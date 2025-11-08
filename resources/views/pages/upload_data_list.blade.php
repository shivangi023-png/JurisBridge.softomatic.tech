@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','My Followup')
{{-- vendor style --}}
@section('vendor-styles')
@include('links.datatable_links')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<style>
    .ui-autocomplete {
        z-index: 1061 !important;
    }
    .card-body{
        margin-top: 10px;
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
                    <a href="upload_lead_data" class="btn btn-icon btn-outline-primary float-right" role="button" aria-pressed="true">
                        <i class="bx bx-plus"></i>Upload Data</a>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control  consumer_name" placeholder="Consumer Name">
                    </div>
                    <span class="text-danger from_date_err"></span>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control  mobile_no" placeholder="Mobile No">
                    </div>
                    <span class="text-danger from_date_err"></span>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <textarea rows="1"  class="form-control input_control address" placeholder="Address"></textarea>
                    </div>
                    <span class="text-danger to_date_err"></span>
                </div>
                <div class="col-md-3">
                    
                    <select class="form-control file_name input_control dropdown-toggle" >
                        <option value="">File Name</option>
                            @foreach($filename as $row)
                            <option value="{{$row->file_name}}">{{$row->file_name}}</option>
                            @endforeach
                    </select>
                  
              
                    <span class="text-danger to_date_err"></span>
                </div>
              
                <div class="col-md-1">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3" id="search"><strong>Search</strong></a>
                </div>
                <div class="col-md-1 ml-3">
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div>

            </div>
          
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




@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>


@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/upload_data.js')}}"></script>
<script>
    
</script>
@endsection