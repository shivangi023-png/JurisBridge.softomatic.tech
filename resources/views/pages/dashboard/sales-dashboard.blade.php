@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- title --}}
@section('title','Sales Dashboard')
{{-- venodr style --}}
@section('vendor-styles')
@include('links.datatable_links')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/dragula.min.css')}}">
@endsection

{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/widgets.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-analytics.css')}}">
@endsection
@section('content')
<!-- Dashboard Analytics Start -->
<section id="sales-dashboard">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-12 activity-card">
                    <div class="card">
                        <div class="card-header">
                            <h6>Activity</h6>
                        </div>
                        <div class="card-body pt-1">
                            <div class="row">
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-success m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-user-plus text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Lead Created</span>
                                        <span class="float-right"><a class="text-dark" href="export_client_report">{{$data['lead_created']}}</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-secondary m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-user-voice text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Lead Assigned</span>
                                        <span class="float-right"><a class="text-dark" href="export_followup_report">{{$data['lead_assigned']}}</a></span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-secondary m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-group text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Follow-up (Calls)</span>
                                        <span class="float-right"><a class="text-dark" href="export_appointment_report">{{$data['follow_up_call']}}</a></span>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-primary m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-money text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Follow-up (Whatsapp)</span>
                                        <span class="float-right"><a class="text-dark" href="export_consultation_fees_report">
                                                {{$data['follow_up_whatsapp']}}</a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-info m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-file text-info"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Follow-up (Visit)</span>
                                        <span class="float-right"><a class="text-dark" href="export_invoice_report">{{$data['follow_up_visit']}}</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-success m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-file text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Follow-up (Pending)</span>
                                        <span class="float-right"><a class="text-dark" href="export_additional_invoice_report">{{$data['follow_up_pending']}}
                                            </a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-primary m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-credit-card text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Quotation Sent</span>
                                        <span class="float-right"><a class="text-dark" href="export_payment_report">{{$data['quotations_send']}}</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-danger m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-credit-card text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Quotation Finalized</span>
                                        <span class="float-right"><a class="text-dark" href="export_due_payment_report">{{$data['quotations_finalized']}}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-primary m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-credit-card text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Appointment</span>
                                        <span class="float-right"><a class="text-dark" href="export_payment_report">{{$data['appointment']}}</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-danger m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-credit-card text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span>Leave</span>
                                        <span class="float-right"><a class="text-dark" href="export_due_payment_report">{{$data['leave']}}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Search Client</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-md-6 col-12 activity-card">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="radio">
                                                    <input type="radio" class="search_case_no search_client" id="case_no" name="bsradio" value="case_no" checked>
                                                    <label for="case_no">Case No</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="radio">
                                                    <input type="radio" class="search_client_name search_client" id="client_name" name="bsradio" value="client_name">
                                                    <label for="client_name">Client Name</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="radio">
                                                    <input type="radio" class="search_client_email search_client" id="client_email" name="bsradio" value="email">
                                                    <label for="client_email">Email</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="radio">
                                                    <input type="radio" class="search_client_contact search_client" id="client_contact" name="bsradio" value="contact">
                                                    <label for="client_contact">Mobile Number</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-xs-8 col-sm-8 col-md-11 col-lg-11 ">
                                    <div class="form group">
                                        <input class="form-control client_info" name="client_name">

                                        <span class="client_info_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-1 col-lg-1 client_info_loading" style="display:none">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>

                                <div class="col-xs-8 col-sm-8 col-md-11 col-lg-11 activity-card client_detail_div" style="display:none;">
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <strong class="text-left">
                                                <h6 class="client_name_span"></h6>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <span class="lead_type_span"></span>
                                                        <select class="form-control input_control" id="select_lead" style="display:none;">
                                                            <option value="">Type</option>
                                                            @foreach ($leadtype as $lt)
                                                            <option value={{$lt->id}}>
                                                                {{$lt->type}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <small id="type_err" style="display:none;"></small>
                                                    </div>
                                                    <div class="col-1">
                                                        <i class="bx bx-check-square text-primary" id="save_lead" style="display:none;"></i>
                                                    </div>
                                                    <div class="col-1">
                                                        <i class="bx bx-window-close text-danger" id="cancel_lead" style="display:none;"></i>
                                                    </div>
                                                </div>
                                            </strong>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <strong class="text-right">
                                                <a id="case_no_href" href="" target="_blank">
                                                    <h6 class="case_no_span text-primary"></h6>
                                                </a>
                                            </strong>
                                        </div>
                                        <div class="col-12 my-0">
                                            <hr class="search_hr">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <input type="hidden" class="clientID" id="clientID">
                                                <div class="col-7">
                                                    <i class="bx bx-home text-primary"></i><small class="ml-1">Property
                                                        Type: </small>
                                                </div>
                                                <div class="col-5">
                                                    <small class="property_type_span"></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7">
                                                    <i class="bx bx-sitemap text-primary"></i><small class="ml-1">No
                                                        of
                                                        Units :
                                                    </small>
                                                </div>
                                                <div class="col-5">
                                                    <small class="unit_span"></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7">
                                                    <i class="bx bxs-city text-primary"></i><small class="ml-1">City
                                                        :
                                                    </small>
                                                </div>
                                                <div class="col-5">
                                                    <small class="city_span"></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7">
                                                    <i class="bx bx-map text-primary"></i><small class="ml-1">Pincode
                                                        :
                                                    </small>
                                                </div>
                                                <div class="col-5">
                                                    <small class="pincode_span"></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7">
                                                    <i class="bx bx-id-card text-primary"></i><small class="ml-1">Source :
                                                    </small>
                                                </div>
                                                <div class="col-5">
                                                    <small class="source_span"></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7">
                                                    <i class="bx bxs-file text-primary"></i><small class="ml-1">Quotations:
                                                    </small>
                                                </div>
                                                <div class="col-5">
                                                    <a href="javascript:void(0);" class="quotations_span detailBtn" data-toggle="modal" data-target="#detailModal" data-detail="Quotation" style="margin-left:-1px;"></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7">
                                                    <i class="bx bx-phone-call text-primary"></i><small class="ml-1">Appointments:
                                                    </small>
                                                </div>
                                                <div class="col-5">
                                                    <a href="javascript:void(0);" class="appointments_span detailBtn" data-toggle="modal" data-target="#detailModal" data-detail="Appointment" style="margin-left:-1px;"></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7">
                                                    <i class="bx bxs-contact text-primary"></i><small class="ml-1">Follow Ups:
                                                    </small>
                                                </div>
                                                <div class="col-5">
                                                    <a href="javascript:void(0);" class="followups_span detailBtn" data-toggle="modal" data-target="#detailModal" data-detail="Follow-Up" style="margin-left:-1px;"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row nd">
                                                <div class="col-6">
                                                    <div class="rectangle">
                                                        <center><a class="appointment_add" href="#">
                                                                <strong>Appointment</strong></a>
                                                        </center>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="rectangle">
                                                        <center><a href="#" class="follow_up_add">
                                                                <strong>Follow
                                                                    Up</strong></a></center>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row nd">
                                                <div class="col-6">
                                                    <div class="rectangle">
                                                        <center><a href="#" class="quotation_add
                                                            "> <strong>Quotation</strong></a></center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <i class="bx bx-user text-primary"></i><small class="ml-1">Assign To:
                                            </small>
                                        </div>
                                        <div class="col-9 padd">
                                            <small class="assign_name_span"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <i class="bx bx-calendar text-primary"></i><small class="ml-1">Assigned Dt:
                                            </small>
                                        </div>
                                        <div class="col-9 padd">
                                            <small class="assigned_date_span"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <i class="bx bx-buildings text-primary"></i><small class="ml-1">Address
                                                : </small>
                                        </div>
                                        <div class="col-9 padd">
                                            <small class="address_span"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 client_contacts">

                                        </div>
                                    </div>

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

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Activity Card Starts-->
        </div>
    </div>
    <div class="row">
        <div class="col-6 client_table">

        </div>
        <div class="col-6 appointment_table">

        </div>
        <div class="col-6 followup_table">

        </div>
        <div class="col-6 next_followup_table">

        </div>
        <div class="col-6 quotation_sent_table">

        </div>
        <div class="col-6 leave_table">

        </div>
          <div class="col-6 assign_due_table">

</div>
         <!-- Pie Chart -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Assigned Leads</h4>
        </div>
        <div class="card-body pl-0">
          <div class="height-300">
            <canvas id="simple-pie-chart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

    </div>
</section>
<!-- Dashboard Analytics end -->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
<script src="{{asset('vendors/js/charts/chart.min.js')}}"></script>

@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/pages/dashboard-analytics.js')}}"></script>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{asset('js/scripts/pages/get_leads.js')}}"></script>
<script src="{{asset('js/scripts/pages/sales-dashboard.js')}}"></script>
@endsection