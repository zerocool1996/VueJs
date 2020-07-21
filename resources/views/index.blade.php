<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <!-- Font Awesome CSS-->
        <link rel="stylesheet" href="{{ asset('/assets/vendor/fontawesome/css/all.css') }}">
        {{-- <link rel="stylesheet" href="/assets/vendor/font-awesome/css/font-awesome.min.css"> --}}

        <!-- Custom Font Icons CSS-->
        <link rel="stylesheet" href="{{ asset('/assets/css/font.css') }}">
        <!-- Google fonts - Muli-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="{{ asset('/assets/css/style.default.css') }}" id="theme-stylesheet">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="{{ asset('/assets/css/custom.css') }}">
        <!-- Favicon-->
        <link rel="shortcut icon" href="{{ asset('/assets/img/favicon.ico') }}">

        <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    </head>
    <body>
        <div id="app">
            <router-view></router-view>
            {{-- <master></master> --}}
        </div>
        <script src="{{ asset('/js/app.js') }}"></script>

        <script src="{{ asset('/assets/vendor/popper.js/umd/popper.min.js') }}"> </script>
        <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/assets/vendor/jquery.cookie/jquery.cookie.js') }}"> </script>
        <script src="{{ asset('/assets/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('/assets/js/front.js') }}"></script>
    </body>
</html>
