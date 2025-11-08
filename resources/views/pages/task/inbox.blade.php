@extends('task_layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'InBox | Task Management')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/extensions/dragula.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">

<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">

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
@include('layouts.inoutbox_tab')

<div class="inoutbox">
<center>
        <div class="spinner-grow text-primary loader" role="status" style="display:block">
          <span class="sr-only">Loading...</span>
        </div>
        <h5 class="loader" style="display:block">Please wait...</h5>
      </center>
</div>




@endsection
@section('vendor-scripts')
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('vendors/js/extensions/dragula.min.js') }}"></script>
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/charts/chart.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
<script src="{{ asset('js/scripts/pages/app-todo.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<script src="{{ asset('js/scripts/pages/in_outbox.js') }}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    
get_inbox();
    fetch_client_project_list();
    fetch_template_task_list();


    $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });
  
     assignee_list();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // ---------
    if ($(".table_config").length) {
    var dataListView = $(".table_config").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control",
        },
        {
           targets: [7],
          orderable: false,
        },
      ],
      // order: [2, "asc"],
      language: {
        search: "",
        searchPlaceholder: "Search",
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row",
      },
      responsive: {
        details: {
          type: "column",
          target: 0,
        },
      },
    });
  }
  // -------
   $('.project_startdate_enddate').daterangepicker({
    "autoApply": true,
    "showDropdowns": true,
    locale: {
      format: 'DD/MM/YYYY'
    }
    });
   // Date picker default null set
  $('.project_startdate_enddate,.task_date').val('');
    
  // $(".select2").select2({
  //   dropdownAutoWidth: true,
  //   width: '100%'
  // });
  $(".office_id").select2({
      dropdownAutoWidth: true,
      width: '100%',
      placeholder:'Select Office'
    });
    $(".working_hr").select2({
      dropdownAutoWidth: true,
      width: '100%',
      placeholder:'Working Hour'
    });
  fetch_client_project_list();
  fetch_template_task_list();

  // subtask description editor
  var subTaskEditor = new Quill('.subtask_editor', {
    modules: {
      toolbar: '.subtask_quill_toolbar'
    },
    placeholder: 'Add Description... ',
    theme: 'snow'
  });
  var newTaskEditor = new Quill('.new_task_editor', {
    modules: {
      toolbar: '.new_task_quill_toolbar'
    },
    placeholder: 'Add Description..... ',
    theme: 'snow'
  });
  });
  </script>
@endsection