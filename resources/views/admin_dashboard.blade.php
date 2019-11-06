<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 30-Oct-19
 * Time: 16:59
 */
?>

@extends('layouts.manage')
@section('nav-bar')
    @include('includes.admin_nav')
@endsection

@section('content')
    {{--@if (session('error'))--}}
    {{--<div class="alert alert-danger ">--}}
    {{--{{ session('error') }}--}}
    {{--</div>--}}
    {{--@endif--}}

    {{--@if (session('msg'))--}}
    {{--<div class="alert alert-success ">--}}
    {{--{{ session('msg') }}--}}
    {{--</div>--}}
    {{--@endif--}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-9">
                <div class="card my-4">
                    <div class="card-header">
                        Assign Role
                    </div>
                    <div class="card-body">
                        <form action="/user/role" method="POST">
                            @csrf
                            <input name="_method" type="hidden" value="PUT">
                            <div class="col m-2 ">
                                <label class="col-form-label" for="email">Email</label>
                                <input name="email" class="form-control" type="text">
                            </div>
                            <div class="col m-2 ">
                                <label class="col-form-label" for="role_id">Role</label>
                                <select name="role_id" class="form-control">
                                    @foreach($roles as $role)
                                        <option @if($role['name'] === 'User') selected @endif value="{{$role['id']}}"
                                        >{{$role['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col my-4 justify-content-center">
                                <button class="btn btn-success form-control" type="submit">Assign Role</button>
                            </div>

                        </form>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-6 mt-2">--}}
    {{--<div class="card">--}}
    {{--<div class="card-header">--}}
    {{--Users--}}
    {{--</div>--}}

    {{--<div class="card-body ">--}}

    {{--</div>--}}

    {{--</div>--}}
    {{--</div>--}}

    {{--</div>--}}
@endsection
