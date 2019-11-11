<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 01-Nov-19
 * Time: 19:42
 */
?>

<nav class="navbar navbar-expand-lg navbar-light  " style="background-color: #45cac577;">
    <a class="navbar-brand" href="/">
        {!!  HTML::image('img/logo.png','alt', array(  'height' => 50 )) !!}
        Artist
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ request()->is('piece/create') ? 'active' : '' }} ">
                <a class="nav-link" href="{{route('piece.create')}}">New Artwork <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item {{ request()->is('artist/pieces') ? 'active' : '' }} ">
                <a class="nav-link" href="{{route('artist.pieces')}}">My Artworks</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('home')}}"> Home </a>
            </li>

        </ul>

        <ul class="navbar-nav">
            @if(Auth::check())

                <span class="border rounded-pill border-dark  ">
                    <ul class="navbar-nav  ">
                        <li class="nav-item ">
                         <img src="{{ asset('img/user.jpg') }}" style="height: 30px;"
                              class="rounded-circle p-1 m-1"/>
                        </li>
                        <li class="nav-item dropdown align-content-center  ">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                       {{Auth::user()->username}}

                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                        <a class="dropdown-item" href="/logout">Logout</a>

                        <a class="dropdown-item" href="/user/profile">Profile</a>

                        @if(Auth::user() && Auth::user()->role() && Auth::user()->role()['name'] == 'Administrator')
                            <a class="dropdown-item" href="/admin/dashboard">Admin Dashboard</a>
                        @endif

                        @if(Auth::user() && Auth::user()->role() && Auth::user()->role()['name'] == 'Artist')
                            <a class="dropdown-item" href="{{route('piece.create')}}">Artist Dashboard</a>
                        @endif

                    </div>
                </li>
                    </ul>

                </span>
                <ul class="navbar-nav">
                    <li class="p-2"></li>

                    <li>
                        <div class="cart">
                            <a href="{{route('cart')}}">
                                <span class="cart-badge">{{Auth::user()->cartPieces()->get()->count()}}</span>
                                <img src="{{ asset('img/cart.png') }}" style="height: 35px;" alt=""/>
                            </a>
                        </div>
                    </li>
                    <li class="p-1"></li>
                </ul>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/register') }}">Register</a>
                </li>

            @endif
        </ul>

    </div>
</nav>
