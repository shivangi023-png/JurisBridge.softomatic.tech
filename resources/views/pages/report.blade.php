@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Reports')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{(asset('vendors/css/forms/select/select2.min.css'))}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
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

    .border_secondary {
        border: 1px solid #dfe3e7 !important;
    }

    a.disabled {
        pointer-events: none;
        cursor: default;
    }

    .scrollable_card {
        width: 100%;
        height: 400px;
        overflow-y: scroll;
    }

    .btn {
        border-radius: 0px !important;
    }

    .btn-outline-dark {
        border: 0px !important;
        border-bottom: 1px solid #000000 !important;
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
                            </div>
                        </div>
                        <div class="error_message text-danger my-2"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 pr-2">
                        <div class="card">
                            <a href="#quotationTab" class="quotationScroll">
                                <div class=" card-header border bg-primary quotation_report text-white">
                                    <i class="bx bx-file bx_icon mr-2"></i> <strong class="ml-2">Quotation
                                        Reports</strong>
                                </div>
                            </a>
                            <a href="#expenseTab" class="expenseScroll">
                                <div class="card-header border mt-1 expense_report text-dark">
                                    <i class="bx bx-money bx_icon"></i> <strong class="ml-2">Expense
                                        Reports</strong>
                                </div>
                            </a>
                            <a href="#clientTab" class="clientScroll">
                                <div class="card-header border mt-1 client_report text-dark">
                                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Client
                                        Reports</strong>
                                </div>
                            </a>
                            <a href="#attendanceTab" class="attendanceScroll">
                                <div class="card-header border mt-1 attendance_report text-dark">
                                    <!-- <i class="bx bx-receipt bx_icon"></i> -->
                                    <span><img src="{{asset('images/icon/attendance-report.svg')}}" width=16px; height="17px;" class="bx_icon attendance_img1">
                                        <img src="{{asset('images/icon/white_attendance-report.svg')}}" width=16px; height="17px;" class="bx_icon attendance_img2" style="display:none;">
                                        <strong class="ml-2">Attendance
                                            Reports</strong></span>
                                </div>
                            </a>
                            <a href="#accountingTab" class="accountingScroll">
                                <div class="card-header border mt-1 accounting_report text-dark">
                                    <i class="bx bx-spreadsheet bx_icon"></i>
                                    <strong class="ml-2">Accounting
                                        Reports </strong>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="scrollable_card">
                            <div class="card border_primary quotation_card" id="quotationTab">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">1. Quotation Sent Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="quotation_sent_pdf">
                                                <a href="javascript:void(0);" class="quotation_sent_pdf"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="quotation_sent_excel">
                                                <a href="javascript:void(0);" class="ml-1 quotation_sent_excel"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="quotation_sent_print">
                                                <a href="javascript:void(0);" class="ml-1 quotation_sent_print"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">2. Quotations Finalized Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="quotation_finalized_pdf">
                                                <a href="javascript:void(0);" class="quotation_finalized_pdf"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="quotation_finalized_excel">
                                                <a href="javascript:void(0);" class="ml-1 quotation_finalized_excel"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="quotation_finalized_print">
                                                <a href="javascript:void(0);" class="ml-1 quotation_finalized_print"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">3. Quotation by Sales Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="quotation_by_sales_pdf">
                                                <a href="javascript:void(0);" class="quotation_by_sales_pdf"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="quotation_by_sales_excel">
                                                <a href="javascript:void(0);" class="ml-1 quotation_by_sales_excel"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="quotation_by_sales_print">
                                                <a href="javascript:void(0);" class="ml-1 quotation_by_sales_print"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">4. Quotation by Office Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="quotation_by_office_pdf">
                                                <a href="javascript:void(0);" class="quotation_by_office_pdf"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="quotation_by_office_excel">
                                                <a href="javascript:void(0);" class="ml-1 quotation_by_office_excel"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="quotation_by_office_print">
                                                <a href="javascript:void(0);" class="ml-1 quotation_by_office_print"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">5. Servicewise Quotations Sent Report
                                            </h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="servicewise_quotation_sent_pdf">
                                                <a href="javascript:void(0);" class="servicewise_quotation_sent_pdf"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="servicewise_quotation_sent_excel">
                                                <a href="javascript:void(0);" class="ml-1 servicewise_quotation_sent_excel"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="servicewise_quotation_sent_print">
                                                <a href="javascript:void(0);" class="ml-1 servicewise_quotation_sent_print"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">6. Servicewise Quotations Finalized
                                                Report
                                            </h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="servicewise_quotation_finalized_pdf">
                                                <a href="javascript:void(0);" class="servicewise_quotation_finalized_pdf"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="servicewise_quotation_finalized_excel">
                                                <a href="javascript:void(0);" class="ml-1 servicewise_quotation_finalized_excel"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="servicewise_quotation_finalized_print">
                                                <a href="javascript:void(0);" class="ml-1 servicewise_quotation_finalized_print"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h6 class="font_style">7. Clientwise Quotations Finalized
                                                Report
                                            </h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="clientwise_quotation_finalized_pdf">
                                                <a href="javascript:void(0);" class="clientwise_quotation_finalized_pdf"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="clientwise_quotation_finalized_excel">
                                                <a href="javascript:void(0);" class="ml-1 clientwise_quotation_finalized_excel"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="clientwise_quotation_finalized_print">
                                                <a href="javascript:void(0);" class="ml-1 clientwise_quotation_finalized_print"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border_secondary mt-2 expense_card" id="expenseTab">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">1. Expense Report - Staffwise</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="expense_report_staffwise_pdf">
                                                <a href="javascript:void(0);" class="expense_report_staffwise_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="expense_report_staffwise_excel">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_staffwise_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="expense_report_staffwise_print">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_staffwise_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">2. Expense Report - Ledgerwise</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="expense_report_ledgerwise_pdf">
                                                <a href="javascript:void(0);" class="expense_report_ledgerwise_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="expense_report_ledgerwise_excel">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_ledgerwise_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <spa data-tooltip="Print" data-value="expense_report_ledgerwise_print">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_ledgerwise_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">3. Expense Report - Clientwise</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="expense_report_clientwise_pdf">
                                                <a href="javascript:void(0);" class="expense_report_clientwise_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="expense_report_clientwise_excel">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_clientwise_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="expense_report_clientwise_print">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_clientwise_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">4. Expense Report - Reimbursement</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="expense_report_reimbursement_pdf">
                                                <a href="javascript:void(0);" class="expense_report_reimbursement_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="expense_report_reimbursement_excel">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_reimbursement_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span><span data-tooltip="Print" data-value="expense_report_reimbursement_print">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_reimbursement_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">5. Expense Report - Client Ledgerwise
                                            </h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="expense_report_client_ledgerwise_pdf">
                                                <a href="javascript:void(0);" class="expense_report_client_ledgerwise_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="expense_report_client_ledgerwise_excel">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_client_ledgerwise_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="expense_report_client_ledgerwise_print">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_client_ledgerwise_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">6. Expense Report- Staff Ledgerwise</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="expense_report_staff_ledgerwise_pdf">
                                                <a href="javascript:void(0);" class="expense_report_staff_ledgerwise_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="expense_report_staff_ledgerwise_excel">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_staff_ledgerwise_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="expense_report_staff_ledgerwise_print">
                                                <a href="javascript:void(0);" class="ml-1 expense_report_staff_ledgerwise_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h6 class="font_style">7. Daily Expense Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="daily_expense_report_pdf">
                                                <a href="javascript:void(0);" class="daily_expense_report_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="daily_expense_report_excel">
                                                <a href="javascript:void(0);" class="ml-1 daily_expense_report_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="daily_expense_report_print">
                                                <a href="javascript:void(0);" class="ml-1 daily_expense_report_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border_secondary mt-2 client_card" id="clientTab">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">1. All Clients Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="all_clients_pdf">
                                                <a href="javascript:void(0);" class="all_clients_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="all_clients_excel">
                                                <a href="javascript:void(0);" class="ml-1 all_clients_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="all_clients_print">
                                                <a href="javascript:void(0);" class="ml-1 all_clients_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">2. All Leads Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="all_leads_pdf">
                                                <a href="javascript:void(0);" class="all_leads_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="all_leads_excel">
                                                <a href="javascript:void(0);" class="ml-1 all_leads_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="all_leads_print">
                                                <a href="javascript:void(0);" class="ml-1 all_leads_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">3. Leads By Sales Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="leads_by_sales_pdf">
                                                <a href="javascript:void(0);" class="leads_by_sales_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="leads_by_sales_excel">
                                                <a href="javascript:void(0);" class="ml-1 leads_by_sales_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="leads_by_sales_print">
                                                <a href="javascript:void(0);" class="ml-1 leads_by_sales_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">4. Other Leads Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="other_leads_pdf">
                                                <a href="javascript:void(0);" class="other_leads_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="other_leads_excel">
                                                <a href="javascript:void(0);" class="ml-1 other_leads_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="other_leads_print">
                                                <a href="javascript:void(0);" class="ml-1 other_leads_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">5. Client Follow-Up Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="client_followup_pdf">
                                                <a href="javascript:void(0);" class="client_followup_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="client_followup_excel">
                                                <a href="javascript:void(0);" class="ml-1 client_followup_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="client_followup_print">
                                                <a href="javascript:void(0);" class="ml-1 client_followup_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">6. Client Not Follow-Up Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="client_not_followup_pdf">
                                                <a href="javascript:void(0);" class="client_not_followup_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="client_not_followup_excel">
                                                <a href="javascript:void(0);" class="ml-1 client_not_followup_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="client_not_followup_print">
                                                <a href="javascript:void(0);" class="ml-1 client_not_followup_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">7. Client With No Email Id Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="client_no_email_pdf">
                                                <a href="javascript:void(0);" class="client_no_email_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="client_no_email_excel">
                                                <a href="javascript:void(0);" class="ml-1 client_no_email_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="client_no_email_print">
                                                <a href="javascript:void(0);" class="ml-1 client_no_email_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">8. Client With No Contact Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="client_no_contact_pdf">
                                                <a href="javascript:void(0);" class="client_no_contact_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="client_no_contact_excel">
                                                <a href="javascript:void(0);" class="ml-1 client_no_contact_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="client_no_contact_print">
                                                <a href="javascript:void(0);" class="ml-1 client_no_contact_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h6 class="font_style">9. Daily Sales Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="daily_sales_pdf">
                                                <a href="javascript:void(0);" class="daily_sales_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="daily_sales_excel">
                                                <a href="javascript:void(0);" class="ml-1 daily_sales_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="daily_sales_print">
                                                <a href="javascript:void(0);" class="ml-1 daily_sales_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border_secondary mt-2 attendance_card" id="attendanceTab">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <h6 class="font_style">1. Attendance Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="staff_attendance_pdf">
                                                <a href="javascript:void(0);" class="staff_attendance_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="staff_attendance_excel">
                                                <a href="javascript:void(0);" class="ml-1 staff_attendance_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="staff_attendance_print">
                                                <a href="javascript:void(0);" class="ml-1 staff_attendance_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border_secondary mt-2 accounting_card" id="accountingTab">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">1. Invoices against Quotation</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="invoice_against_quotation_pdf">
                                                <a href="javascript:void(0);" class="invoice_against_quotation_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="invoice_against_quotation_excel">
                                                <a href="javascript:void(0);" class="ml-1 invoice_against_quotation_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="invoice_against_quotation_print">
                                                <a href="javascript:void(0);" class="ml-1 invoice_against_quotation_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">2. Additional Invoices</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="additional_invoices_pdf">
                                                <a href="javascript:void(0);" class="additional_invoices_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="additional_invoices_excel">
                                                <a href="javascript:void(0);" class="ml-1 additional_invoices_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="additional_invoices_print">
                                                <a href="javascript:void(0);" class="ml-1 additional_invoices_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">3. Cancelled Invoices</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="cancelled_invoice_pdf">
                                                <a href="javascript:void(0);" class="cancelled_invoice_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="cancelled_invoice_excel">
                                                <a href="javascript:void(0);" class="ml-1 cancelled_invoice_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="cancelled_invoice_print">
                                                <a href="javascript:void(0);" class="ml-1 cancelled_invoice_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">4. Consultation Fee Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="consultation_fee_pdf">
                                                <a href="javascript:void(0);" class="consultation_fee_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="consultation_fee_excel">
                                                <a href="javascript:void(0);" class="ml-1 consultation_fee_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="consultation_fee_print">
                                                <a href="javascript:void(0);" class="ml-1 consultation_fee_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-9">
                                            <h6 class="font_style">5. Invoices/Payment Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="billwise_payment_pdf">
                                                <a href="javascript:void(0);" class="billwise_payment_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="billwise_payment_excel">
                                                <a href="javascript:void(0);" class="ml-1 billwise_payment_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="billwise_payment_print">
                                                <a href="javascript:void(0);" class="ml-1 billwise_payment_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-9">
                                            <h6 class="font_style">6. Clientwise TDS Report</h6>
                                        </div>
                                        <div class="col-3">
                                            <span data-tooltip="Export to PDF" data-value="clientwise_tds_pdf">
                                                <a href="javascript:void(0);" class="clientwise_tds_pdf disabled"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                </a>
                                            </span>
                                            <span data-tooltip="Export to Excel" data-value="clientwise_tds_excel">
                                                <a href="javascript:void(0);" class="ml-1 clientwise_tds_excel disabled"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                            </span>
                                            <span data-tooltip="Print" data-value="clientwise_tds_print">
                                                <a href="javascript:void(0);" class="ml-1 clientwise_tds_print disabled"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                            </span>
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
</div>

@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
    $(document).ready(function() {
        const d = new Date(),
            m = d.getMonth() + 1,
            y = d.getFullYear();

        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        var curMonth = d.getMonth();

        var fiscalYr = "";
        if (curMonth >= 3) {
            var nextYr1 = (d.getFullYear() + 1).toString();
            fiscalYr = d.getFullYear().toString() + "-" + nextYr1;
        } else {
            var nextYr2 = d.getFullYear().toString();
            fiscalYr = (d.getFullYear() - 1).toString() + "-" + nextYr2;
        }

        var month = monthNames[m - 1];

        var year = fiscalYr;

        var quarter = Math.floor((d.getMonth() + 3) / 3);
        if (quarter == 1) {
            data_display = 'Fourth Quarter';
            var qq = $('.quarter_filter').closest('.quarter-input').find('.quarter-text').text(data_display);
        }

        if (quarter == 2) {
            data_display = 'First Quarter';
            var qq = $('.quarter_filter').closest('.quarter-input').find('.quarter-text').text(data_display);
        }

        if (quarter == 3) {
            data_display = 'Second Quarter';
            var qq = $('.quarter_filter').closest('.quarter-input').find('.quarter-text').text(data_display);
        }

        if (quarter == 4) {
            data_display = 'Third Quarter';
            var qq = $('.quarter_filter').closest('.quarter-input').find('.quarter-text').text(data_display);
        }
        var mm = $('.month_filter').closest('.month-input').find('.month-text').text(month);
        var yy = $('.year_filter').closest('.year-input').find('.year-text').text(year);


        $('.expenseScroll').on('click', function(e) {
            scrolled = scrolled - 300;
            $(".expense_card").animate({
                scrollTop: scrolled
            });
        });

        $('.clientScroll').on('click', function(e) {
            scrolled = scrolled - 300;
            $(".client_card").animate({
                scrollTop: scrolled
            });
        });

        $('.attendanceScroll').on('click', function(e) {
            scrolled = scrolled - 300;
            $(".attendance_card").animate({
                scrollTop: scrolled
            });
        });

        $('.accountingScroll').on('click', function(e) {
            scrolled = scrolled - 300;
            $(".accounting_card").animate({
                scrollTop: scrolled
            });
        });

        $(".expense_report").click(function() {
            $('.attendance_img1').css("display", "block");
            $('.attendance_img2').css("display", "none");
            if ($(".quotation_report").hasClass('bg-primary')) {
                $(".quotation_report").removeClass("bg-primary").addClass("text-dark");
                $('.quotation_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".client_report").hasClass('bg-primary')) {
                $(".client_report").removeClass("bg-primary").addClass("text-dark");
                $('.client_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".attendance_report").hasClass('bg-primary')) {
                $(".attendance_report").removeClass("bg-primary").addClass("text-dark");
                $('.attendance_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".accounting_report").hasClass('bg-primary')) {
                $(".accounting_report").removeClass("bg-primary").addClass("text-dark");
                $('.accounting_card').removeClass('border_primary').addClass("border_secondary");
            }

            $(this).addClass("bg-primary").removeClass("text-dark").addClass("text-white");
            $('.expense_card').removeClass('border_secondary').addClass("border_primary");

            $('.expense_report_staffwise_excel').removeClass('disabled');
            $('.expense_report_staffwise_pdf').removeClass('disabled');
            $('.expense_report_staffwise_print').removeClass('disabled');

            $('.expense_report_ledgerwise_excel').removeClass('disabled');
            $('.expense_report_ledgerwise_pdf').removeClass('disabled');
            $('.expense_report_ledgerwise_print').removeClass('disabled');

            $('.expense_report_clientwise_excel').removeClass('disabled');
            $('.expense_report_clientwise_pdf').removeClass('disabled');
            $('.expense_report_clientwise_print').removeClass('disabled');

            $('.expense_report_staff_ledgerwise_excel').removeClass('disabled');
            $('.expense_report_staff_ledgerwise_pdf').removeClass('disabled');
            $('.expense_report_staff_ledgerwise_print').removeClass('disabled');

            $('.expense_report_client_ledgerwise_excel').removeClass('disabled');
            $('.expense_report_client_ledgerwise_pdf').removeClass('disabled');
            $('.expense_report_client_ledgerwise_print').removeClass('disabled');

            $('.expense_report_reimbursement_excel').removeClass('disabled');
            $('.expense_report_reimbursement_pdf').removeClass('disabled');
            $('.expense_report_reimbursement_print').removeClass('disabled');

            $('.daily_expense_report_excel').removeClass('disabled');
            $('.daily_expense_report_pdf').removeClass('disabled');
            $('.daily_expense_report_print').removeClass('disabled');

            $('.all_clients_excel').addClass('disabled');
            $('.all_clients_pdf').addClass('disabled');
            $('.all_clients_print').addClass('disabled');

            $('.all_leads_excel').addClass('disabled');
            $('.all_leads_pdf').addClass('disabled');
            $('.all_leads_print').addClass('disabled');

            $('.leads_by_sales_excel').addClass('disabled');
            $('.leads_by_sales_pdf').addClass('disabled');
            $('.leads_by_sales_print').addClass('disabled');

            $('.other_leads_excel').addClass('disabled');
            $('.other_leads_pdf').addClass('disabled');
            $('.other_leads_print').addClass('disabled');

            $('.quotation_sent_excel').addClass('disabled');
            $('.quotation_sent_pdf').addClass('disabled');
            $('.quotation_sent_print').addClass('disabled');

            $('.quotation_finalized_excel').addClass('disabled');
            $('.quotation_finalized_pdf').addClass('disabled');
            $('.quotation_finalized_print').addClass('disabled');

            $('.quotation_by_sales_excel').addClass('disabled');
            $('.quotation_by_sales_pdf').addClass('disabled');
            $('.quotation_by_sales_print').addClass('disabled');

            $('.quotation_by_office_excel').addClass('disabled');
            $('.quotation_by_office_pdf').addClass('disabled');
            $('.quotation_by_office_print').addClass('disabled');

            $('.servicewise_quotation_sent_excel').addClass('disabled');
            $('.servicewise_quotation_sent_pdf').addClass('disabled');
            $('.servicewise_quotation_sent_print').addClass('disabled');

            $('.servicewise_quotation_finalized_excel').addClass('disabled');
            $('.servicewise_quotation_finalized_pdf').addClass('disabled');
            $('.servicewise_quotation_finalized_print').addClass('disabled');

            $('.clientwise_quotation_finalized_excel').addClass('disabled');
            $('.clientwise_quotation_finalized_pdf').addClass('disabled');
            $('.clientwise_quotation_finalized_print').addClass('disabled');

            $('.consultation_fee_excel').addClass('disabled');
            $('.consultation_fee_pdf').addClass('disabled');
            $('.consultation_fee_print').addClass('disabled');

            $('.client_followup_excel').addClass('disabled');
            $('.client_followup_pdf').addClass('disabled');
            $('.client_followup_print').addClass('disabled');

            $('.client_not_followup_excel').addClass('disabled');
            $('.client_not_followup_pdf').addClass('disabled');
            $('.client_not_followup_print').addClass('disabled');

            $('.client_no_email_excel').addClass('disabled');
            $('.client_no_email_pdf').addClass('disabled');
            $('.client_no_email_print').addClass('disabled');

            $('.client_no_contact_excel').addClass('disabled');
            $('.client_no_contact_pdf').addClass('disabled');
            $('.client_no_contact_print').addClass('disabled');

            $('.daily_sales_excel').addClass('disabled');
            $('.daily_sales_pdf').addClass('disabled');
            $('.daily_sales_print').addClass('disabled');

            $('.staff_attendance_excel').addClass('disabled');
            $('.staff_attendance_pdf').addClass('disabled');
            $('.staff_attendance_print').addClass('disabled');

            $('.invoice_against_quotation_excel').addClass('disabled');
            $('.invoice_against_quotation_pdf').addClass('disabled');
            $('.invoice_against_quotation_print').addClass('disabled');

            $('.additional_invoices_excel').addClass('disabled');
            $('.additional_invoices_pdf').addClass('disabled');
            $('.additional_invoices_print').addClass('disabled');

            $('.cancelled_invoice_excel').addClass('disabled');
            $('.cancelled_invoice_pdf').addClass('disabled');
            $('.cancelled_invoice_print').addClass('disabled');

            $('.billwise_payment_excel').addClass('disabled');
            $('.billwise_payment_pdf').addClass('disabled');
            $('.billwise_payment_print').addClass('disabled');

            $('.clientwise_tds_excel').addClass('disabled');
            $('.clientwise_tds_pdf').addClass('disabled');
            $('.clientwise_tds_print').addClass('disabled');
        });

        $(".quotation_report").click(function() {
            $('.attendance_img1').css("display", "block");
            $('.attendance_img2').css("display", "none");
            if ($(".expense_report").hasClass('bg-primary')) {
                $(".expense_report").removeClass("bg-primary").addClass("text-dark");
                $('.expense_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".client_report").hasClass('bg-primary')) {
                $(".client_report").removeClass("bg-primary").addClass("text-dark");
                $('.client_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".attendance_report").hasClass('bg-primary')) {
                $(".attendance_report").removeClass("bg-primary").addClass("text-dark");
                $('.attendance_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".accounting_report").hasClass('bg-primary')) {
                $(".accounting_report").removeClass("bg-primary").addClass("text-dark");
                $('.accounting_card').removeClass('border_primary').addClass("border_secondary");
            }

            $(this).addClass("bg-primary").removeClass("text-dark").addClass("text-white");
            $('.quotation_card').removeClass('border_secondary').addClass("border_primary");

            $('.expense_report_staffwise_excel').addClass('disabled');
            $('.expense_report_staffwise_pdf').addClass('disabled');
            $('.expense_report_staffwise_print').addClass('disabled');

            $('.expense_report_ledgerwise_excel').addClass('disabled');
            $('.expense_report_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_ledgerwise_print').addClass('disabled');

            $('.expense_report_clientwise_excel').addClass('disabled');
            $('.expense_report_clientwise_pdf').addClass('disabled');
            $('.expense_report_clientwise_print').addClass('disabled');

            $('.expense_report_staff_ledgerwise_excel').addClass('disabled');
            $('.expense_report_staff_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_staff_ledgerwise_print').addClass('disabled');

            $('.expense_report_client_ledgerwise_excel').addClass('disabled');
            $('.expense_report_client_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_client_ledgerwise_print').addClass('disabled');

            $('.expense_report_reimbursement_excel').addClass('disabled');
            $('.expense_report_reimbursement_pdf').addClass('disabled');
            $('.expense_report_reimbursement_print').addClass('disabled');

            $('.daily_expense_report_excel').addClass('disabled');
            $('.daily_expense_report_pdf').addClass('disabled');
            $('.daily_expense_report_print').addClass('disabled');

            $('.all_clients_excel').addClass('disabled');
            $('.all_clients_pdf').addClass('disabled');
            $('.all_clients_print').addClass('disabled');

            $('.all_leads_excel').addClass('disabled');
            $('.all_leads_pdf').addClass('disabled');
            $('.all_leads_print').addClass('disabled');

            $('.leads_by_sales_excel').addClass('disabled');
            $('.leads_by_sales_pdf').addClass('disabled');
            $('.leads_by_sales_print').addClass('disabled');

            $('.other_leads_excel').addClass('disabled');
            $('.other_leads_pdf').addClass('disabled');
            $('.other_leads_print').addClass('disabled');

            $('.consultation_fee_excel').addClass('disabled');
            $('.consultation_fee_pdf').addClass('disabled');
            $('.consultation_fee_print').addClass('disabled');

            $('.client_followup_excel').addClass('disabled');
            $('.client_followup_pdf').addClass('disabled');
            $('.client_followup_print').addClass('disabled');

            $('.client_not_followup_excel').addClass('disabled');
            $('.client_not_followup_pdf').addClass('disabled');
            $('.client_not_followup_print').addClass('disabled');

            $('.client_no_email_excel').addClass('disabled');
            $('.client_no_email_pdf').addClass('disabled');
            $('.client_no_email_print').addClass('disabled');

            $('.client_no_contact_excel').addClass('disabled');
            $('.client_no_contact_pdf').addClass('disabled');
            $('.client_no_contact_print').addClass('disabled');

            $('.daily_sales_excel').addClass('disabled');
            $('.daily_sales_pdf').addClass('disabled');
            $('.daily_sales_print').addClass('disabled');

            $('.staff_attendance_excel').addClass('disabled');
            $('.staff_attendance_pdf').addClass('disabled');
            $('.staff_attendance_print').addClass('disabled');

            $('.invoice_against_quotation_excel').addClass('disabled');
            $('.invoice_against_quotation_pdf').addClass('disabled');
            $('.invoice_against_quotation_print').addClass('disabled');

            $('.additional_invoices_excel').addClass('disabled');
            $('.additional_invoices_pdf').addClass('disabled');
            $('.additional_invoices_print').addClass('disabled');

            $('.cancelled_invoice_excel').addClass('disabled');
            $('.cancelled_invoice_pdf').addClass('disabled');
            $('.cancelled_invoice_print').addClass('disabled');

            $('.billwise_payment_excel').addClass('disabled');
            $('.billwise_payment_pdf').addClass('disabled');
            $('.billwise_payment_print').addClass('disabled');

            $('.clientwise_tds_excel').addClass('disabled');
            $('.clientwise_tds_pdf').addClass('disabled');
            $('.clientwise_tds_print').addClass('disabled');

            $('.quotation_sent_excel').removeClass('disabled');
            $('.quotation_sent_pdf').removeClass('disabled');
            $('.quotation_sent_print').removeClass('disabled');

            $('.quotation_finalized_excel').removeClass('disabled');
            $('.quotation_finalized_pdf').removeClass('disabled');
            $('.quotation_finalized_print').removeClass('disabled');

            $('.quotation_by_sales_excel').removeClass('disabled');
            $('.quotation_by_sales_pdf').removeClass('disabled');
            $('.quotation_by_sales_print').removeClass('disabled');

            $('.quotation_by_office_excel').removeClass('disabled');
            $('.quotation_by_office_pdf').removeClass('disabled');
            $('.quotation_by_office_print').removeClass('disabled');

            $('.servicewise_quotation_sent_excel').removeClass('disabled');
            $('.servicewise_quotation_sent_pdf').removeClass('disabled');
            $('.servicewise_quotation_sent_print').removeClass('disabled');

            $('.servicewise_quotation_finalized_excel').removeClass('disabled');
            $('.servicewise_quotation_finalized_pdf').removeClass('disabled');
            $('.servicewise_quotation_finalized_print').removeClass('disabled');

            $('.clientwise_quotation_finalized_excel').removeClass('disabled');
            $('.clientwise_quotation_finalized_pdf').removeClass('disabled');
            $('.clientwise_quotation_finalized_print').removeClass('disabled');
        });

        $(".client_report").click(function() {
            $('.attendance_img1').css("display", "block");
            $('.attendance_img2').css("display", "none");
            if ($(".expense_report").hasClass('bg-primary')) {
                $(".expense_report").removeClass("bg-primary").addClass("text-dark");
                $('.expense_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".quotation_report").hasClass('bg-primary')) {
                $(".quotation_report").removeClass("bg-primary").addClass("text-dark");
                $('.quotation_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".attendance_report").hasClass('bg-primary')) {
                $(".attendance_report").removeClass("bg-primary").addClass("text-dark");
                $('.attendance_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".accounting_report").hasClass('bg-primary')) {
                $(".accounting_report").removeClass("bg-primary").addClass("text-dark");
                $('.accounting_card').removeClass('border_primary').addClass("border_secondary");
            }

            $(this).addClass("bg-primary").removeClass("text-dark").addClass("text-white");
            $('.client_card').removeClass('border_secondary').addClass("border_primary");

            $('.expense_report_staffwise_excel').addClass('disabled');
            $('.expense_report_staffwise_pdf').addClass('disabled');
            $('.expense_report_staffwise_print').addClass('disabled');

            $('.expense_report_ledgerwise_excel').addClass('disabled');
            $('.expense_report_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_ledgerwise_print').addClass('disabled');

            $('.expense_report_clientwise_excel').addClass('disabled');
            $('.expense_report_clientwise_pdf').addClass('disabled');
            $('.expense_report_clientwise_print').addClass('disabled');

            $('.expense_report_staff_ledgerwise_excel').addClass('disabled');
            $('.expense_report_staff_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_staff_ledgerwise_print').addClass('disabled');

            $('.expense_report_client_ledgerwise_excel').addClass('disabled');
            $('.expense_report_client_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_client_ledgerwise_print').addClass('disabled');

            $('.expense_report_reimbursement_excel').addClass('disabled');
            $('.expense_report_reimbursement_pdf').addClass('disabled');
            $('.expense_report_reimbursement_print').addClass('disabled');

            $('.daily_expense_report_excel').addClass('disabled');
            $('.daily_expense_report_pdf').addClass('disabled');
            $('.daily_expense_report_print').addClass('disabled');

            $('.quotation_sent_excel').addClass('disabled');
            $('.quotation_sent_pdf').addClass('disabled');
            $('.quotation_sent_print').addClass('disabled');

            $('.quotation_finalized_excel').addClass('disabled');
            $('.quotation_finalized_pdf').addClass('disabled');
            $('.quotation_finalized_print').addClass('disabled');

            $('.quotation_by_sales_excel').addClass('disabled');
            $('.quotation_by_sales_pdf').addClass('disabled');
            $('.quotation_by_sales_print').addClass('disabled');

            $('.quotation_by_office_excel').addClass('disabled');
            $('.quotation_by_office_pdf').addClass('disabled');
            $('.quotation_by_office_print').addClass('disabled');

            $('.servicewise_quotation_sent_excel').addClass('disabled');
            $('.servicewise_quotation_sent_pdf').addClass('disabled');
            $('.servicewise_quotation_sent_print').addClass('disabled');

            $('.servicewise_quotation_finalized_excel').addClass('disabled');
            $('.servicewise_quotation_finalized_pdf').addClass('disabled');
            $('.servicewise_quotation_finalized_print').addClass('disabled');

            $('.clientwise_quotation_finalized_excel').addClass('disabled');
            $('.clientwise_quotation_finalized_pdf').addClass('disabled');
            $('.clientwise_quotation_finalized_print').addClass('disabled');

            $('.staff_attendance_excel').addClass('disabled');
            $('.staff_attendance_pdf').addClass('disabled');
            $('.staff_attendance_print').addClass('disabled');

            $('.invoice_against_quotation_excel').addClass('disabled');
            $('.invoice_against_quotation_pdf').addClass('disabled');
            $('.invoice_against_quotation_print').addClass('disabled');

            $('.additional_invoices_excel').addClass('disabled');
            $('.additional_invoices_pdf').addClass('disabled');
            $('.additional_invoices_print').addClass('disabled');

            $('.cancelled_invoice_excel').addClass('disabled');
            $('.cancelled_invoice_pdf').addClass('disabled');
            $('.cancelled_invoice_print').addClass('disabled');

            $('.consultation_fee_excel').addClass('disabled');
            $('.consultation_fee_pdf').addClass('disabled');
            $('.consultation_fee_print').addClass('disabled');

            $('.billwise_payment_excel').addClass('disabled');
            $('.billwise_payment_pdf').addClass('disabled');
            $('.billwise_payment_print').addClass('disabled');

            $('.clientwise_tds_excel').addClass('disabled');
            $('.clientwise_tds_pdf').addClass('disabled');
            $('.clientwise_tds_print').addClass('disabled');

            $('.all_clients_excel').removeClass('disabled');
            $('.all_clients_pdf').removeClass('disabled');
            $('.all_clients_print').removeClass('disabled');

            $('.all_leads_excel').removeClass('disabled');
            $('.all_leads_pdf').removeClass('disabled');
            $('.all_leads_print').removeClass('disabled');

            $('.leads_by_sales_excel').removeClass('disabled');
            $('.leads_by_sales_pdf').removeClass('disabled');
            $('.leads_by_sales_print').removeClass('disabled');

            $('.other_leads_excel').removeClass('disabled');
            $('.other_leads_pdf').removeClass('disabled');
            $('.other_leads_print').removeClass('disabled');

            $('.client_followup_excel').removeClass('disabled');
            $('.client_followup_pdf').removeClass('disabled');
            $('.client_followup_print').removeClass('disabled');

            $('.client_not_followup_excel').removeClass('disabled');
            $('.client_not_followup_pdf').removeClass('disabled');
            $('.client_not_followup_print').removeClass('disabled');

            $('.client_no_email_excel').removeClass('disabled');
            $('.client_no_email_pdf').removeClass('disabled');
            $('.client_no_email_print').removeClass('disabled');

            $('.client_no_contact_excel').removeClass('disabled');
            $('.client_no_contact_pdf').removeClass('disabled');
            $('.client_no_contact_print').removeClass('disabled');

            $('.daily_sales_excel').removeClass('disabled');
            $('.daily_sales_pdf').removeClass('disabled');
            $('.daily_sales_print').removeClass('disabled');
        });

        $(".attendance_report").click(function() {
            $('.attendance_img1').css("display", "none");
            $('.attendance_img2').css("display", "block");
            if ($(".expense_report").hasClass('bg-primary')) {
                $(".expense_report").removeClass("bg-primary").addClass("text-dark");
                $('.expense_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".quotation_report").hasClass('bg-primary')) {
                $(".quotation_report").removeClass("bg-primary").addClass("text-dark");
                $('.quotation_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".client_report").hasClass('bg-primary')) {
                $(".client_report").removeClass("bg-primary").addClass("text-dark");
                $('.client_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".accounting_report").hasClass('bg-primary')) {
                $(".accounting_report").removeClass("bg-primary").addClass("text-dark");
                $('.accounting_card').removeClass('border_primary').addClass("border_secondary");
            }

            $(this).addClass("bg-primary").removeClass("text-dark").addClass("text-white");
            $('.attendance_card').removeClass('border_secondary').addClass("border_primary");


            $('.expense_report_staffwise_excel').addClass('disabled');
            $('.expense_report_staffwise_pdf').addClass('disabled');
            $('.expense_report_staffwise_print').addClass('disabled');

            $('.expense_report_ledgerwise_excel').addClass('disabled');
            $('.expense_report_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_ledgerwise_print').addClass('disabled');

            $('.expense_report_clientwise_excel').addClass('disabled');
            $('.expense_report_clientwise_pdf').addClass('disabled');
            $('.expense_report_clientwise_print').addClass('disabled');

            $('.expense_report_staff_ledgerwise_excel').addClass('disabled');
            $('.expense_report_staff_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_staff_ledgerwise_print').addClass('disabled');

            $('.expense_report_client_ledgerwise_excel').addClass('disabled');
            $('.expense_report_client_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_client_ledgerwise_print').addClass('disabled');

            $('.expense_report_reimbursement_excel').addClass('disabled');
            $('.expense_report_reimbursement_pdf').addClass('disabled');
            $('.expense_report_reimbursement_print').addClass('disabled');

            $('.daily_expense_report_excel').addClass('disabled');
            $('.daily_expense_report_pdf').addClass('disabled');
            $('.daily_expense_report_print').addClass('disabled');

            $('.quotation_sent_excel').addClass('disabled');
            $('.quotation_sent_pdf').addClass('disabled');
            $('.quotation_sent_print').addClass('disabled');

            $('.quotation_finalized_excel').addClass('disabled');
            $('.quotation_finalized_pdf').addClass('disabled');
            $('.quotation_finalized_print').addClass('disabled');

            $('.quotation_by_sales_excel').addClass('disabled');
            $('.quotation_by_sales_pdf').addClass('disabled');
            $('.quotation_by_sales_print').addClass('disabled');

            $('.quotation_by_office_excel').addClass('disabled');
            $('.quotation_by_office_pdf').addClass('disabled');
            $('.quotation_by_office_print').addClass('disabled');

            $('.servicewise_quotation_sent_excel').addClass('disabled');
            $('.servicewise_quotation_sent_pdf').addClass('disabled');
            $('.servicewise_quotation_sent_print').addClass('disabled');

            $('.servicewise_quotation_finalized_excel').addClass('disabled');
            $('.servicewise_quotation_finalized_pdf').addClass('disabled');
            $('.servicewise_quotation_finalized_print').addClass('disabled');

            $('.clientwise_quotation_finalized_excel').addClass('disabled');
            $('.clientwise_quotation_finalized_pdf').addClass('disabled');
            $('.clientwise_quotation_finalized_print').addClass('disabled');

            $('.daily_sales_excel').addClass('disabled');
            $('.daily_sales_pdf').addClass('disabled');
            $('.daily_sales_print').addClass('disabled');

            $('.staff_attendance_excel').addClass('disabled');
            $('.staff_attendance_pdf').addClass('disabled');
            $('.staff_attendance_print').addClass('disabled');

            $('.all_clients_excel').addClass('disabled');
            $('.all_clients_pdf').addClass('disabled');
            $('.all_clients_print').addClass('disabled');

            $('.all_leads_excel').addClass('disabled');
            $('.all_leads_pdf').addClass('disabled');
            $('.all_leads_print').addClass('disabled');

            $('.leads_by_sales_excel').addClass('disabled');
            $('.leads_by_sales_pdf').addClass('disabled');
            $('.leads_by_sales_print').addClass('disabled');

            $('.other_leads_excel').addClass('disabled');
            $('.other_leads_pdf').addClass('disabled');
            $('.other_leads_print').addClass('disabled');

            $('.consultation_fee_excel').addClass('disabled');
            $('.consultation_fee_pdf').addClass('disabled');
            $('.consultation_fee_print').addClass('disabled');

            $('.client_followup_excel').addClass('disabled');
            $('.client_followup_pdf').addClass('disabled');
            $('.client_followup_print').addClass('disabled');

            $('.client_not_followup_excel').addClass('disabled');
            $('.client_not_followup_pdf').addClass('disabled');
            $('.client_not_followup_print').addClass('disabled');

            $('.client_no_email_excel').addClass('disabled');
            $('.client_no_email_pdf').addClass('disabled');
            $('.client_no_email_print').addClass('disabled');

            $('.client_no_contact_excel').addClass('disabled');
            $('.client_no_contact_pdf').addClass('disabled');
            $('.client_no_contact_print').addClass('disabled');

            $('.invoice_against_quotation_excel').addClass('disabled');
            $('.invoice_against_quotation_pdf').addClass('disabled');
            $('.invoice_against_quotation_print').addClass('disabled');

            $('.additional_invoices_excel').addClass('disabled');
            $('.additional_invoices_pdf').addClass('disabled');
            $('.additional_invoices_print').addClass('disabled');

            $('.cancelled_invoice_excel').addClass('disabled');
            $('.cancelled_invoice_pdf').addClass('disabled');
            $('.cancelled_invoice_print').addClass('disabled');

            $('.billwise_payment_excel').addClass('disabled');
            $('.billwise_payment_pdf').addClass('disabled');
            $('.billwise_payment_print').addClass('disabled');

            $('.clientwise_tds_excel').addClass('disabled');
            $('.clientwise_tds_pdf').addClass('disabled');
            $('.clientwise_tds_print').addClass('disabled');

            $('.staff_attendance_excel').removeClass('disabled');
            $('.staff_attendance_pdf').removeClass('disabled');
            $('.staff_attendance_print').removeClass('disabled');
        });

        $(".accounting_report").click(function() {
            $('.attendance_img1').css("display", "block");
            $('.attendance_img2').css("display", "none");
            if ($(".expense_report").hasClass('bg-primary')) {
                $(".expense_report").removeClass("bg-primary").addClass("text-dark");
                $('.expense_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".quotation_report").hasClass('bg-primary')) {
                $(".quotation_report").removeClass("bg-primary").addClass("text-dark");
                $('.quotation_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".client_report").hasClass('bg-primary')) {
                $(".client_report").removeClass("bg-primary").addClass("text-dark");
                $('.client_card').removeClass('border_primary').addClass("border_secondary");
            }

            if ($(".attendance_report").hasClass('bg-primary')) {
                $(".attendance_report").removeClass("bg-primary").addClass("text-dark");
                $('.attendance_card').removeClass('border_primary').addClass("border_secondary");
            }

            $(this).addClass("bg-primary").removeClass("text-dark").addClass("text-white");
            $('.accounting_card').removeClass('border_secondary').addClass("border_primary");


            $('.expense_report_staffwise_excel').addClass('disabled');
            $('.expense_report_staffwise_pdf').addClass('disabled');
            $('.expense_report_staffwise_print').addClass('disabled');

            $('.expense_report_ledgerwise_excel').addClass('disabled');
            $('.expense_report_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_ledgerwise_print').addClass('disabled');

            $('.expense_report_clientwise_excel').addClass('disabled');
            $('.expense_report_clientwise_pdf').addClass('disabled');
            $('.expense_report_clientwise_print').addClass('disabled');

            $('.expense_report_staff_ledgerwise_excel').addClass('disabled');
            $('.expense_report_staff_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_staff_ledgerwise_print').addClass('disabled');

            $('.expense_report_client_ledgerwise_excel').addClass('disabled');
            $('.expense_report_client_ledgerwise_pdf').addClass('disabled');
            $('.expense_report_client_ledgerwise_print').addClass('disabled');

            $('.expense_report_reimbursement_excel').addClass('disabled');
            $('.expense_report_reimbursement_pdf').addClass('disabled');
            $('.expense_report_reimbursement_print').addClass('disabled');

            $('.daily_expense_report_excel').addClass('disabled');
            $('.daily_expense_report_pdf').addClass('disabled');
            $('.daily_expense_report_print').addClass('disabled');

            $('.quotation_sent_excel').addClass('disabled');
            $('.quotation_sent_pdf').addClass('disabled');
            $('.quotation_sent_print').addClass('disabled');

            $('.quotation_finalized_excel').addClass('disabled');
            $('.quotation_finalized_pdf').addClass('disabled');
            $('.quotation_finalized_print').addClass('disabled');

            $('.quotation_by_sales_excel').addClass('disabled');
            $('.quotation_by_sales_pdf').addClass('disabled');
            $('.quotation_by_sales_print').addClass('disabled');

            $('.quotation_by_office_excel').addClass('disabled');
            $('.quotation_by_office_pdf').addClass('disabled');
            $('.quotation_by_office_print').addClass('disabled');

            $('.servicewise_quotation_sent_excel').addClass('disabled');
            $('.servicewise_quotation_sent_pdf').addClass('disabled');
            $('.servicewise_quotation_sent_print').addClass('disabled');

            $('.servicewise_quotation_finalized_excel').addClass('disabled');
            $('.servicewise_quotation_finalized_pdf').addClass('disabled');
            $('.servicewise_quotation_finalized_print').addClass('disabled');

            $('.clientwise_quotation_finalized_excel').addClass('disabled');
            $('.clientwise_quotation_finalized_pdf').addClass('disabled');
            $('.clientwise_quotation_finalized_print').addClass('disabled');

            $('.staff_attendance_excel').addClass('disabled');
            $('.staff_attendance_pdf').addClass('disabled');
            $('.staff_attendance_print').addClass('disabled');

            $('.all_clients_excel').addClass('disabled');
            $('.all_clients_pdf').addClass('disabled');
            $('.all_clients_print').addClass('disabled');

            $('.all_leads_excel').addClass('disabled');
            $('.all_leads_pdf').addClass('disabled');
            $('.all_leads_print').addClass('disabled');

            $('.leads_by_sales_excel').addClass('disabled');
            $('.leads_by_sales_pdf').addClass('disabled');
            $('.leads_by_sales_print').addClass('disabled');

            $('.other_leads_excel').addClass('disabled');
            $('.other_leads_pdf').addClass('disabled');
            $('.other_leads_print').addClass('disabled');

            $('.client_followup_excel').addClass('disabled');
            $('.client_followup_pdf').addClass('disabled');
            $('.client_followup_print').addClass('disabled');

            $('.client_not_followup_excel').addClass('disabled');
            $('.client_not_followup_pdf').addClass('disabled');
            $('.client_not_followup_print').addClass('disabled');

            $('.client_no_email_excel').addClass('disabled');
            $('.client_no_email_pdf').addClass('disabled');
            $('.client_no_email_print').addClass('disabled');

            $('.client_no_contact_excel').addClass('disabled');
            $('.client_no_contact_pdf').addClass('disabled');
            $('.client_no_contact_print').addClass('disabled');

            $('.daily_sales_excel').addClass('disabled');
            $('.daily_sales_pdf').addClass('disabled');
            $('.daily_sales_print').addClass('disabled');

            $('.invoice_against_quotation_excel').removeClass('disabled');
            $('.invoice_against_quotation_pdf').removeClass('disabled');
            $('.invoice_against_quotation_print').removeClass('disabled');

            $('.additional_invoices_excel').removeClass('disabled');
            $('.additional_invoices_pdf').removeClass('disabled');
            $('.additional_invoices_print').removeClass('disabled');

            $('.cancelled_invoice_excel').removeClass('disabled');
            $('.cancelled_invoice_pdf').removeClass('disabled');
            $('.cancelled_invoice_print').removeClass('disabled');

            $('.consultation_fee_excel').removeClass('disabled');
            $('.consultation_fee_pdf').removeClass('disabled');
            $('.consultation_fee_print').removeClass('disabled');

            $('.billwise_payment_excel').removeClass('disabled');
            $('.billwise_payment_pdf').removeClass('disabled');
            $('.billwise_payment_print').removeClass('disabled');

            $('.clientwise_tds_excel').removeClass('disabled');
            $('.clientwise_tds_pdf').removeClass('disabled');
            $('.clientwise_tds_print').removeClass('disabled');

            $('.staff_attendance_excel').removeClass('disabled');
            $('.staff_attendance_pdf').removeClass('disabled');
            $('.staff_attendance_print').removeClass('disabled');
        });
    });

    $(document).on("click", ".show_filter", function() {

        if ($(this).is(":checked")) {
            var value = $(this).val();
        }

        if (value == 'year_search') {
            $('.month-input').hide();
            $('.quarter-input').hide();
            $('.year-input').show();
        }

        if (value == 'month_year_search') {
            $('.month-input').show();
            $('.quarter-input').hide();
            $('.year-input').show();
        }

        if (value == 'quarter_year_search') {
            $('.month-input').hide();
            $('.quarter-input').show();
            $('.year-input').show();
        }

    });

    $(document).on('click', '.month_filter', function() {
        $(this).closest('.month-input').find('.month-text').text($(this).data('display'));
    });

    $(document).on('click', '.quarter_filter', function() {
        $(this).closest('.quarter-input').find('.quarter-text').text($(this).data('display'));
    });

    $(document).on('click', '.year_filter', function() {
        $(this).closest('.year-input').find('.year-text').text($(this).data('display'));
    });

    $(document).on('click', '.quotation_sent_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_sent_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();

                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);
                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_sent_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();

                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);
                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_sent_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();

                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_finalized_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_finalized_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_finalized_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_finalized_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_by_sales_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_sales_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_sales_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_sales_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }

    });

    $(document).on('click', '.quotation_by_office_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_office_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_office_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_office_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }

    });

    $(document).on('click', '.servicewise_quotation_sent_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_sent_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_sent_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_sent_pdf/' + year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.servicewise_quotation_finalized_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_finalized_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_finalized_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_finalized_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.clientwise_quotation_finalized_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var overlay = $(
            "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function() {
            $('#loading').remove();
        }, 10000);

        var link = document.createElement('a');
        link.href = $('#base_url').val() + '/clientwise_quotation_finalized_pdf';
        link.click();
    });

    $(document).on('click', '.expense_report_staffwise_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staffwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staffwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staffwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_ledgerwise_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_clientwise_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_clientwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_clientwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_clientwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_reimbursement_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_reimbursement_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_reimbursement_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_reimbursement_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_client_ledgerwise_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_client_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_client_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_client_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_staff_ledgerwise_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staff_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staff_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staff_ledgerwise_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.daily_expense_report_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var overlay = $(
            "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function() {
            $('#loading').remove();
        }, 10000);

        var link = document.createElement('a');
        link.href = $('#base_url').val() + '/daily_expense_report_pdf';

        link.click();
    });

    $(document).on('click', '.all_clients_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_clients_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_clients_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_clients_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.all_leads_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_leads_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_leads_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_leads_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.leads_by_sales_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/leads_by_sales_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/leads_by_sales_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/leads_by_sales_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.other_leads_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/other_leads_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/other_leads_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/other_leads_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.cancelled_invoice_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/cancelled_invoice_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/cancelled_invoice_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/cancelled_invoice_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.consultation_fee_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/consultation_fee_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/consultation_fee_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/consultation_fee_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_followup_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_followup_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_followup_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_followup_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.staff_attendance_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/staff_attendance_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/staff_attendance_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/staff_attendance_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.invoice_against_quotation_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/invoice_against_quotation_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/invoice_against_quotation_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/invoice_against_quotation_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.additional_invoices_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/additional_invoices_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/additional_invoices_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/additional_invoices_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.billwise_payment_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/billwise_payment_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/billwise_payment_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/billwise_payment_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.clientwise_tds_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/clientwise_tds_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/clientwise_tds_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/clientwise_tds_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_not_followup_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_not_followup_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_not_followup_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_not_followup_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_no_email_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_email_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_email_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_email_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_no_contact_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_contact_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_contact_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_contact_pdf/' +
                        year_filter +
                        '/' +
                        quarter_filter + '/' + month_filter;

                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_sent_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_sent_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_sent_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_sent_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_finalized_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_finalized_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_finalized_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_finalized_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_by_sales_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_sales_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_sales_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_sales_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_by_office_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_office_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_office_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_quotation_by_office_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.servicewise_quotation_sent_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_sent_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_sent_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_sent_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.servicewise_quotation_finalized_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_finalized_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_finalized_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/export_servicewise_quotation_finalized_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.clientwise_quotation_finalized_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var overlay = $(
            "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function() {
            $('#loading').remove();
        }, 10000);

        var link = document.createElement('a');
        link.href = $('#base_url').val() + '/clientwise_quotation_finalized_excel';
        link.click();
    });

    $(document).on('click', '.expense_report_staffwise_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staffwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staffwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staffwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_ledgerwise_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_clientwise_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_clientwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_clientwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_clientwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_reimbursement_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_reimbursement_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_reimbursement_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_reimbursement_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_client_ledgerwise_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_client_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_client_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_client_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_staff_ledgerwise_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staff_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staff_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/expense_report_staff_ledgerwise_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.daily_expense_report_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var overlay = $(
            "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function() {
            $('#loading').remove();
        }, 10000);

        var link = document.createElement('a');
        link.href = $('#base_url').val() + '/daily_expense_report_excel';
        link.click();
    });

    $(document).on('click', '.all_clients_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_clients_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_clients_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_clients_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.all_leads_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_leads_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_leads_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/all_leads_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.leads_by_sales_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/leads_by_sales_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/leads_by_sales_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/leads_by_sales_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.other_leads_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/other_leads_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/other_leads_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/other_leads_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.cancelled_invoice_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/cancelled_invoice_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/cancelled_invoice_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/cancelled_invoice_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.consultation_fee_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/consultation_fee_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/consultation_fee_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/consultation_fee_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_followup_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_followup_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_followup_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_followup_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.staff_attendance_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/staff_attendance_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/staff_attendance_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/staff_attendance_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.invoice_against_quotation_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/invoice_against_quotation_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/invoice_against_quotation_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/invoice_against_quotation_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.additional_invoices_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/additional_invoices_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/additional_invoices_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/additional_invoices_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.billwise_payment_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/billwise_payment_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/billwise_payment_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/billwise_payment_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.clientwise_tds_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/clientwise_tds_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/clientwise_tds_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/clientwise_tds_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_not_followup_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_not_followup_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_not_followup_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_not_followup_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_no_contact_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_contact_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_contact_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_contact_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_no_email_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_email_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_email_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    var overlay = $(
                        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
                    );
                    $("body").append(overlay);
                    setTimeout(function() {
                        $('#loading').remove();
                    }, 2000);

                    var link = document.createElement('a');
                    link.href = $('#base_url').val() + '/client_no_email_excel/' +
                        year_filter + '/' +
                        quarter_filter + '/' + month_filter;
                    link.click();
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_sent_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_sent_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation Sent Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_sent_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation Sent Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_sent_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation Sent Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_finalized_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_finalized_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation Finalized Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_finalized_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation Finalized Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_finalized_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation Finalized Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_by_sales_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_by_sales_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation By Sales Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_by_sales_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation By Sales Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_by_sales_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation By Sales Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.quotation_by_office_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_by_office_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation By Office Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_by_office_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation By Office Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'quotation_by_office_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Quotation By Office Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.servicewise_quotation_sent_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'servicewise_quotation_sent_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Servicewise Quotation Sent Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'servicewise_quotation_sent_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Servicewise Quotation Sent Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'servicewise_quotation_sent_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Servicewise Quotation Sent Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.servicewise_quotation_finalized_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'servicewise_quotation_finalized_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Servicewise Quotation Finalized Report:</title>'
                            );
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'servicewise_quotation_finalized_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Servicewise Quotation Finalized Report:</title>'
                            );
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'servicewise_quotation_finalized_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Servicewise Quotation Finalized Report:</title>'
                            );
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.clientwise_quotation_finalized_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'get',
            url: 'clientwise_quotation_finalized_print',
            data: {},

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                var printWindow = window.open('', '', 'height=800,width=1200');
                printWindow.document.write(
                    '<html><head><title>Clientwise Quotation Finalized Report:</title>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(res.out);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on('click', '.expense_report_staffwise_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_staffwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staffwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_staffwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staffwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_staffwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staffwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_ledgerwise_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_clientwise_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_clientwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Clientwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_clientwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Clientwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_clientwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Clientwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_reimbursement_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_reimbursement_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Reimbursement Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_reimbursement_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Reimbursement Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_reimbursement_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Reimbursement Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_client_ledgerwise_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_client_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_client_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_client_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.expense_report_staff_ledgerwise_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_staff_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staff Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_staff_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staff Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'expense_report_staff_ledgerwise_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staff Ledgerwise Expense Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.daily_expense_report_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: 'daily_expense_report_print',
            data: {},

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                var printWindow = window.open('', '', 'height=800,width=1200');
                printWindow.document.write(
                    '<html><head><title>Daily Expense Report:</title>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(res.out);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).on('click', '.all_clients_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'all_clients_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>All Clients Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'all_clients_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>All Clients Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'all_clients_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>All Clients Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.all_leads_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'all_leads_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>All Leads Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'all_leads_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>All Leads Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'all_leads_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>All Leads Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.leads_by_sales_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'leads_by_sales_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Leads By Client Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'leads_by_sales_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Leads By Client Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'leads_by_sales_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Leads By Client Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.other_leads_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'other_leads_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Other Leads Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'other_leads_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Other Leads Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'other_leads_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Other Leads Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.cancelled_invoice_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'cancelled_invoice_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Cancelled Invoice Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'cancelled_invoice_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Cancelled Invoice Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'cancelled_invoice_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Cancelled Invoice Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.consultation_fee_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'consultation_fee_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Consultation Fee Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'consultation_fee_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Consultation Fee Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'consultation_fee_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Consultation Fee Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_followup_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_followup_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Follow Up Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_followup_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Follow Up Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_followup_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Follow Up Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.staff_attendance_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'staff_attendance_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staff Attendance Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'staff_attendance_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staff Attendance Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'staff_attendance_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Staff Attendance Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.invoice_against_quotation_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'invoice_against_quotation_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Invoice Against Quotation Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'invoice_against_quotation_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Invoice Against Quotation Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'invoice_against_quotation_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Invoice Against Quotation Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.additional_invoices_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'additional_invoices_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Additional Invoices Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'additional_invoices_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Additional Invoices Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'additional_invoices_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Additional Invoices Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.billwise_payment_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'billwise_payment_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Billwise Payment Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'billwise_payment_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Billwise Payment Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'billwise_payment_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Billwise Payment Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.clientwise_tds_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'clientwise_tds_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Clientwise TDS Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'clientwise_tds_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Clientwise TDS Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'clientwise_tds_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Clientwise TDS Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_not_followup_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_not_followup_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Not Follow Up Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_not_followup_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Not Follow Up Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_not_followup_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Not Follow Up Report:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_no_contact_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_no_contact_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Report With No Contact:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_no_contact_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Report With No Contact:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_no_contact_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Report With No Contact:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.client_no_email_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if ($("input[name='bsradio']").is(":checked")) {
            var filter = $("input[name='bsradio']:checked").val();

            $('.radio_error_message').html('');
            if (filter == 'year_search') {
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (year_filter != 'Year') {
                    $('.error_message').html('');
                    year_filter = year_filter;
                    month_filter = 'none';
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_no_email_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Report With No Email Id:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select year filter first!!');
                    return false;
                }
            } else if (filter == 'month_year_search') {
                var month_filter = $(".month_filter").closest('.month-input').find('.month-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (month_filter != 'Monthly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    month_filter = month_filter;
                    year_filter = year_filter;
                    quarter_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_no_email_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Report With No Email Id:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select month and year filter first!!');
                    return false;
                }
            } else if (filter == 'quarter_year_search') {
                var quarter_filter = $(".quarter_filter").closest('.quarter-input').find(
                        '.quarter-text')
                    .text();
                var year_filter = $(".year_filter").closest('.year-input').find('.year-text')
                    .text();
                if (quarter_filter != 'Quarterly' && year_filter != 'Year') {
                    $('.error_message').html('');
                    quarter_filter = quarter_filter;
                    year_filter = year_filter;
                    month_filter = 'none';
                    $.ajax({
                        type: 'get',
                        url: 'client_no_email_print',
                        data: {
                            month: month_filter,
                            quarter: quarter_filter,
                            year: year_filter
                        },

                        success: function(data) {
                            console.log(data);
                            var res = JSON.parse(data);
                            var printWindow = window.open('', '', 'height=800,width=1200');
                            printWindow.document.write(
                                '<html><head><title>Client Report With No Email Id:</title>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(res.out);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.error_message').html('Select quarter and year filter first!!');
                    return false;
                }
            }
        } else {
            $('.radio_error_message').html('Select radio button first!!');
            return false;
        }
    });

    $(document).on('click', '.daily_sales_excel', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var link = document.createElement('a');
        link.href = $('#base_url').val() + '/daily_sales_excel';
        link.click();
    });

    $(document).on('click', '.daily_sales_pdf', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var link = document.createElement('a');
        link.href = $('#base_url').val() + '/daily_sales_pdf';

        link.click();
    });

    $(document).on('click', '.daily_sales_print', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: 'daily_sales_print',
            data: {},

            success: function(data) {
                console.log(data);
                var res = JSON.parse(data);
                var printWindow = window.open('', '', 'height=800,width=1200');
                printWindow.document.write(
                    '<html><head><title>Daily Sales Report:</title>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(res.out);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
</script>
@endsection