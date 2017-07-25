@if(env('APP_ENV') == 'local')
    <link rel="stylesheet" href="/assets/css/mainStyles.css?{{ rand()}}">
@elseif(env('APP_ENV') == 'production')
    <link rel="stylesheet" href="/assets/css/mainStyles.min.css?{{ rand()}}">
@endif
@yield('css')