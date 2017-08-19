<?php

namespace App\Exceptions;

use Exception;
use Google_Service_Exception;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Whoops\Exception\ErrorException;

/**
 * Class Handler
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    private $sentryID;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
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
        if ($this->shouldReport($e)) {
            // bind the event ID for Feedback
            $this->sentryID = app('sentry')->captureException($e);

            app('sneaker')->captureException($e);
        }
        parent::report($e);
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
        if(config('app.debug') && config('app.env') == 'local') {
            return parent::render($request, $e);
        }

        if ($e instanceof TokenMismatchException) {

            Log::error($e->getMessage()); // Log this exception, in case further debugging/troubleshooting is meeded.
            flash("The login form has expired, please try again.")->error();
            return redirect(route('login'));
        }

        if($e instanceof \ErrorException){
            Log::error($e->getMessage());

            return response()->view('errors.500', [
                'message' => $e->getMessage(),
                'sentryID' => $this->sentryID
            ], 500);
        }

        if ($e instanceof ModelNotFoundException) {

            $model = $e->getModel();
            $baseModel = new $model;
            $item = class_basename($baseModel);

            return response()->view('errors.404', compact('item'), 404);
        }

        if($e instanceof Google_Service_Exception){

            dd($e);
            if($request->isAjax()){
                return response()->json(['message' => "Google Analytics API usage exceeded"], 500);
            }

            return response()->view('errors.500', [
                'message' => "Google Analytics API usage exceeded",
                'sentryID' => $this->sentryID
            ], 500);
        }

        if ($this->isHttpException($e))
        {
            $exception = FlattenException::create($e);
            $statusCode = $exception->getStatusCode($exception);

            if (in_array($statusCode, array(403, 404, 418, 500))){
                return response()->view('errors.' . $statusCode, ['sentryID' => $this->sentryID], $statusCode);
            }
        }

        // Default is to render an error 500 page
        return response()->view('errors.500', [
            'message' => $e->getMessage(),
            'sentryID' => $this->sentryID
        ], 500);
    }
}
