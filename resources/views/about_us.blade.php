<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 24-Oct-19
 * Time: 14:52
 */
?>

@extends('layouts.app')

@section('content')

    <div class="container">
        <button type="button" class="btn btn-lg btn-danger" data-toggle="popover" title="Popover title" data-content="And here's some amazing content. It's very engaging. Right?">Click to toggle popover</button>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <!-- Title element with bottom line -->
                <div class="kl-title-block clearfix tbk-symbol--line tbk-icon-pos--after-title">
                    <!-- Title with custom font size, gray color and extrabold style -->
                    <h3 class="tbk__title fs-36 gray fw-extrabold"> For artists to easily display their work A quick
                        searching engine for buyers...</h3>

                    <!-- Title bottom symbol -->
                    <div class="tbk__symbol ">
                        <span></span>
                    </div>
                    <!--/ Title bottom symbol -->
                </div>
                <!--/ Title element with bottom line -->
            </div>
            <!--/ col-md-4 col-sm-4 -->

            <div class="col-md-8 col-sm-8">
                <!-- Text box element -->
                <div class="text_box">
                    <p style="text-indent: 50px">
                        When we talk about the value of arts to society, we always start with its intrinsic
                        value:
                        how arts can illuminate our inner lives and enrich our emotional world. This is what we
                        cherish,
                        However we also understand that arts and has a wider more measurable impact on our
                        economy, health
                        and wellbeing, society and education.
                    </p>
                    <p style="text-indent: 50px">
                        The true impact of art hasn’t been seen in Tanzania because people have not yet
                        understood the power,
                        beauty, changes and the phenomenon of art can bring to the society, this has caused
                        artists to struggle
                        to show their creativity potential, contribution to the society and impact of their
                        work in the society.
                        Tanzanian artist specifically Zanzibar artists address their problems by
                        Self-educating themselves
                        Searching for places frequently visited by tourists e.g. Shangani, Jaws corner
                        Attending commercial festivals e.g. Sauti Za Busara.
                    </p>
                </div>
                <!--/ Text box element -->
            </div>
            <!--/ col-md-4 col-sm-4 -->
        </div>
        <!--/ row -->
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <!-- Text box element -->
                <div class="text_box">
                    <p style="text-indent: 50px">
                        Artivation is a startup project that will attempt to create a website that’ll be
                        innovative and address the problems by being a platform:
                        For artists to easily display their work
                        A quick searching engine for buyers, funds and job opportunities
                        A centre of education for people who want to expand their artistic capabilities and for
                        people who want to become artist.
                    </p>
                    <p style="text-indent: 50px">
                        It’s intentions is to provide employment to overcome poverty and reduce dependency
                        ratio, proper use and expansion of people’s creativity, elevating Zanzibar to become a
                        global contributor to arts and crafts, creating a boom of entrepreneurs, and using art
                        to give back to the society.
                    </p>
                </div>
                <!-- /Text box element -->
            </div>
        </div>
        <!-- row -->

    </div>
    <!--/ container -->

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 my-3">
                <!-- Title element with bottom line and sub-title with custom paddings -->
                <div class="kl-title-block clearfix tbk--left tbk-symbol--line tbk-icon-pos--after-title">
                    <!-- Title with custom montserrat font, size and black color -->
                    <h2 class="tbk__title montserrat fs-20 black">
                        <strong>COMPANY MISSION</strong>
                    </h2>

                    <!-- Title bottom symbol -->
                    <div class="tbk__symbol ">
                        <span></span>
                    </div>
                    <!--/ Title bottom symbol -->

                    <!-- Sub-title with custom font size and thin style -->
                    <h4 class="tbk__subtitle fs-15">Enable artists to unlock their potential so as to illuminate the
                        world.</h4>
                </div>
                <!--/ Title element with bottom line and sub-title -->
            </div>
            <!--/ col-md-12 col-sm-12 -->

            <div class="col-md-4 col-sm-4 my-3">
                <!-- Title element with bottom line and sub-title with custom paddings -->
                <div class="kl-title-block clearfix tbk--left tbk-symbol--line tbk-icon-pos--after-title">
                    <!-- Title with custom montserrat font, size and black color -->
                    <h4 class="tbk__title montserrat fs-20 black">
                        <strong>COMPANY VISION</strong>
                    </h4>

                    <!-- Title bottom symbol -->
                    <div class="tbk__symbol ">
                        <span></span>
                    </div>
                    <!--/ Title bottom symbol -->

                    <!-- Sub-title with custom font size and thin style -->
                    <h4 class="tbk__subtitle fs-15">To facilitate in the reduction of poverty by inspiring creativity in
                        Zanzibar.</h4>
                </div>
                <!--/ Title element with bottom line and sub-title -->
            </div>
            <!--/ col-md-12 col-sm-12 -->

            <div class="col-md-4 col-sm-4 pl-2 my-3">
                <!-- Title element with bottom line and sub-title with custom paddings -->
                <div class="kl-title-block clearfix tbk--left tbk-symbol--line tbk-icon-pos--after-title">
                    <!-- Title with custom montserrat font, size and black color -->
                    <h4 class="tbk__title montserrat fs-20 black">
                        <strong>CORE VALUES</strong>
                    </h4>

                    <!-- Title bottom symbol -->
                    <div class="tbk__symbol ">
                        <span></span>
                    </div>
                    <!--/ Title bottom symbol -->

                    <!-- Sub-title with custom font size and thin style -->
                    <h4 class="tbk__subtitle fs-15">Caring|Equity|Integrity|Empathy|Proactive|<br>Partnership|Compassion|Commitment|
                        Respect for diversity|Social responsibilities.</h4>
                </div>
                <!--/ Title element with bottom line and sub-title -->
            </div>
            <!--/ col-md-12 col-sm-12 -->

        </div>
    </div>



@endsection

@section('footer')
    @include('layouts.footer')
@endsection