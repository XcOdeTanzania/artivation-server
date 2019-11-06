<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 02-Nov-19
 * Time: 13:52
 */
?>
@extends('layouts.manage')

@section('nav-bar')
    @include('includes.admin_nav')
@endsection

@section('content')
    <div class="container min-vh-100" >
        <div class="d-flex justify-content-center " >
            <div class="d-flex col-xl-8 col-lg-9 col-md-10 col-sm-12">
                <table class="table table-bordered  text-center  ">
                    <thead>
                    <td>SN</td>
                    <td>Created Date</td>
                    <td class="text-center">Discount (%)</td>
                    <td>Used By</td>
                    <td>Expire On</td>
                    </thead>
                    <tbody >

                    @foreach($coupons as $coupon)
                        <tr>
                            <td>
                                {{$loop->iteration}}
                            </td>
                            <td>
                                {{$coupon['created_at']}}
                            </td>
                            <td class="text-center">
                                {{$coupon['discount_percent']}}
                            </td>
                            <td>
                                @if($coupon->user()->first()) {{$coupon->user()->first()['username']}}
                                @else  -
                                @endif
                            </td>
                            <td>
                                {{$coupon['expire_on']}}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <script>
        $('body>.container').height(
            $(window).height()-
            $('body>.container-fluid').height()-
            $('body>footer').height()
        );
    </script>
@endsection
