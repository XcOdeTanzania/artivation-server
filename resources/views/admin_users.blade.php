<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 02-Nov-19
 * Time: 13:50
 */
?>

@extends('layouts.manage')

@section('nav-bar')
    @include('includes.admin_nav')
@endsection

@section('content')
    <div class="container min-vh-100">
        <div class="d-flex justify-content-center">
            <div class="d-flex col-xl-8 col-lg-9 col-md-10 col-sm-12">
                <table class="table table-bordered  text-center ">
                    <thead>
                    <td>SN</td>
                    <td>Name</td>
                    <td class="text-center">Email</td>
                    <td>Role</td>
                    </thead>
                    <tbody>

                    @foreach($users as $user)
                        <tr>
                            <td>
                                {{$loop->iteration}}
                            </td>
                            <td>
                                {{$user['username']}}
                            </td>
                            <td class="text-center">
                                {{$user['email']}}
                            </td>
                            <td>
                                {{$user->role()['name']}}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
