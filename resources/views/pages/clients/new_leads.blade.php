@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','New Leads')
{{-- vendor style --}}
@section('vendor-styles')
@include('links.datatable_links')
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<style>
    .valid_err {
        color: red;
        font-size: 12px;
    }
</style>
<style>
    .staff-dropdown {
        height: 25px !important;
        overflow-y: auto !important;
    }

    .dropdown-menu {
        z-index: 99999 !important;
    }

    .margin_right {
        margin-right: 2px;
    }

    .datepicker-days {
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker-months {
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker-years {
        width: 235px !important;
        height: 220px !important;
        padding-left: 10px;
    }

    .datepicker thead {
        background-color: #e9edf1;
        color: #2454b1;
    }

    .table th,
    .table td {
        font-family: "Rubik", Helvetica, Arial, serif;
        font-size: 14px;
    }
</style>
@endsection
@section('content')
<!-- invoice list -->
<section class="client-list-wrapper">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class='col-8'>
                    @include('layouts.tabs')
                     <div id="alert">
                    </div>
                </div>

                 <div class='col-2'>
                    <a class="btn btn-icon btn-outline-primary px-3 float-right ml-1" data-toggle="modal" data-target="#UploadDataModal">
                        <strong><i class="bx bx-plus"></i>Upload Data</strong></a>
                </div>
                <div class='col-2'>
                    <a class="btn btn-icon btn-outline-primary px-3 float-right ml-1" data-toggle="modal" data-target="#NewLeadsModal">
                        <strong><i class="bx bx-plus"></i>New Leads</strong></a>
                </div>
            </div>
            <div class="row">
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

                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" id="search_by_source">
                            <option value="">Select Source</option>
                            <option value="fb">Facebook</option>
                            <option value="app">App</option>
                            <option value="Whatsapp">Whatsapp</option>
                            <option value="web">Web</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control" placeholder="Enter Address" id="search_by_address">
                    </div>
                </div>
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control input_control" id="search_by_city">
                                <option value="">City</option>
                                @foreach ($address as $row4)
                                <option value={{$row4->id}}>
                                    {{$row4->city_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control input_control" id="search_by_status">
                                <option value="">Status</option>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control" placeholder="Enter mobile no" id="search_by_mobile_no">
                    </div>
                </div>
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <div class="input-group">
                            <select class="form-control input_control" id="search_by_complete">
                                <option value="">Complete</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-1">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
                </div>
                <div class="col-md-1">
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <select class="form-control client" id="client" multiple="multiple">
                            <option value="">Search By Name</option>
                            @foreach ($leads_name as $row1)
                            <option value={{$row1->id}}>
                                {{$row1->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
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
                @if(session('role_id') == 1)

                <div class="action-dropdown-btn">
                    <div class="dropdown client-filter-action">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="selection">Action</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right company-dropdown" aria-labelledby="commpany_filter">
                            <a type="button" href="#" class="dropdown-item multi_convert_client" id="multi_convert_client">Convert</a>
                            <a type="button" href="#" class="dropdown-item Complete" id="complete">Complete</a>
                            <a type="button" href="#" class="dropdown-item delete" id="delete">Delete</a>

                        </div>
                    </div>

                    <div class="dropdown client-filter-action" style="padding-left:10px">
                        <div class="dropdown client-filter-action">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="commpany_filter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="selection">Assign company</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right company-dropdown" aria-labelledby="commpany_filter">
                                @foreach(session('company_full') as $com)
                                <a type="button" href="#" class="dropdown-item assign_com_btn" data-company_id="{{$com->id}}" data-company_val="{{$com->company_name}}">{{$com->company_name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @endif
                    <div class="table-responsive">
                        <table class="table client-data-table wrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    <th>Action</th>
                                    <th>Name</th>

                                    <th>Society Name</th>
                                    <th>Company</th>

                                    <th>Source</th>
                                    <th>Units</th>
                                    <th>Follow Up</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Any Query</th>
                                    <th>Area</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Services</th>
                                    <th>Lead Source</th>
                                    <th>Commitee Member</th>
                                    <th>Fb id</th>
                                    <th>Ad id</th>
                                    <th>Ad name</th>
                                    <th>Adset id</th>
                                    <th>Adset name</th>
                                    <th>Campaign id</th>
                                    <th>Campaign name</th>
                                    <th>Form id</th>
                                    <th>Form name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($new_leads_list as $row)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><input type="hidden" class="form-control leadID" value="{{ $row->id }}"></td>
                                    <td>
                                        <div class="row client-action">
                                            <a href="#" class="btn btn-icon rounded-circle glow btn-secondary add_followup" data-id="{{$row->id}}" data-name="{{$row->name}}" data-tooltip="Add follow-up" data-toggle="modal" data-target="#followupModal">
                                                <i class="bx bx-phone-call"></i>
                                            </a>
                                            <a href="leads_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle glow btn-warning margin_right" data-tooltip="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            @if(session('role_id')==1 || session('role_id')==3)
                                            <a href="#" class="btn btn-icon rounded-circle glow btn-danger delete_leads margin_right" data-id="{{$row->id}}" data-tooltip="Delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                            @if($row->company=='' || $row->company==NULL)
                                            <button href="#" class="btn btn-icon rounded-circle glow btn-secondary margin_right convert_client" data-tooltip="Convert to Lead" disabled="disabled">
                                                <i class="bx bx-transfer"></i>
                                            </button>
                                            @else
                                            <button href="#" data-id="{{$row->id}}" data-company_id="{{$row->company}}" class="btn btn-icon rounded-circle glow btn-secondary margin_right convert_client" data-tooltip="Convert to Lead">
                                                <i class="bx bx-transfer"></i>
                                            </button>
                                            @endif
                                            <div class="save_lead_div" style="display:none;">
                                                <button href="#" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-success margin_right save_lead_type" data-tooltip="Save Lead Type">
                                                    <i class="bx bx-save"></i>
                                                </button>

                                                <button href="#" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-danger margin_right close_lead_type" data-tooltip="Close Lead Type">
                                                    <i class="bx bx-window-close"></i>
                                                </button>
                                            </div>
                                            <!-- <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-primary margin_right change_lead_type" data-tooltip="Change Lead Type">
                                            <i class="bx bx-analyse"></i>
                                        </button> -->
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $row->name }}</td>


                                    <td>{{$row->society_name}}</td>
                                    <td>{{$row->company_name}}</td>
                                    <td>
                                        @if($row->from=='web')
                                        <img src="{{asset('images/source_icons/web.png')}}" alt="Web">
                                        @elseif($row->from=='app')
                                        <img src="{{asset('images/source_icons/app.png')}}" alt="app">
                                        @elseif($row->from=='fb')
                                        <img src="{{asset('images/source_icons/facebook.png')}}" alt="Facebook">
                                        @elseif($row->from=='newspaper')
                                        <img src="{{asset('images/source_icons/newspaper.png')}}" alt="newspaper">
                                        @elseif($row->from=='whatsApp-group')
                                        <img src="{{asset('images/source_icons/whatsApp-group.png')}}" alt="whatsApp-group">
                                        @elseif($row->from=='walk-in')
                                        <img src="{{asset('images/source_icons/walk-in.png')}}" alt="walk-in">
                                        @elseif($row->from=='client-ref')
                                        <img src="{{asset('images/source_icons/client-ref')}}" alt="client-ref">
                                        @endif
                                    </td>
                                    <td>{{$row->units}}</td>
                                    <td>
                                        <a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="Follow-up" data-toggle="modal" data-target="#detailModal">{{$row->followups}}</a>
                                    </td>
                                    <td>{{$row->mobile_no}}</td>
                                    <td>{{$row->email}}</td>
                                    <td>{{$row->city}}</td>
                                    <td>{{$row->any_query}}</td>
                                    <td>{{$row->area}}</td>
                                    <td>{{$row->address}}</td>
                                    <td>{{$row->role}}</td>
                                    <td>{{$row->services}}</td>
                                    <td>{{$row->lead_source}}</td>
                                    <td>{{ $row->check_commitee_member }}</td>
                                    <td>{{ $row->fb_id }}</td>
                                    <td>{{ $row->ad_id }}</td>
                                    <td>{{ $row->ad_name }}</td>
                                    <td>{{ $row->adset_id }}</td>
                                    <td>{{ $row->adset_name }}</td>
                                    <td>{{ $row->campaign_id }}</td>
                                    <td>{{ $row->campaign_name }}</td>
                                    <td>{{ $row->form_id }}</td>
                                    <td>{{ $row->form_name }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>
                                        @if($row->created_at!='')
                                        {{date('d-M-Y',strtotime($row->created_at))}}
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

        <div class="modal fade text-left" id="UploadDataModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="myModalLabel1">Upload Leads</h3>
                        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-xl-6">
                                    <input type="file" class="form-control csv_file" name="file">
                                    <span class="valid_err file_err"></span>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xl-6">
                                    <a href="{{url('')}}/sample_csv/FB_leads_csv_sample.csv" class="btn btn-warning btn-md round px-3 search" style="float: right;"><strong>Sample CSV</strong></a>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="button" id="upload_btn_submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Upload</span>
                        </button>
                    </div>
                    </form>
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

            </div>

        </div>
    </div>
</div>

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
                <form method="POST" id="FollowUPform">
                    <div class="row mx-0">
                        <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">


                        </div>
                        <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                            <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                                <div class="d-flex align-items-center">
                                    <span class="mr-75 label_title">Date: </span>

                                    <fieldset class="d-flex ">
                                        <input type="text" class="form-control datepicker mr-2 followup_date" placeholder="Select Date">
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
                                <input type="text" class="form-control datepicker mr-2 next_followup_date" placeholder="Next Follow-up Date">
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

                </form>
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

<div class="modal fade text-left" id="NewLeadsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="myModalLabel1">New Leads</h3>
                        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <input type="text" class="form-control" id="client_name" placeholder="Client Name">
                                    <span class="valid_err client_name_err"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12" >
                                    <input type="text" class="form-control" id="contact_no" placeholder="Contact No.">
                                    <span class="valid_err contact_no_err"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12" >
                                    <input type="text" class="form-control" id="units" placeholder="Units">
                                    <span class="valid_err units_err"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12" >
                                    <input type="text" class="form-control" id="address" placeholder="Address">
                                    <span class="valid_err address_err"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12" >
                                    <input type="text" class="form-control" id="area" placeholder="Area">
                                    <span class="valid_err area_err"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12" >
                                    <input type="text" class="form-control" id="source" placeholder="Source">
                                    <span class="valid_err source_err"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12" >
                                    <input type="text" class="form-control" id="city" placeholder="City">
                                    <span class="valid_err city_err"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12" >
                                <textarea class="form-control address" autocomplete="off" id="remarks" placeholder="Remarks"></textarea>
                                    <span class="valid_err remark_err"></span>
                                </div>
                            </div><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="button" id="leads_btn_submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
<!---end-->
@endsection
{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/get_new_leads_action.js')}}"></script>
<script src="{{asset('js/scripts/wickedpicker/dist/wickedpicker.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $(".datepicker")
            .datepicker()
            .on("changeDate", function(ev) {
                $(".datepicker.dropdown-menu").hide();
            });
    });
    $(document).ready(function() {
        if ($(".client-data-table").length) {
            var dataListView = $(".client-data-table").DataTable({
                scrollX: true,
                scrollCollapse: true,
                autoWidth: true,
                iDisplayLength: 20,
                columnDefs: [{
                        width: "150px",
                        targets: [3],
                    },
                    {
                        width: "100px",
                        targets: [10],
                    },
                    {
                        width: "100px",
                        targets: [11],
                    },
                    {
                        width: "100px",
                        targets: [13],
                    },
                    {
                        targets: 0,
                        className: "control",
                    },
                    {
                        orderable: false,
                        targets: 1,
                        checkboxes: {
                            selectRow: true,
                        },
                    },
                    {
                        targets: [0, 1, 2, 3],
                        orderable: false,
                    },
                ],
                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
                language: {
                    search: "",
                    searchPlaceholder: "Search Leads",
                },

                select: {
                    style: "multi",
                    selector: "td:first-child",
                    items: "row",
                },


            });
        }
        // To append actions dropdown inside action-btn div
        var clientFilterAction = $(".client-filter-action");
        $(".action-btns").append(clientFilterAction);
        $(".dt-checkboxes-cell")
            .find("input")
            .on("change", function() {
                var $this = $(this);
                if ($this.is(":checked")) {
                    $this.closest("tr").addClass("selected-row-bg");
                } else {
                    $this.closest("tr").removeClass("selected-row-bg");
                }
            });
        // Select all checkbox
        $(document).on("change", ".dt-checkboxes-select-all input", function() {
            if ($(this).is(":checked")) {
                $(".dt-checkboxes-cell")
                    .find("input")
                    .prop("checked", this.checked)
                    .closest("tr")
                    .addClass("selected-row-bg");
                $('.source-action').hide();
            } else {
                $(".dt-checkboxes-cell")
                    .find("input")
                    .prop("checked", "")
                    .closest("tr")
                    .removeClass("selected-row-bg");
                $('.source-action').show();
            }
        });

        $(document).on("change", ".dt-checkboxes-cell input", function() {
            if ($(this).is(":checked")) {
                $(this)
                    .find("input")
                    .prop("checked", this.checked)
                    .closest("tr")
                    .addClass("selected-row-bg");
                $('.source-action').hide();
            } else {
                $(this)
                    .find("input")
                    .prop("checked", "")
                    .closest("tr")
                    .removeClass("selected-row-bg");
                $('.source-action').show();
            }
        });


        $(document).on("click", ".add_followup", function() {
            $(".followup_client_name").val($(this).data("name"));
            $(".followup_client_id").val($(this).data("id"));
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

        $(document).on("click", "#save_followup", function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $(".valid_err").html("");

            var client = $(".followup_client_id").val();
            var company = $(".company").val();
            var contact_by = $(".contact_by").val();
            var followup_date = $(".followup_date").val();
            var next_followup_date = $(".next_followup_date").val();
            var discussion = $(".discussion").val();

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

            var method = $('#method').val();

            if (followup_date == '') {
                arr.push('followup_date_err');
                arr.push('Follow-up date required');
            }
            if (company == '') {
                arr.push('company_err');
                arr.push('company required');
            }
            if (client == '') {
                arr.push('client_err');
                arr.push('Client required');
            }
            if (contact_by == '') {
                arr.push('contact_by_err');
                arr.push('Please select contact by');
            }

            if (method == '') {
                arr.push('method_err');
                arr.push('Please select method method');
            }
            if (discussion == '') {
                arr.push('discussion_err');
                arr.push('Discussion required');
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


            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: "post",
                    url: "submit_lead_followUp",
                    data: {
                        client_id: client,
                        method: method,
                        company: company,
                        contact_by: contact_by,
                        followup_date: followup_date,
                        next_followup_date: next_followup_date,
                        finalized: finalized,
                        lead_closed: lead_closed,
                        discussion: discussion,
                    },
                    success: function(data) {

                        console.log(data);
                        $("#followupModal").modal("hide");
                        $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        }, "slow");
                        var res = JSON.parse(data);
                        if (res.status == "success") {
                            console.log(res);
                            $("#alert").html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg +
                                "</span></div></div>"
                            );
                            $("#FollowUPform")[0].reset();
                          

                        } else {
                            $("#alert").html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg +
                                "</span></div></div>"
                            );
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        }, "slow");
                        
                        $("#alert").html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something went wrong!</span></div></div>'
                        );
                        $(".alert")
                            .fadeTo(2000, 500)
                            .slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                       
                    },
                });
            }
        });

    });
    $(document).ready(function() {
        $(document).on('click', '.detailBtn', function() {
            var client_id = $(this).data('client_id');
            var detail = $(this).data('detail');
            get_lead_followup(client_id, detail);
        });
    });
