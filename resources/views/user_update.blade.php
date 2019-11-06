<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 31-Oct-19
 * Time: 18:57
 */
?>
@extends('layouts.manage')

@section('content')
    @include('includes.profile_nav')
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <div class="row justify-content-center">
        <div class="col-md-8 mt-2">
            <div class="card">
                <div class="card-header">{{ __('Update Profile') }}</div>

                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <img id="blah"
                             src="@if (Auth::user()['photo_url']){{ Auth::user()['photo_url']}}  @else {{ asset('img/user.jpg') }} @endif"
                             class="card-img-top mb-2 w-25" alt=""/>

                    </div>
                   <form method="POST" action="{{route('update.profile')}}"  enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="image" class="col-md-4 col-form-label text-md-right"> Profile Picture </label>
                            <div class="col-md-6">
                                <input type="file" class="form-control-file" id="image" name="image"
                                       onchange="readURL(this);">
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text"
                                       class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username"
                                       value="{{Auth::user()['username']}}" required >

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="sex"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Sex') }}</label>
                            <div class="col-md-6">
                                <select  class="form-control text-capitalize {{ $errors->has('sex') ? ' is-invalid' : '' }}"
                                         name="sex" required>
                                    <option></option>
                                    <option @if(Auth::user()['sex'] === 'Male') selected @endif value="Male">Male</option>
                                    <option @if(Auth::user()['sex'] === 'Female') selected @endif value="Female">Female</option>
                                </select>
                                @if ($errors->has('sex'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('sex') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>


                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="tel"
                                       class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone"
                                       value="{{Auth::user()['phone']}}" required>

                                @if ($errors->has('price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row mb-0">

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary ">
                                    {{ __('Update Profile') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
