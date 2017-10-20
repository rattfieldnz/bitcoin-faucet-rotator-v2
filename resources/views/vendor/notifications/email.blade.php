@extends('beautymail::templates.sunny')

<?php
    $logo = Config::get('beautymail.view.logo');
?>

@section('content')

    <tr>
        <td class="w50" width="50"></td>
        <td class="w560" width="560">
            <table class="w560" border="0" cellpadding="0" cellspacing="0" width="560">
                <tbody>
                <tr><td class="w560" height="15" width="560"></td></tr>
                <tr class="large_only"><td class="w560" height="15" width="560"></td></tr>
                <tr class="large_only"><td class="w560" height="15" width="560"></td></tr>
                <tr>
                    <td class="w560" width="560">
                        <div class="article-content" align="left">
                            <h1 style="font-size: 5em;">Hello!</h1>
                            <h3 style="font-size: 2em;">{{ env('APP_NAME') }} password reset email confirmation:</h3>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td class="w50" width="50"></td>
    </tr>
    <tr>
        <td class="w40" width="40"></td>
        <td class="w560" width="560">
            <table class="w560" border="0" cellpadding="0" cellspacing="0" width="560">
                <tbody>
                <tr><td class="w560" height="15" width="560"></td></tr>
                <tr><td class="w560" height="15" width="560"></td></tr>
                <tr>
                    <td class="w560" width="560">
                        <div class="article-content" align="left">

                            @foreach ($introLines as $line)
                                <p>{{ $line }}</p>
                            @endforeach

                                <div class="button-content">
                                    <p style="text-align: center !important;"><a href="{{ $actionUrl }}" class="button">{{ $actionText }}</a></p>
                                </div>

                            @if (! empty($salutation))
                                {{ $salutation }}
                            @else
                                    <p>Regards,</p>

                                <p><a href="{{ env('APP_URL') }}" target="_blank" title="{{ env('APP_NAME') }}">{{ env('APP_NAME') }}</a> admin.</p>
                                <hr>
                            @endif

                                <p>If you did not request a password reset, no further action is required.</p>

                            @isset($actionText)
                                <p>If you’re having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
                                    into your web browser: {{ $actionUrl }}</p>
                            @endisset

                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td class="w40" width="40"></td>
    </tr>
    <tr>
        <td colspan="3" height="30"></td>
    </tr>
    <tr>
        <td class="w50" width="50"></td>
        <td class="w560" width="560">
            <table class="w560" border="0" cellpadding="0" cellspacing="0" width="560">
                <tbody>
                <tr class="large_only"><td class="w560" height="15" width="560"></td></tr>
                <tr>
                    <td class="w560" width="560">

                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td class="w50" width="50"></td>
    </tr>

@stop