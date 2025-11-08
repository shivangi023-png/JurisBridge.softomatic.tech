@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Report')
{{-- vendor styles --}}
@section('vendor-styles')
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{(asset('vendors/css/forms/select/select2.min.css'))}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<script src="{{asset('vendors/js/jquery/jquery.min.js')}}"></script>
@endsection
{{-- page styles --}}
<style>
    .bx_icon {
        position: absolute;
        font-size: 20px !important;
    }

    .font_style {
        position: absolute;
        padding-top: 10px !important;
    }

    .border_primary {
        border: 1px solid #5a8dee !important;
    }

    .btn {
        border-radius: 0px !important;
    }

    .btn-outline-dark {
        border: 0px !important;
        border-bottom: 1px solid #000000 !important;
    }

    .RMenuBox {
        padding: 1rem 1.7rem;
    }

    .reportMenu {
        margin-top: -65px;
    }
</style>
@section('page-styles')

@endsection

@section('content')
<input type="hidden" value="{{url('/')}}" id="base_url">
<div class="card">
    <div class="card-body">
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
        <div class="row">
            <div class="col-12">
                <div class="row mt-2">
                    <div class="col-3"></div>
                    <div class="col-9">
                        <div class="row">
                            <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio">
                                                <input type="radio" class="show_filter" id="search_year_filter" name="bsradio" value="year_search">
                                                <label for="search_year_filter">Year</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio">
                                                <input type="radio" class="show_filter" id="search_month_year_filter" name="bsradio" value="month_year_search">
                                                <label for="search_month_year_filter">Monthwise</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio">
                                                <input type="radio" class="show_filter" id="search_quarter_year_filter" name="bsradio" value="quarter_year_search">
                                                <label for="search_quarter_year_filter">Quarterwise</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                            <div class="radio">
                                                <input type="radio" class="show_filter" id="search_daily_filter" name="bsradio" value="daily_search">
                                                <label for="search_daily_filter">Daily</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                </ul>
                                <span class="radio_error_message text-danger"></span>
                            </div>
                            <div class="col-6">
                                <div class="btn-group year-input mr-4 float-right" style="display:none;">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-dark text-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="year-text">Year</span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item year_filter" href="javascript:void(0);" data-value="Year" data-display="Year">Year</a>
                                            <?php
                                            $start_year = 2020;
                                            $end_year = date('Y');
                                            $b = $start_year + 1;
                                            ?>
                                            @for($a=$start_year;$a<=$end_year;$a++) <a class="dropdown-item year_filter" href="javascript:void(0);" data-value="{{$a}}" data-display="{{$a}}-{{$b}}">{{$a}}-{{$b}}</a>
                                                <?php $b++; ?>
                                                @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group month-input mr-2 float-right" style="display:none;">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-dark text-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="month-text">Monthly</span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-display="Monthly">Monthly</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="january" data-display="January">January</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="february" data-display="February">February</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="march" data-display="March">March</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="april" data-display="April">April</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="may" data-display="May">May</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="june" data-display="June">June</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="july" data-display="July">July</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="august" data-display="August">August</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="september" data-display="September">September</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="october" data-display="October">October</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="november" data-display="November">November</a>
                                            <a class="dropdown-item month_filter" href="javascript:void(0);" data-value="december" data-display="December">December</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group quarter-input mr-2 float-right" style="display:none;">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-dark text-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="quarter-text">Quarterly</span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item quarter_filter" href="javascript:void(0);" data-display="Quarterly">Quarterly</a>
                                            <a class="dropdown-item quarter_filter" href="javascript:void(0);" data-value="first_quarter" data-display="First Quarter">First
                                                Quarter</a>

                                            <a class="dropdown-item quarter_filter" href="javascript:void(0);" data-value="second_quarter" data-display="Second Quarter">Second
                                                Quarter</a>

                                            <a class="dropdown-item quarter_filter" href="javascript:void(0);" data-value="third_filter" data-display="Third Quarter">Third
                                                Quarter</a>
                                            <a class="dropdown-item quarter_filter" href="javascript:void(0);" data-value="fourth_filter" data-display="Fourth Quarter">Fourth
                                                Quarter</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group daily-input mr-4 float-right" style="display:none;">
                                    <div class="form-group">
                                        <input class="form-control daily_filter" type="date" name="daily" placeholder="Date" value="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="error_message text-danger my-2"></div>
                    </div>
                </div>
                <div class="data_div">
                    <div class="row">
                        <div class="col-3 pr-2">
                            <div class="card reportMenu">
                                @if (session ('role_id')!=8)
                                <a href="#quotationTab">
                                    <div class=" card-header border bg-primary text-white RMenuBox" id="quotation_report" onclick="get_report(this.id)">
                                        <i class="bx bx-file bx_icon mr-2"></i> <strong class="ml-2">Quotation
                                            Reports</strong>
                                    </div>
                                </a>
                                <a href="#expenseTab">
                                    <div class="card-header border mt-1 text-dark RMenuBox" id="expense_report" onclick="get_report(this.id)">
                                        <i class="bx bx-money bx_icon"></i> <strong class="ml-2">Expense
                                            Reports</strong>
                                    </div>
                                </a>
                                <a href="#clientTab">
                                    <div class="card-header border mt-1 text-dark RMenuBox" id="client_report" onclick="get_report(this.id)">
                                        <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Client
                                            Reports</strong>
                                    </div>
                                </a>
                                <a href="#attendanceTab">
                                    <div class="card-header border mt-1 text-dark RMenuBox" id="attendance_report" onclick="get_report(this.id)">
                                        <!-- <i class="bx bx-receipt bx_icon"></i> -->
                                        <span><img src="{{asset('images/icon/attendance-report.svg')}}" width=16px; height="17px;" class="bx_icon attendance_img1">
                                            <strong class="ml-2">Attendance
                                                Reports</strong></span>
                                    </div>
                                </a>
                                <a href="#accountingTab">
                                    <div class="card-header border mt-1 text-dark RMenuBox" id="accounting_report" onclick="get_report(this.id)">
                                        <i class="bx bx-spreadsheet bx_icon"></i>
                                        <strong class="ml-2">Accounting
                                            Reports </strong>
                                    </div>
                                </a>
                                <a href="#prettyCashTab">
                                    <div class="card-header border mt-1 text-dark RMenuBox" id="pretty_cash_report" onclick="get_report(this.id)">
                                        <i class="bx bx-spreadsheet bx_icon"></i>
                                        <strong class="ml-2">Pretty Cash
                                            Reports </strong>
                                    </div>
                                </a>

                                <a href="#adminTab">
                                    <div class="card-header border mt-1 admin_report text-dark RMenuBox" id="admin_report" onclick="get_report(this.id)">
                                        <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Admin
                                            Reports </strong>
                                    </div>
                                </a>

                                @endif
                                @if (session ('role_id')==8)
                                <a href="#salesTab">
                                    <div class="card-header border mt-1 sales_report text-dark RMenuBox" id="sales_report" onclick="get_report(this.id)">
                                        <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Sales
                                            Reports </strong>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </div>
                        @if (session ('role_id')!=8)
                        <div class="col-9">
                            <div class="card border_primary quotation_card" id="quotationTab">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">1. Quotation Sent Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="quotation_sent_pdf">
                                                <a href="javascript:void(0);" id="quotation_sent_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="quotation_sent_excel">
                                                <a href="javascript:void(0);" class="ml-1" id="quotation_sent_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="quotation_sent_print">
                                                <a href="javascript:void(0);" data-title="Quotation Sent" class="ml-1" id="quotation_sent_print" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">2. Servicewise Quotations Sent Report
                                            </h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="servicewise_quotation_sent_pdf">
                                                <a href="javascript:void(0);" id="servicewise_quotation_sent_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="servicewise_quotation_sent_excel">
                                                <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_sent_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="servicewise_quotation_sent_print">
                                                <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_sent_print" data-title="Servicewise Quotation Sent" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">3. Quotations Finalized Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="quotation_finalized_pdf">
                                                <a href="javascript:void(0);" id="quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="quotation_finalized_excel">
                                                <a href="javascript:void(0);" class="ml-1" id="quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="quotation_finalized_print">
                                                <a href="javascript:void(0);" class="ml-1" id="quotation_finalized_print" data-title="Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>



                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">4. Servicewise Quotations Finalized
                                                Report
                                            </h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="servicewise_quotation_finalized_pdf">
                                                <a href="javascript:void(0);" id="servicewise_quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="servicewise_quotation_finalized_excel">
                                                <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="servicewise_quotation_finalized_print">
                                                <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_finalized_print" data-title="Servicewise Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h6 class="font_style">5. Clientwise Quotations Finalized
                                                Report
                                            </h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="clientwise_quotation_finalized_pdf">
                                                <a href="javascript:void(0);" id="clientwise_quotation_finalized_pdf" onclick="download_pdf1(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="clientwise_quotation_finalized_excel">
                                                <a href="javascript:void(0);" class="ml-1" id="clientwise_quotation_finalized_excel" onclick="download_excel1(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="clientwise_quotation_finalized_print">
                                                <a href="javascript:void(0);" class="ml-1" id="clientwise_quotation_finalized_print" data-title="Clientwise Quotation Finalized" onclick="print1(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if (session ('role_id')==8)
                                <div class="col-9">
                                    <div class="card border_primary sales_card" id="salesTab">
                                        <div class="card-body">
                                            <div class="row mb-1">
                                                <div class="col-9">
                                                    <h6 class="font_style">1. Assigned Leads Report</h6>
                                                </div>
                                                <div class="col-3">
                                                    <span data-tooltip="Export to PDF" data-value="sales_assigned_leads_pdf">
                                                        <a href="javascript:void(0);" id="sales_assigned_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                        </a>
                                                    </span>
                                                    <span data-tooltip="Export to Excel" data-value="sales_assigned_leads_excel">
                                                        <a href="javascript:void(0);" class="ml-1" id="sales_assigned_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                                    </span>
                                                    <span data-tooltip="Print" data-value="sales_assigned_leads_print">
                                                        <a href="javascript:void(0);" class="ml-1" id="sales_assigned_leads_print" data-title="Assigned Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-9">
                                                    <h6 class="font_style">2. Quotation Sent Report</h6>
                                                </div>
                                                <div class="col-3">
                                                    <span data-tooltip="Export to PDF" data-value="sales_quotation_sent_pdf">
                                                        <a href="javascript:void(0);" id="sales_quotation_sent_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                        </a>
                                                    </span>
                                                    <span data-tooltip="Export to Excel" data-value="sales_quotation_sent_excel">
                                                        <a href="javascript:void(0);" class="ml-1" id="sales_quotation_sent_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                                    </span>
                                                    <span data-tooltip="Print" data-value="sales_quotation_sent_print">
                                                        <a href="javascript:void(0);" data-title="Quotation Sent" class="ml-1" id="sales_quotation_sent_print" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row  mb-1">
                                                <div class="col-9">
                                                    <h6 class="font_style">3. Quotation Finalized Report</h6>
                                                </div>
                                                <div class="col-3">
                                                    <span data-tooltip="Export to PDF" data-value="sales_quotation_finalized_pdf">
                                                        <a href="javascript:void(0);" id="sales_quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                        </a>
                                                    </span>
                                                    <span data-tooltip="Export to Excel" data-value="sales_quotation_finalized_excel">
                                                        <a href="javascript:void(0);" class="ml-1" id="sales_quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                                    </span>
                                                    <span data-tooltip="Print" data-value="sales_quotation_finalized_print">
                                                        <a href="javascript:void(0);" class="ml-1" id="sales_quotation_finalized_print" data-title="Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-9">
                                                    <h6 class="font_style">4. Invoices against Quotation</h6>
                                                </div>
                                                <div class="col-3">
                                                    <span data-tooltip="Export to PDF" data-value="sales_invoice_against_quotation_pdf">
                                                        <a href="javascript:void(0);" id="sales_invoice_against_quotation_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                        </a>
                                                    </span>
                                                    <span data-tooltip="Export to Excel" data-value="sales_invoice_against_quotation_excel">
                                                        <a href="javascript:void(0);" class="ml-1" id="sales_invoice_against_quotation_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                                    </span>
                                                    <span data-tooltip="Print" data-value="sales_invoice_against_quotation_print">
                                                        <a href="javascript:void(0);" class="ml-1" id="sales_invoice_against_quotation_print" data-title="Invoice against Quotation" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        {{-- vendor scripts --}}
        @section('vendor-scripts')
        <!-- <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        @endsection
        {{-- page scripts --}}
        @section('page-scripts')
        <script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
        <script src="{{asset('js/scripts/pages/get_report.js')}}"></script>
        @endsection