</script>
<script type="text/javascript">
    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isCSV(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'csv':
                //etc
                return true;
        }
        return false;
    }
    $(document).on('click', '#upload_btn_submit', function() {
        $('.valid_err').html('');
        var arr = [];
        var form = $('#form')[0];
        var formdata = new FormData(form);
        console.log(formdata);
        var file = $('.csv_file').val();
        var msg = isCSV(file);

        if (file != '') {
            if (msg == false) {
                arr.push('file_err');
                arr.push('Only csv file required');
            }
        } else {
            arr.push('file_err');
            arr.push('CSV file required');
        }
        if (arr != '') {
            $('.valid_err').html('');
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;
                $('.' + arr[i]).html(arr[j]).css('color', 'red');
                i = j;
            }
        } else {
            $.ajax({
                type: 'post',
                url: 'upload_data',
                data: formdata,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#cover-spin").show();
                },
                complete: function() {
                    $("#cover-spin").hide();
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('.csv_file').val('');
                        Swal.fire({
                            icon: "success",
                            title: "Upload",
                            text: data.msg
                        });
                        setTimeout(function() {
                            window.location.reload()
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: data.msg
                        });
                    }
                },
                error: function(data) {
                    console.log(data);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: data.msg
                    });
                }
            });
        }
    });
    $(document).on('click', '.assign_com_btn', function() {
        alert();
        var company_id = $(this).data("company_id");
        var company_name = $(this).data("company_val");
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var staff_id = $("#search_by_staff").val();
        var source = $("#search_by_source").val();
        var address = $("#search_by_address").val();
        var city = $("#search_by_city").val();
        var status = $("#search_by_status").val();
        var lead_type = $("#search_by_leadtype").val();
        var lead_id = new Array();
        $(".dt-checkboxes:checked").each(function() {
            lead_id.push($(this).closest("tr").find(".leadID").val());
        });

        if (lead_id == "") {
            $("#alert").animate({
                    scrollTop: $(window).scrollTop(0),
                },
                "slow"
            );
            $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-danger"></i><span>Checkbox is not selected!</span></div></div>'
            );
            $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
        } else {
            $(".loader").css("display", "block");
            $.ajax({
                type: "post",
                url: "assign_leads_to_company",
                data: {
                    company_id: company_id,
                    lead_id: lead_id,
                    client_leads: "leads",
                    page: "leads",
                    selection_val: "Active",
                    from_date: from_date,
                    to_date: to_date,
                    address: address,
                    staff_id: staff_id,
                    source: source,
                    city: city,
                    status: status,
                    lead_type: lead_type,
                },

                success: function(data) {
                    console.log(data);



                    $(".loader").css("display", "none");
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        },
                        "slow"
                    );
                    $("#alert").html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Company assigned successfully</span></div></div>');
                    $(".alert")
                        .fadeTo(2000, 500)
                        .slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    $('.data_div').empty().html(data);


                },
                error: function(data) {
                    console.log(data);
                },
            });
        }
    });
    $(document).on('click', '#multi_convert_client', function() {


        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var staff_id = $("#search_by_staff").val();
        var source = $("#search_by_source").val();
        var address = $("#search_by_address").val();
        var city = $("#search_by_city").val();
        var status = $("#search_by_status").val();
        var lead_type = $("#search_by_leadtype").val();
        var lead_id = new Array();
        $(".dt-checkboxes:checked").each(function() {
            lead_id.push($(this).closest("tr").find(".leadID").val());
        });

        if (lead_id == "") {
            $("#alert").animate({
                    scrollTop: $(window).scrollTop(0),
                },
                "slow"
            );
            $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-danger"></i><span>Checkbox is not selected!</span></div></div>'
            );
            $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
        } else {
            $(".loader").css("display", "block");
            $.ajax({
                type: "post",
                url: "multi_convert_client",
                data: {

                    lead_id: lead_id,
                    client_leads: "leads",
                    page: "leads",
                    selection_val: "Active",
                    from_date: from_date,
                    to_date: to_date,
                    address: address,
                    staff_id: staff_id,
                    source: source,
                    city: city,
                    status: status,
                    lead_type: lead_type,
                },

                success: function(data) {
                    console.log(data);



                    $(".loader").css("display", "none");
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        },
                        "slow"
                    );
                    $("#alert").html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Client converted successfully</span></div></div>');
                    $(".alert")
                        .fadeTo(2000, 500)
                        .slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    $('.data_div').empty().html(data);


                },
                error: function(data) {
                    console.log(data);
                },
            });
        }
    });
    $(document).on('click', '#complete', function() {
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var staff_id = $("#search_by_staff").val();
        var source = $("#search_by_source").val();
        var address = $("#search_by_address").val();
        var city = $("#search_by_city").val();
        var status = $("#search_by_status").val();
        var lead_type = $("#search_by_leadtype").val();
        var lead_id = new Array();
        $(".dt-checkboxes:checked").each(function() {
            lead_id.push($(this).closest("tr").find(".leadID").val());
        });

        if (lead_id == "") {
            $("#alert").animate({
                    scrollTop: $(window).scrollTop(0),
                },
                "slow"
            );
            $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-danger"></i><span>Checkbox is not selected!</span></div></div>'
            );
            $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
        } else {
            $(".loader").css("display", "block");
            $.ajax({
                type: "post",
                url: "lead_complete",
                data: {

                    lead_id: lead_id,
                    client_leads: "leads",
                    page: "leads",
                    selection_val: "Active",
                    from_date: from_date,
                    to_date: to_date,
                    address: address,
                    staff_id: staff_id,
                    source: source,
                    city: city,
                    status: status,
                    lead_type: lead_type,
                },

                success: function(data) {
                    console.log(data);



                    $(".loader").css("display", "none");
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        },
                        "slow"
                    );
                    $("#alert").html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Mark complete successfully</span></div></div>');
                    $(".alert")
                        .fadeTo(2000, 500)
                        .slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    $('.data_div').empty().html(data);


                },
                error: function(data) {
                    console.log(data);
                },
            });
        }
    });

    $(document).on('click', '#delete', function() {
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var staff_id = $("#search_by_staff").val();
        var source = $("#search_by_source").val();
        var address = $("#search_by_address").val();
        var city = $("#search_by_city").val();
        var status = $("#search_by_status").val();
        var lead_type = $("#search_by_leadtype").val();
        var lead_id = new Array();
        $(".dt-checkboxes:checked").each(function() {
            lead_id.push($(this).closest("tr").find(".leadID").val());
        });

        if (lead_id == "") {
            $("#alert").animate({
                    scrollTop: $(window).scrollTop(0),
                },
                "slow"
            );
            $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-danger"></i><span>Checkbox is not selected!</span></div></div>'
            );
            $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
        } else {
            $(".loader").css("display", "block");
            $.ajax({
                type: "post",
                url: "lead_delete",
                data: {
                    lead_id: lead_id,
                    client_leads: "leads",
                    page: "leads",
                    selection_val: "Active",
                    from_date: from_date,
                    to_date: to_date,
                    address: address,
                    staff_id: staff_id,
                    source: source,
                    city: city,
                    status: status,
                    lead_type: lead_type,
                },
                success: function(data) {
                    $(".loader").css("display", "none");
                    $("#alert").animate({
                            scrollTop: $(window).scrollTop(0),
                        },
                        "slow"
                    );
                    $("#alert").html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Mark Delete successfully</span></div></div>');
                    $(".alert")
                        .fadeTo(2000, 500)
                        .slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    $('.data_div').empty().html(data);
                },
                error: function(data) {
                    console.log(data);
                },
            });
        }
    });

    function get_lead_followup(client_id, detail) {
        console.log(client_id);
        console.log(detail);
        $(".loader1").css("display", "block");
        $.ajax({
            type: "post",
            url: "get_lead_followup",
            data: {
                client_id: client_id,
                detail: detail,
            },
            success: function(data) {
                $(".loader1").css("display", "none");
                //console.log(data);
                $(".detailModal-title").empty().html(detail);
                $(".detailModal-body").empty().html(data);
            },

            error: function(data) {
                $(".loader1").css("display", "none");
                $("#alert")
                    .html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg +
                        "</span></div></div>"
                    )
                    .focus();
            },
        });
    }
     $(document).on('click','#leads_btn_submit',function(){
            var valid = validation();
      
            var client_name = $('#client_name').val();
            var contact_no = $('#contact_no').val();
            var units = $('#units').val();
            var address = $('#address').val();
            var area = $('#area').val();
            var city = $('#city').val();
            var remarks = $('#remarks').val();
            var source = $('#source').val();
            if(valid)
            {
                $.ajax({
                type: "post",
                url: "save_new_leads",
                data: 
                    {
                        client_name:client_name ,
                        contact_no:contact_no ,
                        units:units ,
                        address:address ,
                        area:area ,
                        city:city ,
                        remarks:remarks,
                        source:source,
                    },
                success: function(res) {
                    $(".loader1").css("display", "none");

                    console.log(res);
                    $("#NewLeadsModal").modal('toggle');
                    $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-error"></i><span>' +
                                res.msg + '</span></div></div>');
                },

                error: function(res) 
                {
                    $(".loader1").css("display", "none");
                    $("#alert").html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg +
                        "</span></div></div>"
                    ).focus();
                },
            });

            }
    });
    function validation() {
            $('.valid_err').html('');
            var valid = true;
            var client_name = $('#client_name').val();
            var contact_no = $('#contact_no').val();
            var units = $('#units').val();
            var address = $('#address').val();
            var area = $('#area').val();
            var city = $('#city').val();
            var remarks = $('#remarks').val();
           
            if (client_name == '') {
                $('.client_name_err').html('Please Enter client name');
                valid = false;
            }
            if (contact_no == '') {
                $('.contact_no_err').html('Please Enter contact no');
                valid = false;
            }
        

            return valid;
        }
</script>

@endsection