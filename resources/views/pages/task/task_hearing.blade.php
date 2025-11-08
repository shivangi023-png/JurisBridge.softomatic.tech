@extends('task_layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Task Management')
{{-- vendor styles --}}
<style>
  .valid_err {
    color: red;
    font-size: 12px;
  }

  .body {
    float: right;
    position: absolute;
    right: 0;
    margin-top: 5px;
    margin-right: 230px;
  }

  .todo-task-list {
    height: 90vh !important;
  }
   .ps__rail-y {
    display: none !important;
  }
</style>
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/editors/quill/quill.snow.css') }}">

@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/app-todo.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/task.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
@endsection
{{-- page content --}}
@section('content')
<div id="alert" class="mt-1"></div>
@include('layouts.task_tabs')
<div class="todo-fixed-search  justify-content-between align-items-center" style="margin-top:16px">
  <div class="row">
    <div class="col" style="padding-right:0px; padding-left:30px">
      <select class="form-control task_filter_status" multiple="multiple">
        @foreach($task_status_master as $row)
        <option value="{{$row->id}}">{{$row->status}}</option>
        @endforeach
      </select>
    </div>
    <div class="col" style="padding-right:0px; padding-left:30px">
      <fieldset class="form-group" style="margin-bottom:0px;">
        <input type="text" style="top: 461.4px" class="form-control datepicker hearing_filter_from_date" placeholder="From date">
      </fieldset>
    </div>
    <div class="col">
      <fieldset class="form-group" style="margin-bottom:0px;">
        <input type="text" style="top: 461.4px" class="form-control datepicker hearing_filter_to_date" placeholder="to date">
      </fieldset>
    </div>
    <div class="col">
      <fieldset class="form-group position-relative has-icon-left flex-grow-1" style="margin-bottom:0px">
        <button type="button" class="btn btn-outline-primary" id="filter_hearing"><i class="bx bx-filter-alt"></i><span class="align-middle ml-25">Filter</span></button>
      </fieldset>
    </div>
  </div>
  <div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert" style="padding:5px;display:none">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" fdprocessedid="2gydd">
      <span aria-hidden="true">×</span>
    </button>
    <div class="d-flex align-items-center">
      <i class="bx bx-error"></i>
      <span>
        Please select any one field.
      </span>
    </div>
  </div>
</div>
<div id="hearing_list"></div>

@endsection
@section('vendor-scripts')
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('vendors/js/extensions/dragula.min.js') }}"></script>
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>

@endsection

@section('page-scripts')
<script src="{{ asset('js/scripts/pages/app-todo.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $(".task_filter_status").select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Task Status",

    }).trigger('change');
    fetch_hearing_task('', '', '');
    var newTaskEditor = new Quill('.new_task_editor', {
      modules: {
        toolbar: '.new_task_quill_toolbar'
      },
      placeholder: 'Add Description..... ',
      theme: 'snow'
    });
    $(".datepicker")
      .datepicker()
      .on("changeDate", function(ev) {
        $(".datepicker.dropdown-menu").hide();
      });

  });
  $(document).on('click', '#filter_hearing', function() {
    var task_filter_status = $('.task_filter_status').val();
    var hearing_filter_from_date = $('.hearing_filter_from_date').val();
    var hearing_filter_to_date = $('.hearing_filter_to_date').val();
    if (hearing_filter_from_date == '' && hearing_filter_to_date == '' && task_filter_status=='') {

      $('.alert').css('display', 'block');
    } else {

      $('.alert').css('display', 'none');
      fetch_hearing_task(hearing_filter_from_date, hearing_filter_to_date, task_filter_status);

    }
  });
</script>

@endsection