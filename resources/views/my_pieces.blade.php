<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 29-Oct-19
 * Time: 13:41
 */
?>

@extends('layouts.manage')
@section('nav-bar')
    @include('includes.artist_nav')
@endsection
@section('content')

    <div class="container min-vh-100">
        <div class="row w-100 d-flex justify-content-center">
            @foreach($pieces as $piece)
                <div class="card col-xl-4 col-lg-5 col-md-8 col-sm-10 m-2 p-0">
                    <div class="card-body ">
                        <div class="media">
                            <img src="{{$piece['image']}}" class="mr-3" style="width: 100px" alt="">
                            <div class="media-body">

                                <h5 class="mt-0">{{$piece['title']}}</h5>
                                {{$piece['desc']}}
                                <div class="row justify-content-center">
                                <span class="border-top my-2 border-dark text-muted w-100 mx-2 "
                                      style="opacity: .5"></span>
                                </div>
                                <p><span class="pr-2">Amount: {{$piece['price']}}</span> | <span
                                            class="text-capitalize pl-2">
                                    Category: {{ $piece->category()->first()['name']}}
                                </span></p>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row d-flex justify-content-around">
                            <a href="/piece/view/{{$piece['id']}}" class="btn btn-info">View</a>
                            <a href="/piece/edit/{{$piece['id']}}" class="btn btn-success">Edit</a>
                            <a onclick="deleteItem({{$piece['id']}});" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>



    <Script type="application/javascript" event="onclick">


        function deleteItem(pieceId) {

            var data = {"_token": "{{ csrf_token() }}"};
            $.ajax({
                url: "/piece/delete/" + pieceId,
                type: "DELETE",
                data: JSON.stringify(data),
                cache: false,
                contentType: 'application/json; charset=utf-8',
                processData: false,
                success: function (response) {
                    console.log(response);
                },
                error: function () {
                    console.log('Error Occurred');
                }
            });
        }


    </Script>

@endsection
