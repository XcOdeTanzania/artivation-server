@extends('layouts.app')

@section('content')
    @include('layouts.coursel')

    <div class=" mt-1 pt-3 " style="background-color: #c7dddb;">

        <!--Grid row-->
        <div class="row m-2 ">


        @foreach($pieces->reverse() as $piece)
            <!--Grid column-->
                <div class="col-lg-2 col-md-12 mb-4">

                    <!--Image-->
                    <div class="view overlay z-depth-1-half ">
                        <img src="{{$piece['image']}}" class="img-fluid"
                             alt="">
                        <a href="">
                            <div class="mask rgba-white-light"></div>
                        </a>
                    </div>

                </div>
                <!--Grid column-->
    @endforeach
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
