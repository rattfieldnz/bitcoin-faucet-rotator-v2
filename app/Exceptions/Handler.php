<?php

namespace App\Exceptions;

use App\Helpers\Functions\Http;
use App\Traits\RestTrait;
use Exception;
use \Google_Service_Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Log;
use Sentry;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Whoops\Exception\ErrorException;

/**
 * Class Handler
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    use RestTrait;

    private $sentryID;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        //AuthorizationException::class,
        //HttpException::class,
        //ModelNotFoundException::class,
        //ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        try {
            if (app()->bound('sentry') && $this->shouldReport($e)) {
                app('sentry')->captureException($e);
            }
            parent::report($e);
        } catch (Exception $e) {
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        // Show full error stack trace info - if environment is set to 'local',
        // and if app debugging is set to true.
        //if(config('app.debug') && config('app.env') == 'local') {
        //    return parent::render($request, $e);
       // }

        if ($this->isHttpException($e)) {
            $exception = FlattenException::create($e);
            $statusCode = $exception->getStatusCode();

            if ($this->isApiCall($request)) {
                return Http::jsonException(
                    'error',
                    $statusCode,
                    !empty($e->getMessage()) ? $e->getMessage() : Http::getHttpMessage($statusCode)
                );
            } else {
                return response()->view('errors.' . $statusCode, ['sentryID' => Sentry::getLastEventID()], $statusCode);
            }
        }

        if ($e instanceof ValidationException) {
            return redirect()->back()->withErrors($e->validator->getMessageBag()->toArray());
        }

        if ($e instanceof TokenMismatchException) {
            Log::error($e->getMessage()); // Log this exception, in case further debugging/troubleshooting is meeded.
            flash("The login form has expired, please try again.")->error();
            return redirect(route('login'));
        }

        if ($e instanceof ErrorException) {
            Log::error($e->getMessage());

            if ($this->isApiCall($request) || $request->ajax()) {
                return Http::jsonException(
                    'error',
                    500,
                    !empty($e->getMessage()) ? $e->getMessage() : Http::getHttpMessage(500)
                );
            } else {
                return response()->view('errors.500', [
                    'message' => !empty($e->getMessage()) ? $e->getMessage() : Http::getHttpMessage(500),
                    'sentryID' => Sentry::getLastEventID()
                ], 500);
            }
        }

        if ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();
            $baseModel = new $model;
            $item = class_basename($baseModel);

            if ($this->isApiCall($request) || $request->ajax()) {
                return Http::jsonException(
                    'error',
                    404,
                    $item . " was not found"
                );
            } else {
                return response()->view('errors.404', compact('item'), 404);
            }
        }

        if ($e instanceof NotFoundHttpException) {
            $item = "page / url";

            if ($this->isApiCall($request) || $request->ajax()) {
                return Http::jsonException(
                    'error',
                    404,
                    "The page/url was not found"
                );
            } else {
                return response()->view('errors.404', compact('item'), 404);
            }
        }

        if ($e instanceof Google_Service_Exception) {
            if ($this->isApiCall($request) || $request->ajax()) {
                return Http::jsonException(
                    'error',
                    500,
                    "Google Analytics API daily usage exceeded!"
                );
            } else {
                return response()->view('errors.500', [
                    'message' => "Google Analytics API daily usage exceeded!",
                    'sentryID' => Sentry::getLastEventID()
                ], 500);
            }
        }

        if ($e instanceof FatalErrorException) {
            if ($this->isApiCall($request) || $request->ajax()) {
                return Http::jsonException(
                    'error',
                    500,
                    !empty($e->getMessage()) ? $e->getMessage() : Http::getHttpMessage(500)
                );
            } else {
                return response()->view('errors.500', [
                    'message' => $e->getMessage(),
                    'sentryID' => Sentry::getLastEventID()
                ], 500);
            }
        }

        if ($e instanceof Exception) {
            if ($this->isApiCall($request) || $request->ajax()) {
                return Http::jsonException(
                    'error',
                    500,
                    !empty($e->getMessage()) ? $e->getMessage() : Http::getHttpMessage(500)
                );
            } else {
                return response()->view('errors.500', [
                    'message' => !empty($e->getMessage()) ? $e->getMessage() : Http::getHttpMessage(500),
                    'sentryID' => Sentry::getLastEventID()
                ], 500);
            }
        }

        // Default is to render an error 500 page
        return response()->view('errors.500', [
            'message' => !empty($e->getMessage()) ? $e->getMessage() : Http::getHttpMessage(500),
            'sentryID' => Sentry::getLastEventID()
        ], 500);
    }
}
