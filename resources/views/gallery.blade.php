<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 24-Oct-19
 * Time: 14:51
 */

?>


@extends('layouts.app')



@section('content')
    @if (session('operation'))
        <div class="alert alert-success">
            {{ session('operation') }}
        </div>
    @endif
    <div class="col">
        <div class="row ml-2">
            <div class="dropdown m-1">
                <button class="btn btn-secondary dropdown-toggle text-capitalize" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{$activeCategory['name']}}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item text-capitalize" href="/gallery/0/{{$activeArtist['id']}}">All
                        Categories</a>
                    @foreach($categories as $category)
                        <a class="dropdown-item text-capitalize"
                           href="/gallery/{{$category['id']}}/{{$activeArtist['id']}}">{{$category['name']}}</a>
                    @endforeach
                </div>
            </div>
            <div class="dropdown m-1">
                <button class="btn btn-secondary dropdown-toggle text-capitalize" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{$activeArtist['name']}}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="/gallery/{{$activeCategory['id']}}/0">All Artists</a>
                    @foreach($artists as $artist)
                        <a class="dropdown-item"
                           href="/gallery/{{$activeCategory['id']}}/{{$artist['id']}}">{{$artist->user()->first()['username']}}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-columns p-2 ">
            @foreach($pieces as $piece)
                <div class="card ">
                    <img src="{{$piece['image']}}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{$piece['title']}}</h5>
                        <p class="card-text">{{$piece['desc']}}.</p>
                        <div class="row p-2 ">
                            <div class="panel-group ">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <small class="panel-title">
                                            <a data-toggle="collapse" href="#collapse{{$piece['id']}}">Details</a>
                                        </small>
                                    </div>
                                    <div id="collapse{{$piece['id']}}" class="panel-collapse collapse ">
                                        <div class="panel-body text-capitalize">
                                            <p class="text-capitalize"> Category: {{$piece['category']}} </p>
                                            <p> Dimensions: {{$piece['size']}} </p>
                                        </div>
                                        <div class="panel-footer border-dark">
                                            <div class=" text-capitalize ">
                                                By: {{$piece['artist']}}
                                            </div>
                                        </div>
                                        <small class="text-capitalize">
                                            <a href="/piece/view/{{$piece['id']}}">view</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <p class="ml-auto">
                                <small><strong>Price: {{$piece['price']}}/- TSH </strong></small>
                            </p>
                        </div>

                    </div>
                    <div class="card-footer bg-transparent border-success ">
                        <div class="row ">

                         <span class="d-inline-block " @if(!Auth::check()) data-toggle="tooltip" data-placement="top"
                               title="Login first" @endif>
                        <a id="likePiece{{$piece['id']}}"
                           onclick="likeItem(this,{{$piece['id']}},{{Auth::user()['id']}});"
                           class="@if(!Auth::check())  disabled @endif">
                            <i class="fa @if($piece['like_status'])  fa-heart
                                          @else  fa-heart-o @endif
                                    m-1" style="color: red"></i>
                        </a>

                        <small class="p-1">{{$piece['like_counts']}} likes </small>
                         </span>
                            <span class="d-inline-block ml-auto" @if(!Auth::check()) data-toggle="tooltip"
                                  data-placement="top" title="Login first" @endif>
                        <a id="cartPiece{{$piece['id']}}" onclick="addToCart(this,{{$piece['id']}});"
                           class="btn btn-outline-success  ml-auto @if(!Auth::check())  disabled @endif">
                            <i class="fa fa-shopping-cart m-1" style="color: green"></i>
                            @if(!$piece['cart_status'])  Add To Cart @else  Remove From Cart @endif</a>
                        </span>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <Script type="application/javascript" event="onclick">


        function likeItem(_this, pieceId, userId) {

            var data = {"piece_id": pieceId, "user_id": userId, "_token": "{{ csrf_token() }}"};

            $.ajax({
                url: "/like",
                type: "POST",
                data: JSON.stringify(data),
                cache: false,
                contentType: 'application/json; charset=utf-8',
                processData: false,
                success: function (response) {
                    //  alert(response['msg']);
                    console.log(response);
                    if (response['status']) {

                        updateText(_this, response['count'], "fa fa-heart");
                    }
                    else {
                        updateText(_this, response['count'], "fa fa-heart-o");
                    }
                },
                error: function () {
                    console.log('Error Occurred !');
                }

            });
        }

        function updateText(btn, newCount, iconClass, verb) {
            verb = verb || "";
            $(btn).html('<i style="color: red" class="' + iconClass + '"></i><small class="p-1"> ' + newCount + ' likes </small> ' + verb)
            btn.attr("data-likes", newCount)
        }

        function addToCart(btn, pieceId) {
            var data = {"piece_id": pieceId, "user_id": {{Auth::user()['id']}}, "_token": "{{ csrf_token() }}"};

            $.ajax({
                url: "/cart",
                type: "POST",
                data: JSON.stringify(data),
                cache: false,
                contentType: 'application/json; charset=utf-8',
                processData: false,
                success: function (response) {
//                      alert(response['msg']);
//                    console.log(response['status']);
                    if (response['status']) {
                        $(btn).html('<i class="fa fa-shopping-cart m-1" style="color: green"></i> Remove From Cart')
                        btn.attr("cart", '')
                    }
                    else {
                        $(btn).html('<i class="fa fa-shopping-cart m-1" style="color: green"></i> Add To Cart')
                        btn.attr("cart", '')
                    }
                },
                error: function () {
                    console.log('Error Occurred !');
                }
            });
        }


        //        function updateText(btn, newCount, iconClass, verb) {
        //            verb = verb || "";
        //            $(btn).html(newCount + '<i style="color: red" class="' + iconClass + '"></i><small class="p-1"> newCount </small> ' + verb)
        //            btn.attr("data-likes", newCount)
        //        }

    </Script>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection