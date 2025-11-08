@extends('task_layouts.contentLayoutMaster')
{{-- page title --}}
@section('title', 'Over due task')
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


    .table-condensed {
        border-collapse: initial;
    }

    .datepicker-days {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker-months {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }
.day
{
  border: 1px solid #ede7e7;
  
}
.datepicker-years {
        width: 205px;
        height: 210px;
        padding-left: 10px;
    }

    .datepicker thead {
        background-color: #e9edf1;
        color: #2454b1;
    }

    .dow {
        color: #2454b1;
    }
      .my_task_list {
      overflow-x: scroll !important;
      overflow-y: scroll !important;
      margin-bottom: 50px;
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
@endsection
{{-- page content --}}
@section('content')
@include('layouts.task_tabs')
<hr>
@include('layouts.task_overdue_tab')

<div class="my_task_list"></div>

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
    var task_filter_type=$('.task_filter_type').val();
    var task_filter_status=$('.task_filter_status').val();
    var task_filter_priority=$('.task_filter_priority').val();
    var task_filter_from_date=$('.task_filter_from_date').val();
    var task_filter_to_date=$('.task_filter_to_date').val();
    $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });
    // alert('add task');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.autoapply').daterangepicker({
      "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });
    // Date picker default null set
    $('.project_startdate_enddate,.task_date').val('');
  
    fetch_my_task();
    fetch_client_project_list();
    fetch_template_task_list();
  });
  $(document).ready(function() {
    // alert('add task');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.autoapply').daterangepicker({
      "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });
    // Date picker default null set
    $('.project_startdate_enddate,.task_date').val('');
  
    fetch_task_list();
    fetch_client_project_list();
    fetch_template_task_list();
  });
  $(document).ready(function(){
        $(".task_filter_type").val('');
        $(".task_filter_type").select2({
        dropdownAutoWidth: true,
        width: "100%",
        placeholder: "Task Type",

        }).trigger('change');
        $(".task_filter_status").val('');
        $(".task_filter_status").select2({
        dropdownAutoWidth: true,
        width: "100%",
        placeholder: "Task Status",

        }).trigger('change');
        $(".task_filter_priority").val('');
        $(".task_filter_priority").select2({
        dropdownAutoWidth: true,
        width: "100%",
        placeholder: "Task priority",

        }).trigger('change');
    });
    $(document).on('click','#filter_task',function(){
      
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var task_filter_type=$('.task_filter_type').val();
        var task_filter_status=$('.task_filter_status').val();
      
        var task_filter_priority=$('.task_filter_priority').val();
        var task_filter_from_date=$('.task_filter_from_date').val();
        var task_filter_to_date=$('.task_filter_to_date').val();
        
        if(task_filter_type=='' && task_filter_status=='' && task_filter_priority=='' && task_filter_from_date=='' && task_filter_to_date=='')
        {
           
            $('.alert').css('display','block');
        }
        else
        {
            
            $('.alert').css('display','none');
            $.ajax({
                    type: "post",
                    url: "overdue_task_filter",
                    data: {
                    task_filter_type: task_filter_type,
                    task_filter_status: task_filter_status,
                    task_filter_priority:task_filter_priority,
                    task_filter_from_date:task_filter_from_date,
                    task_filter_to_date:task_filter_to_date,
                    
                    },

            success: function (data) {
                    console.log(data);
                    $('.over_due_task_list').html(data);
                   
                    },
            error: function (data) {
                console.log(data);
                    }

            });
        }
    });
</script>

@endsection