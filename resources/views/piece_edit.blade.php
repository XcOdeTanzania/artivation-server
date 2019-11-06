<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 28-Oct-19
 * Time: 12:02
 */
?>

@extends('layouts.app')

@section('content')

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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Art Piece') }}</div>

                <div class="card-body">
                    <img id="blah" src="{{$piece['image']}}" class="card-img-top mb-2" alt=""/>
                    <form method="POST" action="/piece/edit/{{$piece['id']}}">
                        @csrf

                        <div class="form-group row">
                            <label for="image" class="col-md-4 col-form-label text-md-right"> Image </label>
                            <div class="col-md-6">
                                <input type="file" class="form-control-file" id="image" name="image"
                                       onchange="readURL(this);">
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text"
                                       class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                                       value="{{$piece['title']}}" required >

                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="desc" class="col-md-4 col-form-label text-md-right ">{{ __('Description')
                            }}</label>

                            <div class="col-md-6">
                            <textarea class="form-control {{ $errors->has('desc') ? ' is-invalid' : '' }}"
                                      name="desc" id="description" rows="3"
                                      required> {{$piece['desc']}} </textarea>
                                @if ($errors->has('desc'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('desc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="category"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>
                            <div class="col-md-6">
                                <select  class="form-control text-capitalize {{ $errors->has('price') ? ' is-invalid' : '' }}"
                                         name="category_id" required>
                                    @foreach($categories as $category)
                                        <option @if($category['id'] == $piece['category_id'])  selected @endif label="{{$category['name']}}" class="text-capitalize">{{$category['id']}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category_id') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>


                        <div class="form-group row">
                            <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>

                            <div class="col-md-6">
                                <input id="price" type="number"
                                       class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price"
                                       value="{{$piece['price']}}" required>

                                @if ($errors->has('price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="size" class="col-md-4 col-form-label text-md-right">{{ __('Size') }}</label>

                            <div class="col-md-6">
                                <input id="size" type="text" placeholder="eg Width 250 cm height 120cm"
                                       class="form-control{{ $errors->has('size') ? ' is-invalid' : '' }}" name="size"
                                       value="{{$piece['size']}}" required>

                                @if ($errors->has('size'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('size') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row mb-0">

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary ">
                                    {{ __('Submit Changes') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
