@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- title --}}
@section('title','Dashboard Analytics')
{{-- venodr style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/dragula.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection

{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/widgets.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-analytics.css')}}">
@endsection

@section('content')
<style>
    #search_card {
        box-shadow: 0px 2px 4px rgba(152, 152, 152, 0.1) !important;
    }

    .search_hr {
        width: 100%;
        border-top: 2px solid #E3E3E3;
    }

    .nd {
        margin-top: 20px;
    }

    .client_info span {
        font-size: 13px;
        margin-left: 8px !important;
    }

    .client_info i {
        font-size: 14px;
    }

    .rectangle {
        background: #FFFFFF;
        border: 3px solid #d6e1f2;
        box-sizing: border-box;
        border-radius: 20px;
        padding: 6px 3px;
        font-size: 12px;
        text-align: center;
    }

    .rectangle:hover {
        background: #e6ebf4;
        border: 3px solid #e6ebf4;
    }

    .table thead th {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
    }

    .table th,
    .table td {
        padding: 10px 6px !important;
    }
</style>
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
    <div class="row">
        <div class="col-12">
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
                    @if (session('role_id') == 1 || session('role_id') == 5)
                    <div class="col-xl-6 col-md-6 col-12 activity-card">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Activity</h4>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_client_report">{{$data['New Clients']}}</a>
                                            </h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_followup_report">{{$data['Clients Contacted']}}</a></h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_appointment_report">{{$data['Appointments']}}</a></h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_consultation_fees_report">
                                                    <?php echo number_format($data['Consultation Fees'], 2); ?></a></h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_invoice_report"><?php echo number_format($data['Invoice'], 2); ?></a>
                                            </h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_additional_invoice_report">
                                                    <?php echo number_format($data['Additional Invoice']); ?></a></h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_payment_report"><?php echo number_format($data['Payments']); ?></a>
                                            </h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_due_payment_report"><?php echo number_format($data['Due Payments'], 2); ?></a>
                                            </h5>
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
                                            <h5 class="float-right"><a class="text-dark" href="export_quotation_report">{{$data['Quotations']}}</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Search Client</h4>
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
                                                    <h6 class="client_name_span"></span>
                                                </strong>
                                            </div>
                                            <div class="col-2"></div>
                                            <div class="col-4">
                                                <strong class="text-right">
                                                    <h6 class="case_no_span text-primary"></h6>
                                                </strong>
                                            </div>
                                            <div class="col-12 my-0">
                                                <hr class="search_hr">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <input type="hidden" class="clientID">
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
                                                            <center><a href="appointment-add">
                                                                    <strong>Appointment</strong></a>
                                                            </center>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="rectangle">
                                                            <center><a target="_blank" href="" class="follow_up_add">
                                                                    <strong>Follow
                                                                        Up</strong></a></center>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row nd">
                                                    <div class="col-6">
                                                        <div class="rectangle">
                                                            <center><a href="quotation_add" class="
                                                            "> <strong>Quotation</strong></a></center>

                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="rectangle">
                                                            <center><a href="invoice_add" class="">
                                                                    <strong>Invoice</strong></a></center>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row nd">
                                                    <div class="col-6">
                                                        <div class="rectangle">
                                                            <center><a href="expenses_add" class="">
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
                                            <div class="col-3">
                                                <i class="bx bx-user text-primary"></i><small class="ml-1">Assign To:
                                                </small>
                                            </div>
                                            <div class="col-9">
                                                <small class="assign_name_span pl-2"></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <i class="bx bx-calendar text-primary"></i><small class="ml-1">Assigned Dt:
                                                </small>
                                            </div>
                                            <div class="col-9">
                                                <small class="assigned_date_span pl-2"></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <i class="bx bx-buildings text-primary"></i><small class="ml-1">Address
                                                    : </small>
                                            </div>
                                            <div class="col-9">
                                                <small class="address_span pl-2"></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <table class="table dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th class='text-center'>Name</th>
                                                            <th class='text-center'>Contact</th>
                                                            <th class='text-center'>Email</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="client_contacts">
                                                    </tbody>
                                                </table>
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

    </div>
    @if (session('role_id') == 1 || session('role_id') == 3)
    <div class="row">
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                Appointments
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_appointment" href="javascript:;" data-value="today_appointment" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_appointment" data-display="This Week" href="javascript:;" data-value="weekly_appointment">This Week</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a href="appointment-list" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body  appointment_div">
                            <div class="table-responsive">
                                <table class="table dataTable">

                                    <thead>
                                        <th>Client Name</th>
                                        <th>Meeting with</th>
                                        <th>Meeting Date</th>
                                        <th>Time</th>
                                        <th>Visit Type</th>
                                    </thead>

                                    <tbody>
                                        @if($todays_appointment=="[]")
                                        <tr>
                                            <td colspan="5">No records found!! </td>
                                        </tr>
                                        @else
                                        @foreach($todays_appointment as $val)
                                        <tr>
                                            <td>{{ $val->case_no }}
                                                <small>({{ $val->cname }})</small>
                                            </td>
                                            <td>{{$val->meetname}}</td>
                                            <td><?php echo date("d-M-Y", strtotime($val->meeting_date)); ?></td>
                                            <td>{{$val->meeting_time}}</td>
                                            <td>{{$val->aname}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header  d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                Leave
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_leave" href="javascript:;" data-value="today_leave" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_leave" href="javascript:;" data-value="weekly_leave" data-display="This Week">This Week</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a href="staff_leave" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body  leave_div">
                            <div class="table-responsive">
                                <table class="table dataTable">

                                    <thead>
                                        <th>Staff Name</th>
                                        <th>From</th>
                                        <th>To</th>
                                    </thead>

                                    <tbody>
                                        @if($today_leave=="[]")
                                        <tr>
                                            <td colspan="3">No records found!! </td>
                                        </tr>
                                        @else
                                        @foreach ($today_leave as $leave)
                                        <tr>
                                            <td>{{$leave->name}}</td>
                                            <td>{{date('d-M-Y',strtotime($leave->start_date))}}</td>
                                            <td>{{date('d-M-Y',strtotime($leave->end_date))}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header  d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                New Clients
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_add_client" href="javascript:;" data-value="today_client" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_add_client" href="javascript:;" data-value="weekly_client" data-display="This Week">This Week</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a href="client_list" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body  client_div">
                            <div class="table-responsive">
                                <table class="table dataTable">
                                    <thead>
                                        <th>Client Name</th>
                                        <th>Case Number</th>
                                    </thead>
                                    <tbody>
                                        @if($today_add_client=="[]")
                                        <tr>
                                            <td colspan="2">No records found!! </td>
                                        </tr>
                                        @else

                                        @foreach ($today_add_client as $cl)
                                        <tr>
                                            <td>{{$cl->client_name}}</td>
                                            <td>{{$cl->case_no}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header  d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                Quotations Sent
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_quotation" href="javascript:;" data-value="today_quotation" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_quotation" href="javascript:;" data-value="weekly_quotation" data-display="This Week">This Week</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a href="quotation_list" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body  quotation_div">
                            <div class="table-responsive">
                                <table class="table dataTable">

                                    <thead>
                                        <th>Client Name</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Send Date</th>
                                    </thead>

                                    <tbody>
                                        @if($today_quotations=="[]")
                                        <tr>
                                            <td colspan="4">No records found!! </td>
                                        </tr>
                                        @else
                                        @foreach ($today_quotations as $quot)
                                        <tr>
                                            <td>{{ $quot->case_no }}
                                                <small>({{ $quot->client_name }})</small>
                                            </td>
                                            <td>{{$quot->task_name}}</td>
                                            <td>{{$quot->amount}}</td>
                                            <td><?php echo date("d-M-Y", strtotime($quot->send_date)); ?></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header  d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                Raised Invoice
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_raised_invoice" href="javascript:;" data-value="today_invoice" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_raised_invoice" href="javascript:;" data-value="weekly_invoice" data-display="This Week">This Week</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a href="invoice_list" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body  invoice_div">
                            <div class="table-responsive">
                                <table class="table dataTable">

                                    <thead>
                                        <th>Client Name</th>
                                        <th>Amount</th>
                                        <th>Bill Date</th>
                                        <th>Due Date</th>
                                    </thead>

                                    <tbody>
                                        @if($today_raised_invoice=="[]")
                                        <tr>
                                            <td colspan="4">No records found!! </td>
                                        </tr>
                                        @else
                                        @foreach ($today_raised_invoice as $inv)
                                        <tr>
                                            <td>{{ $inv->case_no }}
                                                <small>({{ $inv->client_name }})</small>
                                            </td>
                                            <td>{{$inv->total_amount}}</td>
                                            <td><?php echo date("d-M-Y", strtotime($inv->bill_date)); ?></td>
                                            <td><?php echo date("d-M-Y", strtotime($inv->due_date)); ?></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header  d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                Received Payment
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_received_payment" href="javascript:;" data-value="today_payment" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_received_payment" href="javascript:;" data-value="weekly_payment" data-display="This Week">This Week</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a href="payment_list" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body payment_div">
                            <div class="table-responsive">
                                <table class="table dataTable">

                                    <thead>
                                        <th>Client Name</th>
                                        <th>Amount</th>
                                        <th>Mode of Payment</th>
                                        <th>Payment Date</th>
                                    </thead>

                                    <tbody>
                                        @if($today_received_payment=="[]")
                                        <tr>
                                            <td colspan="4">No records found!! </td>
                                        </tr>
                                        @else
                                        @foreach ($today_received_payment as $pay)
                                        <tr>
                                            <td>{{ $pay->case_no }}
                                                <small>({{ $pay->client_name }})</small>
                                            </td>
                                            <td>{{$pay->payment}}</td>
                                            <td>{{$pay->mode_of_payment}}</td>
                                            <td><?php echo date("d-M-Y", strtotime($pay->payment_date)); ?></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header  d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                Leads By Sales
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_sales_lead" href="javascript:void(0);" data-value="today_sales_lead" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_sales_lead" href="javascript:void(0);" data-value="monthly_sales_lead" data-display="This Month">This Month</a>
                                            <a class="dropdown-item filter_sales_lead" href="javascript:void(0);" data-value="quarterly_sales_lead" data-display="This Quarter">This
                                                Quarter</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center mr-1">
                                    <!-- <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i> -->
                                    <select name="staff_id" id="staff_id" class="form-control">
                                        <option value="">Select Staff</option>
                                        @foreach($staff as $stf)
                                        <option value="{{$stf->sid}}">{{$stf->name}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a href="#" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body sales_div">
                            <div class="table-responsive">
                                <table class="table dataTable">

                                    <thead>
                                        <th>Client Name</th>
                                        <th>No of units</th>
                                        <th>area</th>
                                        <th>Property type</th>
                                        <th>Date</th>
                                    </thead>

                                    <tbody>
                                        @if($today_sales_lead=="[]")
                                        <tr>
                                            <td colspan="5">No records found!! </td>
                                        </tr>
                                        @else
                                        @foreach ($today_sales_lead as $slead)
                                        <tr>
                                            <td><span>{{ $slead->case_no }}<small>({{ $slead->client_name }})</small></span>
                                            </td>
                                            <td>{{$slead->no_of_units}}</td>
                                            <td>{{$slead->area}}</td>
                                            <td>{{$slead->property_type_name}}</td>
                                            <td><?php echo date("d-M-Y", strtotime($slead->date)); ?></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="card widget-todo">
                        <div class="card-header  d-flex justify-content-between align-items-center">
                            <h4 class="card-title d-flex">
                                Leads By Office
                            </h4>
                            <ul class="list-inline d-flex mb-0">
                                <li class="d-flex align-items-center mr-1">
                                    <i class='bx bx-filter font-medium-3 mr-50 cursor-pointer'></i>
                                    <div class="dropdown">
                                        <div class="dropdown-toggle" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="cursor-pointer">Today</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item filter_office_lead" href="javascript:;" data-value="today_office_lead" data-display="Today">Today</a>
                                            <a class="dropdown-item filter_office_lead" href="javascript:;" data-value="monthly_office_lead" data-display="This Month">This
                                                Month</a>
                                            <a class="dropdown-item filter_office_lead" href="javascript:;" data-value="quarterly_office_lead" data-display="This Quarter">This
                                                Quarter</a>
                                        </div>
                                    </div>
                                </li>

                                <li class="d-flex align-items-center">
                                    <a href="#" class="btn btn-primary btn-sm round mr-1">
                                        View More
                                        <i class='bx bx-chevrons-right'></i>
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="card-body office_div">
                            <div class="table-responsive">
                                <table class="table dataTable">

                                    <thead>
                                        <th>Client Name</th>
                                        <th>No of units</th>
                                        <th>area</th>
                                        <th>Property type</th>
                                        <th>Date</th>
                                    </thead>

                                    <tbody>
                                        @if($today_office_lead=="[]")
                                        <tr>
                                            <td colspan="5">No records found!! </td>
                                        </tr>
                                        @else
                                        @foreach ($today_office_lead as $olead)
                                        <tr>
                                            <td><span>{{ $olead->case_no }}<small>({{ $olead->client_name }})</small></span>
                                            </td>
                                            <td>{{$olead->no_of_units}}</td>
                                            <td>{{$olead->area}}</td>
                                            <td>{{$olead->property_type_name}}</td>
                                            <td><?php echo date("d-M-Y", strtotime($olead->date)); ?></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (session('role_id') == 3)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Appointments</h4>
                </div>
                <div class="card-body card-dashboard">

                    <div class="table-responsive">
                        <table class="table dataTable">

                            <thead>
                                <!-- <th>Action</th> -->
                                <th>Client</th>
                                <th>Meeting with</th>
                                <th>Scheduled by</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Place</th>
                                <th>Status</th>
                            </thead>

                            <tbody>
                                @foreach($todays_appointment as $val)
                                <tr>
                                    <!-- <td>
                                      <div class="client-action">
                                          @if($val->status=='finalize')
                                          <button type="button"
                                              class="btn btn-icon rounded-circle btn-light-success mr-1 mb-1 view_consulting_btn"
                                              data-toggle="modal" data-target="#viewConsultingFee"
                                              data-appointment_id="{{$val->id}}">
                                              <i class="bx bx-down-arrow-alt"></i></button>
                                          @else
                                          <button type="button"
                                              class="btn btn-icon rounded-circle btn-light-success mr-1 mb-1 consulting_pay_btn"
                                              data-appointment_id="{{$val->id}}" data-fees="{{$val->charges}}"
                                              data-toggle="modal" data-target="#modalconsultingfee">
                                              <i class="bx bx-credit-card"></i></button>
                                          @endif
                                          @if($val->status=='finalize')
                                          <a href="consulting_fee_reciept-{{$val->id}}"
                                              class="btn btn-icon rounded-circle btn-light-warning mr-1 mb-1"
                                              data-appointment_id="{{$val->id}}">
                                              <i class="bx bx-printer"></i></a>
                                          @else
                                          <button type="button"
                                              class="btn btn-icon rounded-circle btn-light-warning mr-1 mb-1"
                                              disabled="disabled">
                                              <i class="bx bx-printer"></i></button>
                                          @endif
                                          <button type="button"
                                              class="btn btn-icon rounded-circle btn-light-info mr-1 mb-1 remodalbtn"
                                              data-appointment_id="{{$val->id}}"
                                              data-meeting_with="{{$val->meeting_with}}" data-place="{{$val->place}}"
                                              data-time="{{$val->meeting_time}}" data-date="{{$val->meeting_date}}"
                                              data-toggle="modal" data-target="#reshcheduleModal">
                                              <i class="bx bx-reset"></i></button>
                                          <button type="button"
                                              class="btn btn-icon rounded-circle btn-light-danger mr-1 mb-1 delete_appointment_btn"
                                              data-appointment_id="{{$val->id}}">
                                              <i class="bx bx-trash"></i></button>
                                      </div>
                                  </td> -->
                                    <td>{{$val->cname}}</td>
                                    <td>{{$val->meetname}}</td>
                                    <td>{{$val->schedule_by}}</td>
                                    <td>{{date('d-m-Y',strtotime($val->meeting_date))}}</td>
                                    <td>{{$val->meeting_time}}</td>
                                    <td>{{$val->aname}}</td>
                                    <td>{{$val->status}}</td>
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


    @if (session('role_id') == 3)
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leaves</h4>
                </div>
                <div class="card-body pt-1">

                    <div class="table-responsive leave_div">
                        <table class="table dataTable zero-configuration">

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


    <div class="row">
    </div>

    <!-- Activity Card Starts-->

    </div>



</section>
<!-- Dashboard Analytics end -->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/dragula.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/pages/dashboard-analytics.js')}}"></script>
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{asset('js/scripts/pages/get_leads.js')}}"></script>
<script>
    $(document).ready(function() {
        /*$('.dataTable').DataTable({
            ordering: false,
            lengthChange: false,
            bFilter: false,
            searching: false,
            info: false
        });*/
        $("#staff_id").select2({
            dropdownAutoWidth: true,
            width: "100%",
            placeholder: "Select Staff",
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: 'search_client',
            data: {
                value: 'case_no'
            },

            success: function(data) {
                console.log(data);
                $(".client_info").autocomplete({

                    source: data
                });
            }
        });

        $(document).on('click', '.detailBtn', function() {

            var client_id = $('.clientID').val();
            var detail = $(this).data('detail');

            get_quo_appo_foll(client_id, detail);
        });

        $(document).on('click', '.dropdown-item', function() {
            $(this).closest('ul').find('span.cursor-pointer').html($(this).data('display'));
        });

        $(document).on("click", ".search_client", function() {

            if ($(this).is(":checked")) {
                var value = $(this).val();
            }
            $('.client_info_loading').css('display', 'block');
            $.ajax({
                type: 'get',
                url: 'search_client',
                data: {
                    value: value
                },

                success: function(data) {
                    $('.client_info_loading').css('display', 'none');
                    console.log(data);
                    $(".client_info").val('');
                    $(".client_info").autocomplete({

                        source: data
                    });
                }
            });
        });

        $('.client_info').autocomplete({


            select: function(event, ui) {

                var value = ui.item.value;
                if ($('.search_client').is(":checked")) {
                    var type = $('.search_client:checked').val();
                }
                $('.client_info_loading').css('display', 'block');
                $.ajax({
                    type: 'get',
                    url: 'search_exist_client',
                    data: {
                        value: value,
                        type: type
                    },

                    success: function(data) {
                        console.log(data);
                        $('.client_info_loading').css('display', 'none');
                        jQuery.each(data, function(i, val) {
                            $('.client_detail_div').css('display', 'block');
                            var href = 'follow-up-add-' + val.client_id;
                            $('.follow_up_add').attr('href', href);
                            $('.property_type_span').text(val
                                .type_name);
                            $('.address_span').text(val.address);
                            $('.city_span').text(val.city_name);
                            $('.client_name_span').text(val.client_name);
                            $('.case_no_span').text(val
                                .case_no);
                            $('.area_span').text(val
                                .area);
                            $('.unit_span').text(val.no_of_units);
                            $('.source_span').text(val.source_name);
                            $('.assign_name_span').text(val.assign_name);
                            $('.assigned_date_span').text(val.assigned_date);
                            $('.appointments_span').text(val.appointments);
                            $('.followups_span').text(val.followups);
                            $('.quotations_span').text(val.quotations);
                            var contacts = val.contacts;
                            $.each(contacts, function(key, value) {
                                if (value.contact) {
                                    var contact = value.contact;
                                } else {
                                    var contact = '';
                                }
                                if (value.email) {
                                    var email = value.email;
                                } else {
                                    var email = '';
                                }
                                $('#client_contacts').empty().append('<tr> <td class="text-center"><small>' + value.name + '</small></td>  <td class="text-center"><small>' + contact + '</small></td> <td class="text-center"><small>' + email + '</small></td></tr>');
                            })
                            $('.clientID').val(val.client_id);
                            $('.client_info_div').css('display',
                                'block');
                        });

                    }
                });
            }

        });

    });
</script>
<script>
    $(document).on('click', '.filter_quotation', function() {
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'filter_today_quotation',
            data: {
                value: value
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.quotation_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter quotation",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('click', '.filter_appointment', function() {
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'filter_today_appointment',
            data: {
                value: value
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.appointment_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter appointment",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('click', '.filter_leave', function() {
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'filter_today_leave',
            data: {
                value: value
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.leave_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter leave",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('click', '.filter_add_client', function() {
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'filter_today_add_client',
            data: {
                value: value
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.client_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter client",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('click', '.filter_raised_invoice', function() {
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'filter_today_invoice',
            data: {
                value: value
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.invoice_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter invoice",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('click', '.filter_received_payment', function() {
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'filter_today_received_payment',
            data: {
                value: value
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.payment_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter payment",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('click', '.filter_sales_lead', function() {
        var value = $(this).data('value');
        var staff_id = $('#staff_id').val();

        $.ajax({
            type: 'post',
            url: 'filter_today_sales_lead',
            data: {
                value: value,
                staff_id: staff_id
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.sales_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter leads by sales",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('change', '#staff_id', function() {
        var value = $(this).closest('ul').children('li').children('div').find('.cursor-pointer').text();
        var staff_id = $(this).val();

        $.ajax({
            type: 'post',
            url: 'filter_today_sales_lead',
            data: {
                value: value,
                staff_id: staff_id
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.sales_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter leads by sales",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $(document).on('click', '.filter_office_lead', function() {
        var value = $(this).data('value');

        $.ajax({
            type: 'post',
            url: 'filter_today_office_lead',
            data: {
                value: value
            },

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                if (res.status == "success") {
                    $('.office_div').empty().html(res.out);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter leads by sales",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });
</script>
@endsection