<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 26-Oct-19
 * Time: 15:50
 */
?>

@extends('layouts.app')

@section('content')
    <div class="card w-100 p-2 " style="background-color: #e7f3f3">
        <img src="{{$piece['image']}}" class="card-img-top" alt="...">
        <div class="card-body">
            <h4 class="card-title text-uppercase">{{$piece['title']}}</h4>
            <p style=" text-indent: 50px;">{{$piece['desc']}} </p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item" style="background-color: #e7f3f3">Size: {{$piece['size']}}</li>
            <li class="list-group-item text-capitalize" style="background-color: #e7f3f3">
                Artist: {{$piece['artist']}}</li>
            <li class="list-group-item text-capitalize" style="background-color: #e7f3f3">
                Category: {{$piece['category']}}</li>
        </ul>
        @if($accessibility)
            <div class="card-body d-flex">



                <a id="likeItem" onclick="likeItem(this,{{$piece['id']}},{{Auth::user()['id']}});" class="btn @if(!Auth::check())  disabled @endif p-0"  >
                    <i id="heartIcon{{$piece['id']}}" class="fa @if($piece['like_status']) echo fa-heart
                                          @else echo fa-heart-o @endif
                            m-1" style="color: red"></i>
                    <small class="p-1">{{$piece['like_counts']}} likes</small>
                </a>


                <a onclick="addToCart(this,{{$piece['id']}});" class="btn btn-outline-success @if(!Auth::check()) echo disabled @endif  ml-auto">
                    <i class="fa fa-shopping-cart m-1" style="color: green"></i>
                    @if(!$piece['cart_status']) Add To Cart @else Remove From Cart @endif </a>
            </div>
        @endif
    </div>

    <Script type="application/javascript" event="onclick">



        function likeItem(_this,pieceId, userId) {

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
                    if(response['status']){

                        updateText(_this, response['count'], "fa fa-heart");
                    }
                    else {
                        updateText(_this, response['count'], "fa fa-heart-o");
                    }
                },
                error:function () {
                    console.log('Error Occurred !');
                }

            });
        }

        function updateText(btn, newCount, iconClass, verb) {
            verb = verb || "";
            $(btn).html( '<i style="color: red" class="' + iconClass + '"></i><small class="p-1"> ' + newCount +' likes </small> ' + verb )
            btn.attr("data-likes", newCount)
        }

        function addToCart(btn,pieceId) {
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
                    if(response['status']){
                        $(btn).html( '<i class="fa fa-shopping-cart m-1" style="color: green"></i> Remove From Cart' )
                        btn.attr("cart", '')
                    }
                    else {
                        $(btn).html( '<i class="fa fa-shopping-cart m-1" style="color: green"></i> Add To Cart' )
                        btn.attr("cart", '')
                    }
                },
                error:function () {
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