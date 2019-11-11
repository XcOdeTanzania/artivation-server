<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 04-Nov-19
 * Time: 03:31
 */


/// https://play.google.com/store/apps/details?id=com.qlicue.artivation
///https://apps.apple.com/tz/app/artivation/id1468798493
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="card mb-4">
                <img src="{{asset('img/android_n_ios.jpg')}}" class="card-img-top" alt="...">
                <div class="card-img-overlay text-center d-flex flex-column justify-content-center">
                    <div class="row ">
                        <div class="col w-50 text-center">

                            <a href="https://play.google.com/store/apps/details?id=com.qlicue.artivation">
                                <img src="{{asset('img/play_store.png')}}" alt="">

                            </a>

                        </div>
                        <div class="col w-50 text-center">
                            <a href="https://apps.apple.com/tz/app/artivation/id1468798493">
                                <img src="{{asset('img/app_store.png')}}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
                {{--<div class="card-body">--}}

                {{--</div>--}}
                {{--<div class="card-footer bg-transparent border-success ">--}}
                    {{----}}

                {{--</div>--}}
            </div>

        </div>
    </div>


@endsection
