<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 24-Oct-19
 * Time: 14:50
 */
?>

@extends('layouts.app')

@section('content')
    <div class="col">
        <div class="row d-flex justify-content-center">
            {{--@foreach($artists as $artist)--}}
            @foreach($artists as $artist)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 m-2 ">
                    <div class="card bg-dark text-white align-middle" style="width: 100%; ">
                        <img src="{{ asset('img/pallet.jpg') }}" class="card-img" alt="">
                        <div class="card-img-overlay text-center d-flex flex-column justify-content-center"
                             style="background: rgba(0, 0, 0, 0.7) none repeat scroll 0 0;">
                            <img src="@if ($artist['photo_url']){{ $artist['photo_url']}}  @else {{ asset('img/user.jpg') }} @endif" alt="" style="width: 50px;height: 50px"
                                 class="rounded-circle p-1 m-1 "/>
                            <h5 class="card-title text-uppercase">{{$artist['name']}}</h5>
                            <h6>{{$artist['number_of_pieces']}} Artworks</h6>
                            <p>{{$artist['number_of_pieces_bought']}} Sold</p>
                            <a href="/gallery/0/{{$artist['id']}}" class="btn btn-success @if($artist['number_of_pieces']<1) echo disabled @endif ">View Gallery</a>
                            {{--@if($artist['number_of_pieces']>0)--}}
                            {{--@endif--}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@endsection

@section('footer')
    @include('layouts.footer')
@endsection