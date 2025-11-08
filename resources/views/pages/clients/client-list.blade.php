@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Client Info')
{{-- vendor style --}}
@section('vendor-styles')
@include('links.datatable_links')
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<style>
    .staff-dropdown {
        height: 250px !important;
        overflow-y: auto !important;
    }
    .card-body{
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<!-- invoice list -->

<section class="client-list-wrapper">

    <div id="alert"></div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    @include('layouts.tabs')
                </div>
                <div class="col-2">
                    <a href="client_add" class="btn btn-icon btn-outline-primary px-3 float-right" role="button" aria-pressed="true">
                        <strong><i class="bx bx-plus"></i>Add Lead</strong></a>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <select class="form-control client" id="client" multiple="multiple">
                            <option value="">Search By Clients</option>
                            @foreach ($clients as $row1)
                            <option value={{$row1->id}}>
                                {{$row1->case_no}} ({{$row1->client_name}})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
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
<!--Basic Modal -->
<div class="modal fade text-left" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title detailModal-title" id="myModalLabel1"></h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body detailModal-body">
                <center>
                    <div class="spinner-grow text-primary loader1" role="status" style="display:none">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="loader1" style="display:none">Please wait...</h5>
                </center>
            </div>
        </div>
    </div>
</div>

<!--Basic Modal -->
<div class="modal fade text-left" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">New appointment</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!----modal body start-->
                <div class="row mx-0">
                    <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">
                    </div>
                    <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                        <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                            <div class="d-flex align-items-center">
                                <span class="mr-75 label_title">Date: </span>

                                <fieldset class="d-flex ">
                                    <input type="text" class="form-control pickadate mr-2 meeting_date" placeholder="Select Date">
                                </fieldset>
                                <span class="valid_err meeting_date_err"></span>

                            </div>

                            <div class="d-flex align-items-center">
                                <span class="mr-75 label_title">Time: </span>

                                <fieldset class="d-flex">
                                    <input type="text" class="form-control timepicker time" placeholder="Select Date">
                                </fieldset>
                                <span class="valid_err time_err"></span>

                            </div>
                        </div>
                    </div>
                </div><br>

                <div class="row pt-50">
                    <div class="col-12 col-md-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="hidden" class="form-control appointment_client_id">
                                <input type="text" class="form-control appointment_client_name">
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
                                    <span class="input-group-text">Place</span>
                                </div>
                                <select class="form-control meeting_place" id="meeting_place" name="meeting_place">
                                    <option value="">Choose...</option>
                                    @foreach($appointment_places as $ap)
                                    <option value="{{$ap->id}}">{{$ap->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="meeting_place_err valid_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-md-4 col-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Meeting with</span>
                                </div>
                                <select class="form-control meeting_with" id="meeting_with" name="meeting_with">
                                    <option value="">Choose...</option>
                                    @foreach($staff as $stf)

                                    <option value="{{$stf->sid}}">{{$stf->name}}</option>


                                    @endforeach
                                </select>
                            </div>
                            <span class="meeting_with_err valid_err"></span>
                        </fieldset>
                    </div>
                    <div class="col-md-4 col-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Schedule By</span>
                                </div>
                                <select class="form-control schedule_by" id="schedule_by" name="schedule_by">
                                    @foreach($staff as $stf)
                                    @if(session('role_id')!=1 && (session('user_id')==$stf->user_id))
                                    <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                                    @endif
                                    @endforeach
                                    @if(session('role_id')==1)
                                    <option value="">Choose...</option>
                                    @foreach($staff as $stf)
                                    <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <span class="schedule_by_err valid_err"></span>
                        </fieldset>
                    </div>
                </div>
            </div><!-- modal body end-->
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="save_appointment" data-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!---end-->

<!--Basic Modal -->
<div class="modal fade text-left" id="followupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">Follow-up</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!----modal body start-->
                <div class="row mx-0">
                    <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">


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
                </div><br>


                <div class="row pt-50">
                    <div class="col-12 col-md-12">
                        <fieldset class="form-group">
                            <div class="input-group">

                                <input type="hidden" class="form-control followup_client_id">
                                <input type="text" class="form-control followup_client_name">
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


            </div><!-- modal body end-->
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="save_followup" data-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!---end-->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/client_list.js')}}"></script>
<script src="{{asset('js/scripts/pages/get_leads.js')}}"></script>
<script src="{{asset('js/scripts/wickedpicker/dist/wickedpicker.min.js')}}"></script>
@endsection