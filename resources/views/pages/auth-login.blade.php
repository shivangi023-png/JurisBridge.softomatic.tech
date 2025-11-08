@extends('layouts.fullLayoutMaster')
{{-- page title --}}
@section('title','Login Page')
{{-- page scripts --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/authentication.css')}}">
@endsection

@section('content')
<!-- login page start -->
<section id="auth-login" class="row flexbox-container">

    <div class="col-xl-8 col-11">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
        <div class="alert bg-rgba-{{ $msg }} alert-dismissible mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <div class="d-flex align-items-center">
                @if(Session::has('alert-success'))
                <i class="bx bx-like"></i>
                @else
                <i class="bx bx-error"></i>
                @endif
                <span>
                    {{ Session::get('alert-' . $msg) }}
                </span>
            </div>
        </div>
        @endif
        @endforeach


        <div class="card bg-authentication mb-0">
            <div class="row m-0 card_row">
                <!-- left section-login -->
                <div class="col-md-6 d-md-block d-none ">
                    <div class="row">
                        <div class="col-md-3">
                            <img class="img-fluid" src="{{asset('images/pages/lighting.png')}}" alt="branding logo">
                        </div>
                        <div class="col-md-9 client_management_div  align-self-center">
                            Client Management!
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center align-self-center">
                            <img class="img-fluid" src="{{asset('images/pages/vector-image.png')}}" alt="branding logo">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12 px-0">
                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center"
                        style="background-color:black">
                        <div class="card-header pb-1 text-center align-self-center">
                            <div class="card-title">
                                <div class="logo_div">
                                    <img class="img-fluid logo_img" src="{{asset('images/pages/logo.png')}}"
                                        alt="branding logo">
                                </div>

                            </div>
                        </div>
                        <div class="card-body">


                            <form action="" method="post">
                                {{ csrf_field() }}
                                <div class="form-group input_div">
                                    <input type="email" class="form-control input_login" name="username"
                                        id="exampleInputEmail1" placeholder="Email address">
                                </div>
                                <div class="form-group">

                                    <input type="password" class="form-control input_login" name="password"
                                        id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div
                                    class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                    <div class="text-left">

                                    </div>
                                    <div class="text-right"><a href="forgot_password" class="card-link forgot_a">Forgot
                                            Password?</a></div>
                                </div>

                                <p class="text-center"><button type="submit"
                                        class="btn btn-primary glow w-50 round text-center">Login</button></p>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- right section image -->

            </div>
        </div>
    </div>
</section>
<!-- login page ends -->

@endsection