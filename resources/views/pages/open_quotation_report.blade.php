@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Appointment List')
{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.bubble.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<link rel="stylesheet" href="{{asset('css/plugins/forms/form-quill-editor.css')}}">

<style>
    .modal-lg,
    .modal-xl {
        max-width: 1500px;
    }

    .dropdown-menu {
        z-index: 99999 !important;
    }

    .table-condensed {
        border-collapse: initial;
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

    .dow {
        color: #2454b1;
    }

    .mt {
        margin-top: 10px;
    }

    .mb {
        margin-bottom: 10px;
    }

    #editorContainer {
        height: 300px;
        margin-bottom: 55px;
    }
    .radioBox {
        margin-bottom: 0px;
        margin-top: 10px;
    }
    .dataTables_filter {
        padding-left: 0px !important;
        margin-left: -6px !important;
    }
    .action-btns {
        margin-top: -16px;
    }
    .TopForm{
        padding: 6px 0px 6px 16px;
    }
    .dataTables_filter {
        display: inline-flex;
        width: 62%;
        margin-left: 12px !important;
        margin-top: 0px !important;
    }
    .dt-buttons.btn-group.flex-wrap {
        padding-bottom: 4px;
    }
    .table-responsive {
        margin-top: -4px;
    }
</style>
@endsection

@section('content')
<section class="client-list-wrapper">
    <center>
        <div class="spinner-grow text-primary loader" role="status" style="display:none">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:none">Please wait...</h5>
    </center>
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

    <div class="card mt">
        <div id="alert">


        </div>
        <div class="card-body">
            <div class="row TopForm">
            
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker from_date" placeholder="From Date" value="{{date('01/m/Y')}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-label-group">
                        <input type="text" class="form-control input_control datepicker to_date" placeholder="To Date" value="{{date('d/m/Y')}}">
                    </div>
                </div>
               
           
            
                
                <div class="col-md-3">
                    <a href="javascript:void(0);" class="btn btn-primary btn-md round px-3 search"><strong>Search</strong></a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-md round px-4" id="reset"><strong>Reset</strong></a>
                </div>
            </div>

            <div class="data_div">

            </div>
        </div>
    </div>

  
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script> -->
<!-- <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script> -->
<script src="{{asset('vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/quill.min.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/appointment.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        filter_open_quotation(from_date,to_date);
        function filter_open_quotation(from_date,to_date)
        {
           
             $.ajax({
                    type: "get",
                    url: "filter_open_quotation",
                    data: {from_date:from_date,to_date:to_date},
                    success: function (data) {
                    console.log(data);
                    if (data) {
                        
                        $(".data_div").empty().html(data);
                    } else {
                        Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Some error while filter office visit",
                        confirmButtonClass: "btn btn-danger",
                        });
                    }
                    },
                    error: function (data) {
                    console.log(data);
                    },
                });
        }
       $(document).on("click", ".search", function() {
             var from_date = $(".from_date").val();
             var to_date = $(".to_date").val();
             filter_open_quotation(from_date,to_date);
         
         
        });
});




</script>
@endsection