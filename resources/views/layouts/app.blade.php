<!DOCTYPE html>
<html lang="{{ \App\Models\MainMeta::first()->language()->first()->isoCode() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <!-- START SEO / Meta Tags / Twitter Graph / Open Graph data -->
        @include('layouts.partials.seo._meta-tags-data')
        <!-- END SEO / Meta Tags / Twitter Graph / Open Graph data -->

        <!-- START Site Styles -->
        @include('layouts.partials.styles._css')
        <!-- END Site Styles -->
    </head>
    <body class="skin-blue sidebar-mini" id="{{ empty(Auth::user()) ? 'guest-bg' : 'auth-bg' }}">
        <!-- START Main Content -->
        @include('layouts.partials._main_content')
        <!-- END Main Content -->

        <!-- START Main Footer -->
        @include('layouts.partials.content._footer')
        <!-- END Main Footer -->

        <!-- START Site Scripts/JS -->
        @include('layouts.partials.scripts._js')
        <!-- END Site Scripts/JS -->

        <!-- START Google Analytics tracking -->
        @stack('google-analytics')
        <!-- END Google Analytics tracking -->
    </body>
</html>