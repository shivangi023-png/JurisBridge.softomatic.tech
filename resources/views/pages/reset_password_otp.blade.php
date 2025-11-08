@extends('layouts.fullLayoutMaster')
{{-- page title --}}
@section('title','Send OTP')
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
        <div id="alert"></div>

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
                        <div class="card-body" id="sec1">
                            <form action="" id="resetPasswordForm">

                                <div class="form-group">

                                    <input type="email" class="form-control" name="user_email" id="email"
                                        placeholder="Email address">
                                </div>
                                <span class="valid_err email_err"></span>
                                <div class="form-group">
                                    <?php $otp = rand(10000, 99999); ?>
                                    <input type="hidden" class="form-control" name="generated_otp" id="generated_otp"
                                        value="<?php echo $otp ?>">
                                </div>
                                <p class="text-center"><button type="button"
                                        class="btn btn-primary glow w-50 round text-center" id="submit">Send
                                        OTP</button></p>
                            </form>

                        </div>

                        <div class="card-body" id="sec2" style="display:none;">
                            <form action="forgot_password" id="sendOtpForm" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="user_id" id="user_id"
                                        placeholder="">
                                    <input type="hidden" class="form-control" name="match_otp" id="match_otp">
                                    <input type="number" class="form-control" name="send_otp" id="send_otp"
                                        placeholder="Enter OTP" required>
                                </div>
                                <span id="error"></span>
                                <p class="text-center"><button type="submit"
                                        class="btn btn-primary glow w-50 round text-center">Continue
                                    </button></p>

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
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/validate.min.js')}}"></script>
<script src="{{asset('js/scripts/pages/additional-methods.min.js.min.js')}}"></script>

<script>
$('#sendOtpForm').validate({
    rules: {
        send_otp: {
            required: true,
            equalTo: '[name="match_otp"]'
        }
    },
    messages: {
        email: {
            required: "Please enter email"
        }
    },
    errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});
</script>
<script>
$(document).on('click', '#submit', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var email = $('#email').val();
    var generated_otp = $('#generated_otp').val();

    if (email == '') {
        $('#alert').html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Email field is required</span></div></div>'
        ).focus();

        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
        return false;
    } else {
        $("#submit").attr("disabled", true);
        $.ajax({
            type: 'post',
            url: 'send_otp',

            data: {
                email: email,
                generated_otp: generated_otp
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);

                if (res.status == 'success') {
                    $("#resetPasswordForm").trigger("reset");
                    $("#sec1").hide();
                    $("#sec2").show();
                    $("#user_id").val(res.id);
                    $("#match_otp").val(res.generated_otp);
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    $('#alert').html(
                        '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                        res.msg + '</span></div></div>'
                    ).focus();

                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });

                } else {
                    $("#resetPasswordForm").trigger("reset");

                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0)
                        },
                        "slow"
                    );
                    $('#alert').html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg + '</span></div></div>'
                    ).focus();
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
                }
            },
            error: function(data) {
                //console.log(data);
                $("#alert").animate({
                        scrollTop: $(window).scrollTop(0)
                    },
                    "slow"
                );

                $('#alert').html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                    res.msg + '</span></div></div>'
                ).focus();
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            }
        });
    }
});
</script>
@endsection