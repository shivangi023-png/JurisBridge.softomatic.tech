@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- title --}}
@section('title','Dashboard Analytics')
{{-- venodr style --}}
@section('vendor-styles')
@include('links.datatable_links')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">

@endsection

{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/widgets.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-analytics.css')}}">
@endsection
<style>
    .txt:hover {
        text-decoration: underline;
        color: #5a8dee !important;
    }

    .txt {
        color: #394c62 !important;
    }

    .dashboard_mt {
        margin-top: 10px !important;
    }

    .search_mt {
        margin-top: -20px;
    }
</style>
@section('content')
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
    <div class="row dashboard_mt">
        <div class="col-12">
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
            <div id="alert">

            </div>
            <div class="row">
                @if (session('role_id') == 1 || session('role_id') == 5 || session('role_id') == 10)
                <div class="col-xl-6 col-md-6 col-12 activity-card">
                    <div class="card">
                        <div class="dashboard-card-header card-header">
                            <h6 class="">Activity</h6>
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
                                        New Clients
                                        <h6 class="float-right"><a class="txt" href="export_client_report">{{$data['New Clients']}}</a>
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-secondary m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-user-voice text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        Clients Contacted
                                        <h6 class="float-right"><a class="txt" href="export_followup_report">{{$data['Clients Contacted']}}</a></h6>
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
                                        Appointments
                                        <h6 class="float-right"><a class="txt" href="export_appointment_report">{{$data['Appointments']}}</a></h6>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-primary m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-money text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        Consultation Fees
                                        <h6 class="float-right"><a class="txt" href="export_consultation_fees_report">
                                                <?php echo number_format($data['Consultation Fees'], 2); ?></a></h6>
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
                                        Invoice
                                        <h6 class="float-right"><a class="txt" href="export_invoice_report"><?php echo number_format($data['Invoice'], 2); ?></a>
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-success m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-file text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        Additional Invoice
                                        <h6 class="float-right"><a class="txt" href="export_additional_invoice_report">
                                                <?php echo number_format($data['Additional Invoice']); ?></a></h6>
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
                                        Payments
                                        <h6 class="float-right"><a class="txt" href="export_payment_report"><?php echo number_format($data['Payments']); ?></a>
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">
                                    <div class="avatar bg-rgba-danger m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-credit-card text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        Due Payments
                                        <h6 class="float-right"><a class="txt" href="export_due_payment_report"><?php echo number_format($data['Due Payments'], 2); ?></a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex activity-content col-xl-6 col-md-6 col-12">

                                    <div class="avatar bg-rgba-warning m-0 mr-75">
                                        <div class="avatar-content">
                                            <i class="bx bx-note text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        Quotations
                                        <h6 class="float-right"><a class="txt" href="export_quotation_report">{{$data['Quotations']}}</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-xl-6 col-md-6 col-12">
                    <div class="row">

                        {{-- <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="">Lead Timeline</h6>
                                    <div class="row">
                                        <div class="col-xs-8 col-sm-8 col-md-11 col-lg-11">
                                            <div class="form group">
                                                <input class="form-control client_query" name="client_name">
                                                <span class="client_err valid_err"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-12">
                            <div class="card search_mt">
                                <!-- <div class="card-header">
                            <h6 class="">Search Client</h6>
                            <button type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1" data-toggle="modal" data-target="#pretty_cash">
                                <i class="bx bx-money"></i>
                            </button>

                        </div> -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6 col-6">
                                            <h6 class="">Search Client</h6>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-6"> <button type="button" class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1 float-right" data-toggle="modal" data-target="#pretty_cash">
                                                <i class="bx bx-money"></i>
                                            </button></div>
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
                                                        <div class="col-7">
                                                            <img src="{{asset('images\case1.svg')}}" width=16px; height="17px;" class="bx_icon">
                                                            <i class="bx bxs-cases text-primary"></i><small class="ml-1">Cases
                                                                :
                                                            </small>
                                                        </div>
                                                        <div class="col-5">
                                                            <a href="" target="_blank" class="cases_span" style="margin-left:-1px;"></a>
                                                        </div>

                                                    </div>
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

                                                    <div class="row">
                                                        <div class="col-7">
                                                            <i class="bx bx-user text-primary"></i><small class="ml-1">Assign To:
                                                            </small>
                                                        </div>
                                                        <div class="col-5">
                                                            <small class="assign_name_span"></small>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <i class="bx bx-calendar text-primary"></i><small class="ml-1">Assigned Dt:
                                                            </small>
                                                        </div>
                                                        <div class="col-5">
                                                            <small class="assigned_date_span"></small>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <i class="bx bx-buildings text-primary"></i><small class="ml-1">Address
                                                                : </small>
                                                        </div>
                                                        <div class="col-5">
                                                            <small class="address_span"></small>
                                                        </div>
                                                    </div>

                                                </div>



                                                <div class="col-6">
                                                    <div class="row nd">
                                                        <div class="col-6">
                                                            <div class="rectangle">
                                                                <center><a class="cases_view_details" href="#">
                                                                        <strong>Cases View Details</strong></a>
                                                                </center>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="rectangle">
                                                                <center><a class="leads_timeline" href="#">
                                                                        <strong>Lead Timeline</strong></a>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                                                <center><a class="quotation_add" href="#"> <strong>Quotation</strong></a></center>

                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="rectangle">
                                                                <center><a class="invoice_add" href="#">
                                                                        <strong>Invoice</strong></a></center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row nd">
                                                        <div class="col-6">
                                                            <div class="rectangle">
                                                                <center><a class="expenses_add" href="#">
                                                                        <strong>Expense</strong></a></center>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="rectangle">
                                                                <center><a href="#" data-toggle="modal" data-target="#default" class="invoice_payment_btn">
                                                                        <strong>Payment</strong></a></center>
                                                            </div>
                                                        </div>
                                                    </div>
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
                        <div class="col-6">

                        </div>
                    </div>
                </div>
                <!-- Activity Card Starts-->

            </div>
        </div>
    </div>
    @if (session('role_id') == 1 || session('role_id') == 3 || session('role_id') == 10)

    <div class="row">
        <div class="col-6 attendance_table">

        </div>

        <div class="col-6 followup_table">

        </div>
        <div class="col-6 assign_due_table">

        </div>
        <div class="col-6 appointment_table">

        </div>

        <div class="col-6 leave_table">

        </div>

        <div class="col-6 client_table">

        </div>

        <div class="col-6 quotation_sent_table">

        </div>

        <div class="col-6 raised_invoice_table">

        </div>

        <div class="col-6 received_payment_table">

        </div>

        <div class="col-6 leads_by_sales_table">

        </div>

        <div class="col-6 leads_by_office_table">

        </div>
        <div class="col-md-6 col-lg-6 raise_attendance">
            <!-- leads list -->
        </div>
        <div class="col-6 office_visit">

        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header  d-flex justify-content-between align-items-center">
                    <h6 class="d-flex">
                        Income : <span class="total_inc"></span>
                    </h6>
                    <h6 class="d-flex">
                        Expenses : <span class="total_exp"></span>
                    </h6>
                    <ul class="list-inline d-flex mb-0">
                        <li class="d-flex align-items-center mr-1">
                            <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                            <div class="dropdown">
                                <div class="dropdown-toggle bar_session" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if(date('m')>3)
                                    <span class="bar_select">{{date('Y')}}-{{date('Y')+1}}</span>
                                    @else
                                    <span class="bar_select">{{date('Y')-1}}-{{date('Y')}}</span>
                                    @endif
                                </div>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    @for($i=2020;$i<=date('Y');$i++) <a class="dropdown-item" href="javascript:;" id="{{$i}}-{{$i+1}}" data-display="{{$i}}-{{$i+1}}" onclick=barchart(this.id)>{{$i}}-{{$i+1}}</a>
                                        @endfor
                                </div>
                            </div>
                        </li>


                    </ul>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="analytics-bar-chart" class="my-75"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="card">
                <div class="card-header  d-flex justify-content-between align-items-center">
                    <h6 class="d-flex">
                        Quotation Finalize : <span class="quo_fin"></span>
                    </h6>

                    <ul class="list-inline d-flex mb-0">
                        <li class="d-flex align-items-center mr-1">
                            <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                            <div class="dropdown">
                                <div class="dropdown-toggle line_session" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if(date('m')>3)
                                    <span class="line_select">{{date('Y')}}-{{date('Y')+1}}</span>
                                    @else
                                    <span class="line_select">{{date('Y')-1}}-{{date('Y')}}</span>
                                    @endif
                                </div>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    @for($i=2020;$i<=date('Y');$i++) <a class="dropdown-item" href="javascript:;" id="{{$i}}-{{$i+1}}" data-display="{{$i}}-{{$i+1}}" onclick=linechart(this.id)>{{$i}}-{{$i+1}}</a>
                                        @endfor
                                </div>
                            </div>
                        </li>


                    </ul>
                </div>
                <div class="card-body pl-0">
                    <div class="height-300">
                        <canvas id="line-chart"></canvas>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6" id="daily_report_table">

           
               
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Assigned Leads</h4>
                    <select name="staff_id" id="staff_pie" class="form-control select_control" onchange=piechart(this.value)>
                        <option value="">Select Staff</option>
                        @foreach($staff as $stf)
                        @if(session('staff_id')==$stf->sid)
                        <option value="{{$stf->sid}}" selected>{{$stf->name}}</option>
                        @else
                        <option value="{{$stf->sid}}">{{$stf->name}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="card-body pl-0 pb-3">
                    <div class="height-335" id="pieChartContent">
                        <canvas id="simple-pie-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header  d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Lead Source</h4>
                    <?php
                    if (date('m') > 3) {
                        $year = date('Y') . '-' . (date('Y') + 1);
                    } else {
                        $year = (date('Y') - 1) . '-' . date('Y');
                    }

                    ?>


                    <select name="sourceyear" id="sourceyear" class="form-control select_control" onchange=sourcepiechart(this.value)>
                        @for($i=2020;$i<=date('Y');$i++) <option value="{{$i}}-{{$i+1}}" <?php if (($i . '-' . ($i + 1)) == $year) echo "selected"; ?>>{{$i}}-{{$i+1}}</option>
                            @endfor
                    </select>
                </div>
                <div class="card-body pl-0 pb-3">
                    <div class="height-335" id="sourcepieChartContent">
                        <canvas id="source-pie-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leads</h4>
                </div>
                <div class="card-body pl-0 pb-3">
                    <div id="radial-bar-chart"></div>
                </div>
            </div>
        </div>


    </div>
    @endif

    @if (session('role_id') == 3||session('role_id') == 10)
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Leaves</h4>
                </div>
                <div class="card-body pt-1">

                    <div class="table-responsive leave_div">
                        <table class="table zero-configuration">

                            <thead>

                                <th>Staff</th>
                                <th>Leave Type</th>
                                <th>From </th>
                                <th>To</th>
                                <th>Reason</th>
                                <th>status</th>
                                <th>Action</th>
                            </thead>

                            <tbody>
                                @foreach ($leaves as $leave)
                                <tr>

                                    <td>{{$leave->name}}</td>
                                    <td>{{$leave->type}}</td>
                                    <td>{{date('d-m-Y',strtotime($leave->start_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($leave->end_date))}}</td>
                                    <td>{{$leave->reason}}</td>
                                    <td>
                                        @if($leave->status=='Pending')
                                        <span class="badge badge-light-warning badge-pill">{{$leave->status}}</span>
                                        @elseif($leave->status=='Approved')
                                        <span class="badge badge-light-success badge-pill">{{$leave->status}}</span>
                                        @else
                                        <span class="badge badge-light-danger badge-pill">{{$leave->status}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($leave->status=='Pending')
                                        <div class="action">
                                            <button type="button" value="Approved" data-id="{{$leave->id}}" class="btn btn-icon rounded-circle btn-light-success app_rej_btn mr-1 mb-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Approve">
                                                <i class="bx bx-check"></i></button>
                                            <button type="button" value="Rejected" data-id="{{$leave->id}}" class="btn btn-icon rounded-circle btn-light-danger app_rej_btn mr-1 mb-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reject">
                                                <i class="bx bx-x"></i></button>
                                        </div>
                                        @else
                                        <div class="action">
                                            <button type="button" class="btn btn-icon rounded-circle btn-light-success  mr-1 mb-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Approve" disabled>
                                                <i class="bx bx-check"></i></button>
                                            <button type="button" class="btn btn-icon rounded-circle btn-light-danger  mr-1 mb-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reject" disabled>
                                                <i class="bx bx-x"></i></button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="modal fade text-left show" id="pretty_cash" tabindex="-1" aria-labelledby="myModalLabel18" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel18">Pretty Cash</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="cash" id="radio1" value="expense">
                                            <label for="radio1">Expense</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="cash" id="radio2" value="receipt">
                                            <label for="radio2">Receipt</label>
                                        </div>
                                    </fieldset>
                                </li>

                            </ul>
                            <span class="cash_err text-danger valid_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-label-group">
                                <input type="text" class="form-control  mr-2 mb-50 mb-sm-0 date pretty_date">
                                <span class="pretty_date_err text-danger valid_err"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select name="staff_id" class="form-control" id="pretty_staff" onchange=piechart(this.value)>
                                <option value="">Select Staff</option>
                                @foreach($staff1 as $stf1)
                                <option value="{{$stf1->sid}}">{{$stf1->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger pretty_staff_err valid_err"></span>
                        </div>

                    </div><br>
                    <div class="row">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control" id="pretty_amount" placeholder="Amount">
                                <label for="pretty_amount">Amount</label>
                                <span class="text-danger pretty_amount_err valid_err"></span>
                            </fieldset>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="form-label-group">
                                <textarea class="form-control" id="pretty_remark" rows="3" placeholder="Remark"></textarea>
                                <label for="pretty_remark">Remark</label>
                                <span class="text-danger pretty_remark_err valid_err"></span>
                            </fieldset>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" id="pretty_submit">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Submit</span>
                    </button>
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
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>

<script src="{{asset('vendors/js/charts/chart.min.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/pages/dashboard-analytics.js')}}"></script>
<script src="{{asset('js/scripts/pages/dashboard-chart.js')}}"></script>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{asset('js/scripts/pages/get_leads.js')}}"></script>
<!-- firebase  -->
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script src="{{asset('js/scripts/firebase.js')}}"></script>
<!-- firebase  -->
<script>
    $(document).ready(function() {
        if ($(".pretty_date").length) {
            var tdate = new Date();
            var dd = tdate.getDate(); //yields day
            var MM = tdate.getMonth(); //yields month
            var yyyy = tdate.getFullYear(); //yields year
            var currentDate = yyyy + "," + (MM + 1) + "," + dd;
            $(".pretty_date").pickadate({
                format: "dd/mm/yyyy",
                max: [currentDate],
                format: 'dd-mm-yyyy',
                onStart: function() {
                    this.set({
                        select: new Date()
                    });
                }
            });
        }

        function pretty_valid(staff, amount, remark) {
            $('.valid_err').html('');
            var amount_exp = /^\d{0,9}(\.\d{0,9})?$/;
            var valid = true;
            if (staff == '') {
                $('.pretty_staff_err').html('Please select staff');
                valid = false;
            }

            if (amount_exp.test(amount) == false || amount == '') {
                $('.pretty_amount_err').html('Please enter valid amount');
                valid = false;
            }
            if (remark == '') {
                $('.pretty_remark_err').html('Please enter remark');
                valid = false;

            }
            if (!$('input[name="cash"]:checked').val()) {
                $('.cash_err').html('Please check any option');
                valid = false;
            }
            return valid;
        }
        $(document).on('click', '#pretty_submit', function() {

            var staff = $('#pretty_staff').val();
            var amount = $('#pretty_amount').val();
            var remark = $('#pretty_remark').val();
            var date = $('.pretty_date').val();
            var cash = $('input[name="cash"]:checked').val();

            var valid = pretty_valid(staff, amount, remark);
            console.log(valid);
            if (valid) {
                $.ajax({
                    type: 'post',
                    url: 'add_pretty_cash',

                    data: {
                        date: date,
                        staff: staff,
                        amount: amount,
                        remark: remark,
                        cash: cash,
                    },
                    success: function(data) {
                        console.log(data);
                        var res = JSON.parse(data);
                        $('#pretty_cash').modal('toggle');
                        if (res.status == 'success') {
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>');

                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        });
    });
</script>
@endsection