@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Task Report')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{(asset('vendors/css/forms/select/select2.min.css'))}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<script src="{{asset('vendors/js/jquery/jquery.min.js')}}"></script>
@endsection
{{-- page style --}}
@section('page-styles')
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

</style>
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
                    {{-- <div class="col-9">
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
                    </div> --}}
                </div>
                <div class="data_div">
                    <div class="row">
                        <div class="col-3 pr-2">
                            <div class="card reportMenu">
                                <a href="#OnHoldTaskTab">
                                    <div class=" card-header border bg-primary text-white RMenuBox" id="onhold_task_report" onclick="get_report(this.id)">
                                        <i class="bx bx-file bx_icon mr-2"></i> <strong class="ml-2">Task Reports</strong>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-9">
                            <div class="card border_primary" id="OnHoldTaskTab">
                                <div class="card-body">
                                    <div class="row mb-1">
                                            <div class="col-9">
                                                <h6 class="font_style">1. OnHold Task Report</h6>
                                            </div>
                                            <div class="col-3">
                                                <span data-tooltip="Export to PDF" data-value="onhold_task_pdf">
                                                    <a href="javascript:void(0);" id="onhold_task_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                    </a>
                                                </span>
                                                <span data-tooltip="Export to Excel" data-value="onhold_task_excel">
                                                    <a href="javascript:void(0);" class="ml-1" id="onhold_task_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                                </span>
                                                <span data-tooltip="Print" data-value="onhold_task_print">
                                                    <a href="javascript:void(0);" class="ml-1" id="onhold_task_print" data-title="OnHold Task" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                                                </span>
                                            </div>
                                    </div>
                                     <div class="row mb-1">
                                            <div class="col-9">
                                                <h6 class="font_style">2. Hearing Task Report</h6>
                                            </div>
                                            <div class="col-3">
                                                <span data-tooltip="Export to PDF" data-value="hearing_task_pdf">
                                                    <a href="javascript:void(0);" id="hearing_task_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                                                    </a>
                                                </span>
                                                <span data-tooltip="Export to Excel" data-value="hearing_task_excel">
                                                    <a href="javascript:void(0);" class="ml-1" id="hearing_task_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                                                </span>
                                                <span data-tooltip="Print" data-value="hearing_task_print">
                                                    <a href="javascript:void(0);" class="ml-1" id="hearing_task_print" data-title="Hearing Task" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
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
@section('vendor-scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/get_task_reports.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
@endsection