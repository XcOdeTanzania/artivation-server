<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 21-Oct-19
 * Time: 11:56
 */
?>

<style>
    @keyframes slide {
        0% {
            transform: translate3d(0, 0, 0);
        }

        100% {
            transform: translate3d(0, var(--height), 0);
        }
    }

    .sliding-image {
        animation: slide 60s linear infinite;
    }
</style>


<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner" style="height: 500px">
        {{--@foreach($pieces as $piece)--}}
        {{--<div class="carousel-item @if($pieces[count($pieces)-1]->id == $piece->id) echo active @endif">--}}
        {{--<img src="{{$piece['image']}}" class="d-block w-100 sliding-image" style="--height: -{{$piece['height']}}px;"--}}
        {{--alt="...">--}}

        <div class="carousel-item active">
            <img src="{{asset('img/img_1.jpg')}}" class="d-block w-100 sliding-image"
                 alt="...">
        </div>
        <div class="carousel-item ">
            <img src="{{asset('img/img_2.jpg')}}" class="d-block w-100 sliding-image"
                 alt="...">

        </div>
        <div class="carousel-item ">
            <img src="{{asset('img/img_3.jpg')}}" class="d-block w-100 sliding-image"
                 alt="...">
        </div>
        <div class="carousel-item ">
            <img src="{{asset('img/img_4.jpg')}}" class="d-block w-100 sliding-image"
                 alt="...">
        </div>

        {{--{!!  HTML::image($piece['image'],'alt', array(  'width' =>1080,'height' => 480 )) !!}--}}
        {{--</div>--}}
        {{--style=" object-fit: none; object-position: top; max-height: 500px; margin-bottom: 1rem;"--}}
        {{--@endforeach--}}
    </div>
</div>