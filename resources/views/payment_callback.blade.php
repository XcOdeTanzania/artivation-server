<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 08-Nov-19
 * Time: 17:43
 */
?>

@extends('layouts.manage')

@section('nav-bar')
    <nav class="navbar navbar-expand-lg navbar-light  " style="background-color: #45cac577;">
        <a class="navbar-brand" href="/">
            {!!  HTML::image('img/logo.png','alt', array(  'height' => 50 )) !!}
            Artivation
        </a>

        <ul class="navbar-nav">
            @if(Auth::check())



            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/register') }}">Register</a>
                </li>

            @endif
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="p-2"></li>

            <li>
                {{--<div class="cart">--}}
                <a href="{{route('home')}}">
                    <i class="fa fa-home fa-2x"></i>
                </a>
                {{--</div>--}}
            </li>
            <li class="p-1"></li>
        </ul>
        </div>
    </nav>
@endsection

@section('content')
    <div class="container">

        <div class="col text-center">
            <h4 class="display-4">
               {{$header}}
            </h4>
            <h4 >
                {{$message}}
            </h4>
        </div>
      <div class="row justify-content-center my-2">
          <div class="col-xl-4 col-lg-6 col-md-8 col-sm-10">
              <ul class=" list-unstyled">
                  <li>Status: {{$status}}</li>

                  <li>Amount: {{$transaction['amount']}}</li>

                  <li>Payed By: {{$transaction['payment_method']}}</li>
              </ul>
          </div>
      </div>
<div class="col mt-4">
    <div class="col text-center">
        <h4 class=" text-uppercase m-2">Purchased Items </h4>
    </div>
    <div class="row">
        <div class="col">
            @foreach($items as $piece)
                <div class="media ml-2 mb-2">
                    <img src="{{$piece['image']}}" class="mr-3" style="width: 100px" alt="...">
                    <div class="media-body">

                        <div class="row">
                            <div class="col w-75">
                                <h5 class="mt-0">{{$piece['title']}}</h5>
                                {{$piece['desc']}}
                               <p>Amount: <strong> {{$piece['price']}} </strong></p>
                            </div>

                        </div>


                    </div>

                </div>
            @endforeach
        </div>
    </div>

</div>
    </div>
@endsection
