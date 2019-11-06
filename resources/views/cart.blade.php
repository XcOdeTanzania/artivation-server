<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 24-Oct-19
 * Time: 14:53
 */
?>
@extends('layouts.app')

@section('content')


    <div class=" row w-100 d-flex justify-content-center">
        <div class="row mt-1 mb-4 w-100">
            <div class="panel-group w-100">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row d-flex justify-content-end">
                            <small class="panel-title mr-2 ">
                                <button class="btn btn-outline-info" data-toggle="collapse" href="#collapse">Add Coupon</button>
                            </small>
                        </div>
                    </div>
                    <div id="collapse" class="panel-collapse collapse ">
                        <div class="row d-flex justify-content-center">
                            <div class="panel-body text-capitalize col-xl-6 col-lg-8 col-md-10">
                                <form method="POST" action="{{route('coupon.acquire')}}">
                                    @csrf
                                    <div class="form-group row m-2">
                                        <label for="title" class="col-md-3 col-form-label text-md-right">{{ __('Token') }}</label>

                                        <div class="col-md-6 mb-2">
                                            <input id="token" type="text"
                                                   class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token"
                                                   required >

                                            @if ($errors->has('token'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('token') }}</strong>
                                    </span>
                                            @endif

                                        </div>
                                        <div class="col">
                                            <button type="submit" class="btn btn-primary ">
                                                {{ __(' Submit ') }}
                                            </button>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>

                        <div class="panel-footer border-dark">
                            <div class=" text-capitalize ">

                            </div>
                        </div>
                        <small class="text-capitalize">

                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-8 col-md-10 justify-content-center ">

            @foreach($pieces as $piece)
                <div class="media ml-2 mb-2">
                    <img src="{{$piece['image']}}" class="mr-3" style="width: 100px" alt="...">
                    <div class="media-body">

                        <div class="row">
                            <div class="col w-75">
                                <h5 class="mt-0">{{$piece['title']}}</h5>
                                {{$piece['desc']}}
                            </div>
                            <div class="col d-flex mr-2 align-self-center justify-content-end">
                                {{$piece['price']}}
                            </div>
                        </div>


                    </div>

                </div>
            @endforeach

            <div class="row">
                <span class="border-top my-3 border-dark w-100"></span>
            </div>


            <div class="row mt-2" style="margin-left: 102px">
                <div class="col w-75">

                    <p>
                        Sub Total
                    </p>
                </div>
                <div class="col mr-2 d-flex justify-content-end">

                    <p>
                        {{$subTotal}}
                    </p>
                </div>
            </div>

            <div class="row mt-2" style="margin-left: 102px">
                <div class="col w-75">

                    <p>
                        Tax
                    </p>
                </div>
                <div class="col mr-2 d-flex justify-content-end">

                    <p>
                        {{$tax}}
                    </p>
                </div>
            </div>

            <div class="row mt-2" style="margin-left: 102px">
                <div class="col w-75">

                    <p>
                        Shipping
                    </p>
                </div>
                <div class="col mr-2 d-flex justify-content-end">

                    <p>
                        {{$shipping}}
                    </p>
                </div>
            </div>

            <div class="row mt-2" style="margin-left: 102px">
                <div class="col w-75">

                    <p>
                        Total
                    </p>
                </div>
                <div class="col mr-2 d-flex justify-content-end">

                    <p>
                        {{$total}}
                    </p>
                </div>
            </div>
            @if($discount>0)
                <div class="row mt-2" style="margin-left: 102px">
                    <div class="col w-75">

                        <p>
                            Discount
                        </p>
                    </div>

                    <div class="col mr-2 d-flex justify-content-end">

                        <p>
                            {{$discount}}
                        </p>
                    </div>

                </div>
            @endif
            <div class="row">
                <span class="border-top my-3 border-dark w-100"></span>
            </div>

            <div class="row mt-2" style="margin-left: 102px">
                <div class="col w-75">

                    <h4>
                        <strong>
                            Payable
                        </strong>

                    </h4>
                </div>
                <div class="col mr-2 d-flex justify-content-end">

                    <h4>
                        <strong>
                            {{$payable}}
                        </strong>
                    </h4>

                </div>
            </div>

            <div class="row">
                <span class="border-top my-3 border-dark w-100"></span>
            </div>

            <div class="row m-2 justify-content-center">
                <a href="pesapalUrl/{{Auth::user()['id']}}" class="btn btn-success w-75">Check Out</a>
            </div>

        </div>
    </div>




@endsection

@section('footer')
    @include('layouts.footer')
@endsection