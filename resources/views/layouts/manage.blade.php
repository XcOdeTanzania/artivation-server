<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <style>
        .cart-badge {
            position: absolute;
            right: -5px;
            top: 10px;
            background: red;
            text-align: center;
            border-radius: 25px 25px 25px 25px;
            color: white;
            padding: 2px 5px;
            font-size: 10px;
        }

        .cart {
            position: relative;
            padding-top: 5px;
            display: inline-block;
        }

        @media (max-width: 575px) {
            .card-columns {
                -webkit-column-count: 1;
                -moz-column-count: 1;
                column-count: 1;
            }
        }

        @media (min-width: 576px) {
            .card-columns {
                -webkit-column-count: 1;
                -moz-column-count: 1;
                column-count: 1;
            }
        }

        @media (min-width: 768px) {
            .card-columns {
                -webkit-column-count: 2;
                -moz-column-count: 2;
                column-count: 2;
            }
        }

        @media (min-width: 992px) {
            .card-columns {
                -webkit-column-count: 3;
                -moz-column-count: 3;
                column-count: 3;
            }
        }

        @media (min-width: 1200px) {
            .card-columns {
                -webkit-column-count: 4;
                -moz-column-count: 4;
                column-count: 4;
            }
        }

        body {
            min-height: 100vh;
            position: relative;
            margin: 0;
        }

        footer {
            position: absolute;
            bottom: 0;
        }



    </style>

    <!-- Styles -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body >
@yield('nav-bar')
<div id="manage" class="">
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button class="close" type="button" data-dismiss="alert"><span>&times;</span></button>
            {{ session('error') }}
        </div>
    @endif
    @if (session('msg'))
        <div class="alert alert-success alert-dismissible">
            <button class="close" type="button" data-dismiss="alert"><span>&times;</span></button>
            {{ session('msg') }}
        </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>
</div>
<div class="m-4" style="height:50px">

</div>
<footer class="page-footer font-small pt-0 w-100   " style="background-color: #8adad7">
    <div class="footer-copyright text-center  py-3" style="background-color: #45cac5">Artivation © 2019

        {{--<span>--}}
        {{--crafted By--}}
        {{--<a href="https://qlicue.co.tz"> Qlicue</a>--}}
        {{--</span>--}}

    </div>
</footer>

</body>

</html>
