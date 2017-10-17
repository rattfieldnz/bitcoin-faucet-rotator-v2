@if(env('APP_ENV') == 'local')
    <link rel="stylesheet" href="{{ asset("/assets/css/mainStyles.css?" . rand()) }}">
@elseif(env('APP_ENV') == 'production')
    <link rel="stylesheet" href="{{ asset("/assets/css/mainStyles.min.css?" . rand()) }}">
@else
    <link rel="stylesheet" href="{{ asset("/assets/css/mainStyles.css?" . rand()) }}">
@endif
@yield('css')