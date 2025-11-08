@extends('task_layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Task Management')
{{-- vendor styles --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/extensions/dragula.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/app-todo.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/task.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
@endsection
{{-- page content --}}
@section('content')
@include('layouts.task_tabs')
<div >
   <div class="card1" style=" margin-top: 52px;margin-bottom: 30px;">
      <div class="card-body pl-1">
<div class="row">
    <div class="col-md-6">
      <div class="card">
     <!--  <div class="card-header">
          <h4 class="card-title">Line Chart</h4>
        </div> -->
        <div class="card-body pl-0">
           <div class="height-300" id="donutChartContentTaskStatus">
              <div id="donut-chart-TaskStatus" class="d-flex justify-content-center"></div>
            </div>
        </div>
      </div>
    </div>

    <!-- Bar Chart -->
    <div class="col-md-6">
      <div class="card">
       <!--   <div class="card-header">
          <h4 class="card-title">Bar Chart</h4>
        </div> -->
        <div class="card-body pl-0">
          <div class="height-300">
                   <canvas id="bar_chart"></canvas>
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

@endsection
@section('vendor-scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection
@section('page-scripts')
<script src="{{ asset('js/scripts/pages/app-todo.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task_chart.js') }}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
      $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    const d = new Date();
    let month = d.getMonth()+1;//current month no.
    donutChartPie(month);
    MilestoneTypeBarChart(month);
    fetch_task_list();
    fetch_client_project_list();
    fetch_template_task_list();
  });
</script>
@endsection