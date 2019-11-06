<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Artivation</title>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}"/>

</head>
<body>
@include('layouts.navbar')
@if (session('error'))
    <div class="alert alert-success ">
        {{ session('error') }}
    </div>
@endif

@include('layouts.coursel')


<div class=" mt-1 pt-3 " style="background-color: #c7dddb;">

    <!--Grid row-->
    <div class="row m-2 ">


    @foreach($pieces->reverse() as $piece)
        <!--Grid column-->
            <div class="col-lg-2 col-md-12 mb-4">

                <!--Image-->
                <div class="view overlay z-depth-1-half ">
                    <img src="{{$piece['image']}}" class="img-fluid"
                         alt="">
                    <a href="">
                        <div class="mask rgba-white-light"></div>
                    </a>
                </div>

            </div>
            <!--Grid column-->
    @endforeach


        {{--<!--Grid column-->--}}
        {{--<div class="col-lg-2 col-md-6 mb-4">--}}

        {{--<!--Image-->--}}
        {{--<div class="view overlay z-depth-1-half">--}}
        {{--<img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(78).jpg" class="img-fluid"--}}
        {{--alt="">--}}
        {{--<a href="">--}}
        {{--<div class="mask rgba-white-light"></div>--}}
        {{--</a>--}}
        {{--</div>--}}

        {{--</div>--}}
        {{--<!--Grid column-->--}}

        {{--<!--Grid column-->--}}
        {{--<div class="col-lg-2 col-md-6 mb-4">--}}

        {{--<!--Image-->--}}
        {{--<div class="view overlay z-depth-1-half">--}}
        {{--<img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(79).jpg" class="img-fluid"--}}
        {{--alt="">--}}
        {{--<a href="">--}}
        {{--<div class="mask rgba-white-light"></div>--}}
        {{--</a>--}}
        {{--</div>--}}

        {{--</div>--}}
        {{--<!--Grid column-->--}}

        {{--<!--Grid column-->--}}
        {{--<div class="col-lg-2 col-md-12 mb-4">--}}

        {{--<!--Image-->--}}
        {{--<div class="view overlay z-depth-1-half">--}}
        {{--<img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(81).jpg" class="img-fluid"--}}
        {{--alt="">--}}
        {{--<a href="">--}}
        {{--<div class="mask rgba-white-light"></div>--}}
        {{--</a>--}}
        {{--</div>--}}

        {{--</div>--}}
        {{--<!--Grid column-->--}}

        {{--<!--Grid column-->--}}
        {{--<div class="col-lg-2 col-md-6 mb-4">--}}

        {{--<!--Image-->--}}
        {{--<div class="view overlay z-depth-1-half">--}}
        {{--<img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(82).jpg" class="img-fluid"--}}
        {{--alt="">--}}
        {{--<a href="">--}}
        {{--<div class="mask rgba-white-light"></div>--}}
        {{--</a>--}}
        {{--</div>--}}

        {{--</div>--}}
        {{--<!--Grid column-->--}}

        {{--<!--Grid column-->--}}
        {{--<div class="col-lg-2 col-md-6 mb-4">--}}

        {{--<!--Image-->--}}
        {{--<div class="view overlay z-depth-1-half">--}}
        {{--<img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(84).jpg" class="img-fluid"--}}
        {{--alt="">--}}
        {{--<a href="">--}}
        {{--<div class="mask rgba-white-light"></div>--}}
        {{--</a>--}}
        {{--</div>--}}

        {{--</div>--}}
        {{--<!--Grid column-->--}}

    </div>
    <!--Grid row-->

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
@include('layouts.footer')
</html>

{{--<!doctype html>--}}
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
{{--<head>--}}
{{--<meta charset="utf-8">--}}
{{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}

{{--<title>Artivation</title>--}}

{{--<!-- Fonts -->--}}
{{--<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">--}}

{{--<!-- Styles -->--}}
{{--<style>--}}
{{--html, body {--}}
{{--background-color: #fff;--}}
{{--color: #636b6f;--}}
{{--font-family: 'Nunito', sans-serif;--}}
{{--font-weight: 200;--}}
{{--height: 100vh;--}}
{{--margin: 0;--}}
{{--}--}}

{{--.full-height {--}}
{{--height: 100vh;--}}
{{--}--}}

{{--.flex-center {--}}
{{--align-items: center;--}}
{{--display: flex;--}}
{{--justify-content: center;--}}
{{--}--}}

{{--.position-ref {--}}
{{--position: relative;--}}
{{--}--}}

{{--.top-right {--}}
{{--position: absolute;--}}
{{--right: 10px;--}}
{{--top: 18px;--}}
{{--}--}}

{{--.content {--}}
{{--text-align: center;--}}
{{--}--}}

{{--.title {--}}
{{--font-size: 84px;--}}
{{--}--}}

{{--.links > a {--}}
{{--color: #636b6f;--}}
{{--padding: 0 25px;--}}
{{--font-size: 13px;--}}
{{--font-weight: 600;--}}
{{--letter-spacing: .1rem;--}}
{{--text-decoration: none;--}}
{{--text-transform: uppercase;--}}
{{--}--}}

{{--.m-b-md {--}}
{{--margin-bottom: 30px;--}}
{{--}--}}
{{--</style>--}}
{{--</head>--}}
{{--<body>--}}

{{--<div class="flex-center position-ref full-height">--}}
{{--@if (Route::has('login'))--}}
{{--<div class="top-right links">--}}
{{--@auth--}}
{{--<a href="{{ url('/home') }}">Home</a>--}}
{{--@else--}}
{{--<a href="{{ route('login') }}">Login</a>--}}

{{--@if (Route::has('register'))--}}
{{--<a href="{{ route('register') }}">Register</a>--}}
{{--@endif--}}
{{--@endauth--}}
{{--</div>--}}
{{--@endif--}}

{{--<div class="content">--}}
{{--<div class="title m-b-md">--}}
{{--Artivation--}}
{{--</div>--}}

{{--<div class="links">--}}
{{--<a href="https://laravel.com/docs">Documentation</a>--}}
{{--<a href="https://laracasts.com">Laracasts</a>--}}
{{--<a href="https://laravel-news.com">News</a>--}}
{{--<a href="https://nova.laravel.com">Nova</a>--}}
{{--<a href="https://forge.laravel.com">Forge</a>--}}
{{--<a href="https://github.com/laravel/laravel">GitHub</a>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</body>--}}
{{--</html>--}}
