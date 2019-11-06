<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 28-Oct-19
 * Time: 15:22
 */
?>

@extends('layouts.manage')
@section('nav-bar')
    @include('includes.admin_nav')
@endsection
@section('content')

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">


    <div class="row d-flex justify-content-center">

        <div class="col-xl-4 col-lg-5 col-md-5 col-sm-12 m-2 ">
            <div class="card">
                <div class="card-header">{{ __('Generate Coupon') }}</div>
                <div class="card-body">
                    <p class="mb-2 text-muted">
                        <small>
                            Generate multiple coupons Here
                        </small>
                    </p>


                    <form method="POST" action="{{route('coupon.generate')}}" class="border rounded">
                        @csrf
                        <input name="registered_by" value="{{Auth::user()['id']}}" hidden>
                        <div class="col m-1">
                            <label for="token_count" class=" col-form-label  "> Number of Coupons </label>
                            <input placeholder="Token Count" value="1"
                                   class="form-control mb-2 {{ $errors->has('token_count') ? ' is-invalid' : '' }}"
                                   value="{{ old('token_count') }}" name="token_count" type="number" required>
                            @if ($errors->has('token_count'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('token_count') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="col m-1">
                            <label for="discount_percent" class=" col-form-label  "> Discount Percent </label>
                            <input placeholder="Discount %"
                                   class="form-control mb-2 {{ $errors->has('token_count_percent') ? ' is-invalid' : '' }}"
                                   value="{{ old('discount_percent') }}" name="discount_percent" type="number" required>
                            @if ($errors->has('discount_percent'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('discount_percent') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="col m-1">
                            <label for="expire_on" class=" col-form-label  "> Expire Date </label>
                            <input type="date" name="expire_on" class="form-control">
                        </div>
                        <div class="form-group row justify-content-center">
                            <button class="btn btn-success  mt-2">
                                Generate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 col-md-5 col-sm-12 m-2 ">
            <div class="card">
                <div class="card-header">{{ __('Register Coupon') }}</div>
                <div class="card-body">
                    <p class="mb-2 text-muted">
                        <small>
                            Register your own token here
                        </small>
                    </p>

                    <form method="POST" action="{{route('coupon.register')}}" class="border rounded">
                        @csrf
                        <input name="registered_by" value="{{Auth::user()['id']}}" hidden>
                        <div class="col m-1">
                            <label for="discount" class=" col-form-label  "> Token </label>
                            <input placeholder="Token"
                                   class="form-control mb-2 {{ $errors->has('token') ? ' is-invalid' : '' }}"
                                   value="{{ old('token') }}" name="token" type="text" required>
                            @if ($errors->has('token'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('token') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="col m-1">
                            <label for="discount" class=" col-form-label  "> Discount Percent </label>
                            <input placeholder="Discount %"
                                   class="form-control mb-2 {{ $errors->has('discount_percent') ? ' is-invalid' : '' }}"
                                   value="{{ old('discount_percent') }}" name="discount_percent" type="number" required>
                            @if ($errors->has('discount_percent'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('discount_percent') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="col m-1">
                            <label for="expire_on" class=" col-form-label  "> Expire Date </label>
                            <input type="date"  name="expire_on" class="form-control"  >
                        </div>

                        <div class="form-group row justify-content-center">
                            <button class="btn btn-success  mt-2">
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>




    </div>


@endsection

@section('footer')
    @include('layouts.footer')
@endsection