@extends('layouts.contentLayoutMaster')
{{-- page title --}}
@section('title','New Follow-up')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">

@endsection
{{-- page styles --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-quotation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/wickedpicker/dist/wickedpicker.min.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<!-- app invoice View Page -->
<section class="quotation-edit-wrapper">
    <div class="row">
        <!-- invoice view page -->
        <div class="col-xl-9 col-sm-12 col-lg-12 col-md-12 col-xs-12">
            <div id="alert">


            </div>
            <div class="card">
                <div class="card-body pb-0 mx-25">
                    <!-- header section -->
                    <form method="POST" action='' id="form">
                        {{ csrf_field() }}
                        <div class="row mx-0">
                            <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">

                                <h5 class="invoice-number mb-0 mr-75">Client Follow-up</h6>
                            </div>
                            <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                                <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                                    <div class="d-flex align-items-center">
                                        <span class="mr-75 label_title">Date: </span>

                                        <fieldset class="d-flex ">
                                            <input type="text" class="form-control pickadate mr-2 followup_date" placeholder="Select Date">
                                        </fieldset>
                                        <span class="valid_err followup_date_err"></span>

                                    </div>


                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row pt-50">
                            <div class="col-12 col-md-12">
                                <fieldset class="form-group">
                                    <div class="input-group">

                                        <select class="form-control client_id" id="client" name="client">
                                            <option value="">Client name</option>
                                            @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->client_name}}</option>
                                            @endforeach

                                        </select>
                                        <span class="client_err valid_err"></span>

                                    </div>
                                </fieldset>
                            </div>

                        </div>

                        <div class="row  pt-50">
                            <div class="col-12 col-md-4">
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Method</span>
                                        </div>
                                        <select class="form-control method" id="method" name="method">
                                            <option value="">Choose...</option>
                                            <option value="call">call</option>
                                            <option value="email">email</option>
                                            <option value="whatsapp">whatsapp</option>
                                            <option value="visit">visit</option>
                                        </select>
                                    </div>
                                    <span class="method_err valid_err"></span>
                                </fieldset>
                            </div>
                            <div class="col-md-4 col-12">
                                <fieldset class="form-group">
                                    <select class="form-control contact_by" id="contact_by" name="contact_by">
                                        <option value="">Contact By</option>
                                        @foreach($staff as $stf)
                                        @if(session('role_id')!=1 && (session('user_id')==$stf->user_id))
                                        <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                        @endif
                                        @endforeach


                                        @if(session('role_id')==1)
                                        <option value="">--select Contact by--</option>
                                        @foreach($staff as $stf)
                                        <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                    <span class="contact_by_err valid_err"></span>
                                </fieldset>
                            </div>
                            <div class="col-12 col-md-4">
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Company</span>
                                        </div>
                                        <select class="form-control company" id="company" name="company">
                                            <option value="">Choose...</option>
                                            @foreach($company as $com)
                                            @if($com->id==session('company_id'))
                                            <option value="{{$com->id}}" selected>{{$com->company_name}}</option>
                                            @else
                                            <option value="{{$com->id}}">{{$com->company_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="company_err valid_err"></span>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row  pt-50">
                            <div class="col-md-12 col-12 contact_div">

                            </div>
                            <div class="col-md-12 col-12">
                                <span class="valid_err contact_to_err"></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row  pt-50">
                            <div class="col-md-3 col-12">
                                <fieldset class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="next_follow_up_date" id="next_radio" checked="">
                                        <label class="custom-control-label" for="next_radio">Next Follow-up date</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-3 col-12 next_date_div">

                                <fieldset class="form-group">
                                    <input type="text" class="form-control pickadate mr-2 next_followup_date" placeholder="Next Follow-up Date">
                                    <span class="valid_err next_date_err"></span>
                                </fieldset>
                            </div>

                            <div class="col-md-6 col-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset class="form-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="finalized" id="finalized_radio">
                                                <label class="custom-control-label" for="finalized_radio">Finalized</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset class="form-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="lead_closed" id="leadclosed_radio">
                                                <label class="custom-control-label" for="leadclosed_radio">Lead
                                                    closed</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="row  pt-50">
                            <div class="col-md-12 col-12">
                                <fieldset class="form-label-group">
                                    <textarea class="form-control discussion" id="discussion" rows="3" placeholder="Discussion"></textarea>
                                    <label for="discussion">Discussion</label>
                                    <span class="valid_err discussion_err"></span>
                                </fieldset>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto mr-auto">
                                <a href="appointment-list" class="btn btn-icon btn-warning mr-1 mb-1 px-5">Go Back</a>
                            </div>
                            <div class="col-auto">
                                <button type="button" name="submit" class="btn btn-primary mr-3 submit px-5" id="submit">Submit</button>
                                <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                            </div>
                        </div>
                    </form>
                    <br><br>
                </div>
            </div>
        </div>

        <!-- invoice action  -->

    </div>
    </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

@endsection
{{-- page scripts --}}

@section('page-scripts')
<script src="{{asset('js/scripts/pages/followup.js')}}"></script>
<script src="{{asset('js/scripts/wickedpicker/dist/wickedpicker.min.js')}}"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.timepicker').wickedpicker();
        console.log("inside ajax")
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('change', '#client', function() {

            var id = $(this).val();

            $.ajax({
                type: 'post',
                url: 'get_contacts_followup',

                data: {
                    id: id
                },

                success: function(data) {
                    console.log(data);
                    $('.contact_div').empty().html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        $.ajax({
            type: 'get',
            url: 'autocomplete_followup_disc',


            success: function(data) {

                $(".discussion").autocomplete({

                    source: data
                });
            }
        });
        $(document).on('click', '.radio_btn', function() {
            if ($('.radio_btn').is(':checked')) {
                if ($(this).val() == 'next_follow_up_date') {
                    $('.next_date_div').css('display', 'block');
                } else {
                    $('.next_date_div').css('display', 'none');
                }
            }
        });
        $(document).on('click', '#submit', function() {

            var client_id = $('.client_id').val();

            var followup_date = $('.followup_date').val();
            var next_followup_date = $('.next_followup_date').val();
            var contact_by = $('.contact_by').val();

            var company = $('.company').val();
            var discussion = $('.discussion').val();
            var next_radio = $('#next_radio').val();
            var arr = [];
            if ($('#finalized_radio').prop("checked") == true) {
                var finalized = 'yes';
            } else {
                var finalized = 'no';
            }
            if ($('#leadclosed_radio').prop("checked") == true) {
                var lead_closed = 'yes';
            } else {
                var lead_closed = 'no';
            }
            if ($('#next_radio').prop("checked") == true) {
                if (next_radio == 'next_follow_up_date') {
                    if (next_followup_date == '') {
                        arr.push('next_date_err');
                        arr.push('Next Follow-up date required');
                    } else {
                        var next_followup_date = next_followup_date;
                    }
                }
            } else {
                var next_followup_date = "";
            }

            var method = new Array();

            method.push($('#method').val());

            var contact_to = new Array();
            var contact_to = [];
            $.each($(".contact_to:checked"), function() {
                contact_to.push($(this).val());
            });


            if (followup_date == '') {
                arr.push('followup_date_err');
                arr.push('Follow-up date required');
            }
            if (company == '') {
                arr.push('company_err');
                arr.push('company required');
            }
            if (client_id == '') {
                arr.push('client_err');
                arr.push('Client required');
            }
            if (contact_by == '') {
                arr.push('contact_by_err');
                arr.push('Please select contact by');
            }
            if (contact_to == '') {
                arr.push('contact_to_err');
                arr.push('Please check any contact person name');
            }
            if (method == '') {
                arr.push('method_err');
                arr.push('Please select ant method');
            }
            if (discussion == '') {
                arr.push('discussion_err');
                arr.push('Discussion required');
            }
            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;


                    $('.' + arr[i]).html(arr[j]).css('color', 'red');



                    i = j;
                }
            } else {
                $.ajax({
                    type: 'post',
                    url: 'save_follow_up',
                    data: {
                        client_id: client_id,
                        followup_date: followup_date,
                        contact_by: contact_by,
                        next_followup_date: next_followup_date,
                        contact_to: contact_to,
                        method: method,
                        finalized: finalized,
                        lead_closed: lead_closed,
                        discussion: discussion,
                        company: company
                    },
                    success: function(data) {
                        console.log(data);
                        var res = data;
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        if (res.status == 'success') {

                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $("#form").trigger('reset');
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>');

                        }


                    },
                    error: function(data) {
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something wend wrong!</span></div></div>'
                        );
                    }
                });
            }
        });
    });
</script>
@endsection