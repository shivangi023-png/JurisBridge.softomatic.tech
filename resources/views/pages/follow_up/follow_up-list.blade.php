@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Follow up List')
{{-- vendor style --}}
@section('vendor-styles')
@include('links.datatable_links')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">

@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<style>
    .ui-autocomplete {
        z-index: 1061 !important;
    }
</style>
@endsection

@section('content')
<!-- invoice list -->

<section class="client-list-wrapper">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class='col-10'>
                    @include('layouts.tabs')
                </div>
                <div class='col-2'>
                    <a href="follow-up-add" class="btn btn-icon btn-outline-primary float-right" role="button" aria-pressed="true">
                        <strong><i class="bx bx-plus"></i>New Follow-up</strong></a>
                </div>

                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker from_date" placeholder="From Date">
                    </div>
                    <span class="text-danger from_date_err"></span>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker to_date" placeholder="To Date">
                    </div>
                    <span class="text-danger to_date_err"></span>
                </div>

                @if(session('role_id') == 1)
                <div class="col-md-2">
                    <div class="dropdown">
                        <select class="form-control staff dropdown-toggle" id="search_by_staff">
                            <option value="">Staff</option>
                            @foreach ($staff as $stf)
                            <option value='{{$stf->sid}}'>{{$stf->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="col-md-2">
                    <div class="dropdown" style="padding-right:15px">
                        <select class="form-control input_control dropdown-toggle" id="search_by_method">
                            <option value="">Method</option>
                            <option value="call">Call</option>
                            <option value="email">Email</option>
                            <option value="whatsapp">Whatsapp</option>
                            <option value="visit">Visit</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li class="d-inline-block mr-5">
                            <fieldset class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="type_radio" value="finalized" id="finalized_type">
                                    <label class="custom-control-label" for="finalized_type">Finalized</label>
                                </div>
                            </fieldset>
                        </li>
                        <li class="d-inline-block">
                            <fieldset class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="type_radio" value="lead_closed" id="leadclosed_type">
                                    <label class="custom-control-label" for="leadclosed_type">Not Interested</label>
                                </div>
                            </fieldset>
                        </li>
                    </ul>
                </div>
                @if(session('role_id') == 1)
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li class="d-inline-block mr-5">
                            <fieldset class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="followup_radio" value="follow_up" id="followup_type">
                                    <label class="custom-control-label" for="followup_type">Follow-up</label>
                                </div>
                            </fieldset>
                        </li>
                        <li class="d-inline-block mr-5">
                            <fieldset class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="followup_radio" value="next_follow_up" id="next_followup_type">
                                    <label class="custom-control-label" for="next_followup_type">Next Follow-up
                                    </label>
                                </div>
                            </fieldset>
                        </li>
                    </ul>
                </div>
                @endif
                <div class="col-md-1">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3" id="search"><strong>Search</strong></a>
                </div>
                <div class="col-md-1 ml-3">
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div>

            </div>
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
            <center>
                <div class="spinner-grow text-primary loader" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="loader" style="display:none">Please wait...</h5>
            </center>
            <div class="data_div">

            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="FollowUpHistory" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-pink">
                <h5 class="modal-title" id="uModalLabel">All Call Details </h5>
            </div>
            <div class="modal-body call_detail_body">


            </div>
        </div>
    </div>
</div>

<!--Extra Large Modal -->
<div class="modal fade text-left w-100" id="saveFollowup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Client Follow-up <span class="font-small-3" id="client_name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>


            </div>
            <div class="modal-body">
                <form method="POST" action='' id="form">
                    {{ csrf_field() }}
                    <div class="row pt-50">
                        <input type="hidden" class="form-control  mr-2 client_id">
                        <div class="col-12 col-md-6">
                            <fieldset class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control pickadate mr-2 followup_date" placeholder="Select Date">
                                </div>
                                <span class="valid_err followup_date_err"></span>
                            </fieldset>
                        </div>
                        <div class="col-12 col-md-6">
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
                    </div>

                    <div class="row  pt-50">

                        <div class="col-md-6 col-12">
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
                        <div class="col-12 col-md-6">
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
                        <div class="col-md-4 col-12">
                            <fieldset class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input radio_btn" name="customRadio" value="next_follow_up_date" id="next_radio" checked="">
                                    <label class="custom-control-label" for="customRadio3">Next Follow-up date</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-3 col-12 next_date_div">

                            <fieldset class="form-group">
                                <input type="text" class="form-control pickadate mr-2 next_followup_date" placeholder="Next Follow-up Date">
                                <span class="valid_err next_date_err"></span>
                            </fieldset>
                        </div>

                        <div class="col-md-5 col-12">
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
                                            <label class="custom-control-label" for="leadclosed_radio">Not Interested</label>
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning ml-1 save_follow_up_btn" id="save_follow_up_btn">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save Follow-up</span>
                </button>
                <button type="button" class="btn btn-light-danger" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>

            </div>
        </div>
        </form>
    </div>
</div>
</div>
<div class="modal" id="whatsapp_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Contact via WhatsApp</h5>
      </div>
      <div class="modal-body contact-list whatsapp_body">
        </div>
          <div class="modal-footer">
         <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                  </button>
      </div>
    </div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/followup.js')}}"></script>
<script>
    $(document).ready(function() {
        $(document).on("click", "#search", function() {
            $(".data_div").empty();
            var from_date = $(".from_date").val();
            var to_date = $(".to_date").val();
            var staff = $('#search_by_staff').val();
            var method = $('#search_by_method').val();
            // var method = new Array();
            // method.push($('#search_by_method').val());
            // if (method == '') {
            //     method = '';
            // }

            if ($('#finalized_type').prop("checked") == true) {
                var type = $('#finalized_type').val();
            }

            if ($('#leadclosed_type').prop("checked") == true) {
                var type = $('#leadclosed_type').val();
            }

            if ($('#followup_type').prop("checked") == true) {
                var follow = $('#followup_type').val();
            }

            if ($('#next_followup_type').prop("checked") == true) {
                var follow = $('#next_followup_type').val();
            }
            if (
                staff != "" ||
                method != "" ||
                follow != undefined ||
                type != undefined ||
                (from_date != "" && to_date != "")
            ) {
                $(".loader").css("display", "block");
                $.ajax({
                    type: "post",
                    url: "search_follow_up",
                    datatype: "text/html",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        staff: staff,
                        method: method,
                        follow: follow,
                        type: type,
                       
                    },

                    success: function(data) {
                        console.log(data);
                        $(".loader").css("display", "none");
                        $(".data_div").empty().html(data);
                    },
                    error: function(data) {
                        console.log(data);
                        $(".loader").css("display", "none");
                        $("#alert")
                            .html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg +
                                "</span></div></div>"
                            )
                            .focus();
                    },
                });

            } else {
                if (from_date == "" && to_date != "") {
                    $(".from_date_err").text("Select From Date");
                    return false;
                }
                if (from_date != "" && to_date == "") {
                    $(".to_date_err").text("Select To Date");
                    return false;
                }
                if (
                    from_date == "" &&
                    to_date == "" &&
                    staff == "" &&
                    method == "" &&
                    follow == undefined &&
                    type == undefined
                ) {
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        },
                        "slow"
                    );
                    $("#alert")
                        .html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please Select filter</span></div></div>'
                        )
                        .focus();
                    $(".alert")
                        .fadeTo(2000, 500)
                        .slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    return false;
                }
            }
        });
        $('#reset').click(function() {
            location.reload();
        });
    });
</script>
@endsection