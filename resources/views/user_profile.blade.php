<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 31-Oct-19
 * Time: 17:21
 */
?>

@extends('layouts.manage')

@section('content')
    @section('nav-bar')
    @include('includes.profile_nav')
    @endsection
    <div class="d-flex justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-8 col-sm-10 m-2 ">
            <div class="card w-100 p-2 " style="background-color: #e7f3f3">
                <div class="card-header">
                    <h3>My Profile</h3>
                </div>
                <div class="d-flex justify-content-center">
                    <img src="@if (Auth::user()['photo_url']){{ Auth::user()['photo_url']}}  @else {{ asset('img/user.jpg') }} @endif" alt="" style=""
                         class="rounded-circle p-1 m-1 w-25  "/>
                </div>

                <div class="card-body">

                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="background-color: #e7f3f3">Name: {{Auth::user()['username']}}</li>
                    <li class="list-group-item " style="background-color: #e7f3f3">
                        Email: {{Auth::user()['email']}}</li>
                    <li class="list-group-item text-capitalize" style="background-color: #e7f3f3">
                        Phone: {{Auth::user()['phone']}}</li>
                    <li class="list-group-item text-capitalize" style="background-color: #e7f3f3">
                        Sex: {{Auth::user()['sex']}}</li>
                </ul>

            </div>
        </div>
    </div>


@endsection
