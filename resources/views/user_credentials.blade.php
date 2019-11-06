<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 31-Oct-19
 * Time: 21:10
 */
?>

@extends('layouts.manage')

@section('nav-bar')
    @include('includes.profile_nav')
@endsection

@section('content')

    <div class="container  ">
        @if(session('error'))
            <div class="alert-danger">
                {{session('error')}}ljalad
            </div>
        @endif
        <div class="row d-flex justify-content-center mx-2 my-4">
            <div class="media ml-2 mb-2">
                <img src="@if (Auth::user()['photo_url']){{ Auth::user()['photo_url']}}  @else {{ asset('img/user.jpg') }}  @endif"
                     class="mr-3 rounded-circle " style="width: 100px" alt="">
                <div class="media-body align-self-center">

                    <div class="row d-flex  ">
                        <div class="col ">
                            <h5 class="mt-0">{{Auth::user()['username']}}</h5>
                            {{Auth::user()['email']}}
                        </div>

                    </div>


                </div>

            </div>
        </div>
        <div class="row d-flex justify-content-center ">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-12 m-2 ">
                <div class="card">
                    <div class="card-header">{{ __('Change Password') }}</div>
                    <div class="card-body">


                        <form method="POST" action="{{route('update.password')}}" class="border rounded">
                            @csrf
                            <div class="col m-1">
                                <input placeholder="Current Password"
                                       class="form-control mb-2 {{ $errors->has('current_password') ? ' is-invalid' : '' }}"
                                       name="current_password" type="password" required>
                                @if ($errors->has('current_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col m-1">
                                <input placeholder="New Password"
                                       class="form-control mb-2 {{ $errors->has('new_password') ? ' is-invalid' : '' }}"
                                       name="new_password" type="password" required>
                                @if ($errors->has('new_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col m-1">
                                <input placeholder="Confirm New Password"
                                       class="form-control mb-2 {{ $errors->has('confirm_new_password') ? ' is-invalid' : '' }}"
                                       name="confirm_new_password" type="password" required>
                                @if ($errors->has('confirm_new_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('confirm_new_password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group row justify-content-center">
                                <button class="btn btn-success  mt-2">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-12 m-2 ">
                <div class="card">
                    <div class="card-header">{{ __('Change Email') }}</div>
                    <div class="card-body">


                        <form method="POST" action="{{route('update.email')}}" class="border rounded">
                            @csrf
                            <div class="col m-1">
                                <input placeholder="Email"
                                       class="form-control mb-2 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       value="{{ Auth::user()['email'] }}" name="email" type="email" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col m-1">
                                <input placeholder="Current Password"
                                       class="form-control mb-2 {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" type="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group row justify-content-center">
                                <button class="btn btn-success  mt-2">
                                    Change Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
