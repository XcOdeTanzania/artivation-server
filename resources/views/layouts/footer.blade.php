<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 22-Oct-19
 * Time: 08:40
 */
?>

<!-- Footer -->
<footer class="page-footer font-small pt-4" style="background-color: #8adad7">

    <!-- Footer Links -->
    <div class="container-fluid text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-3 mb-md-0 mb-3">

                <!-- Links -->
                <h5 class="text-uppercase text-center">Categories</h5>

                <ul class="list-unstyled text-capitalize p-1">
                    @foreach($categories as $category)
                        @if ($loop->iteration  % 2 != 0)
                            <div class="row ">
                                <div class="col text-left">
                                    <li>
                                        <a href="/gallery/{{$category['id']}}/0">{{$category['name'] }}</a>
                                    </li>
                                </div>
                                @if(count($categories) == $loop->iteration)
                                    <div class="col">
                                        {{--Empty column--}}
                                    </div>
                                @endif
                                @else

                                    <div class="col text-left">
                                        <li>
                                            <a href="/gallery/{{$category['id']}}/0">{{$category['name'] }}</a>
                                        </li>
                                    </div>
                            </div>
                        @endif

                    @endforeach

                </ul>

            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-6 mt-md-0 mt-3">

                <!-- Content -->

                @if(Auth::user())
                    <h5 class="text-uppercase">Mail Us</h5>
                    <div class="form-group">
                        <input placeholder="About" class="form-control mb-2" type="text">
                        <textarea placeholder="Message" class="form-control rounded-0 mb-2"
                                  id="exampleFormControlTextarea2" rows="3"></textarea>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success text-right mr-1">Send</button>
                        </div>

                    </div>
                @else
                    <h5 class="text-uppercase">Sign Up</h5>

                    <div class="form-group">
                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="col">
                                <input placeholder="Name"
                                       class="form-control mb-2 {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       value="{{ old('name') }}" name="name" type="text" required>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col">
                                <input placeholder="Email"
                                       class="form-control mb-2 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       value="{{ old('email') }}" name="email" type="email"  required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class=" form-row mb-2 ml-1 mr-1">
                                <div class="col">
                                    <input placeholder="Password" class="form-control  mr-2 {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                           type="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col">
                                    <input placeholder="Confirm Password" class="form-control "
                                           name="password_confirmation" type="password" required>
                                </div>

                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Register</button>
                            </div>
                        </form>

                    </div>
                @endif


            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none pb-3">


            <!-- Grid column -->
            <div class="col-md-3 mb-md-0 mb-3">

                <!-- Links -->
                <h6 class="text-uppercase font-weight-bold">GET IN TOUCH</h6>
                <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60%;">
                {{--<p class="p-1 text-center font-weight-bold">--}}
                {{--<a href="https://www.artivation.co.tz"> Artivation </a></p>--}}
                <p class="mb-1 text-left">
                    <i class="fa fa-envelope mr-3"></i> artivation18@gmail.com</p>
                <p class="mb-1 text-left">
                    <i class="fa fa-phone mr-3"></i> +255 779 530 559
                </p>

                <p class="mb-1 text-left">
                    <i class="fa fa-inbox mr-3"></i>
                    P. O. Box 995
                    <br/>
                    <i class=" mr-4 pr-2"></i>
                    Msufini, Zanzibar, Tanzania
                </p>

            </div>
            <!-- Grid column -->

        </div>
        <!-- Grid row -->

    </div>
    <!-- Footer Links -->
    <!-- Copyright -->
    <div class="footer-copyright text-center  py-3" style="background-color: #45cac5">Artivation © 2019

        {{--<span>--}}
        {{--crafted By--}}
        {{--<a href="https://qlicue.co.tz"> Qlicue</a>--}}
        {{--</span>--}}

    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->
