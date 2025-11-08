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
<style>
  
</style>
@endsection
{{-- page content --}}
@section('content')
<div id="alert" class="mt-1"></div>
@include('layouts.task_tabs')

<div class="task_list"></div>

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

@section('page-scripts')
<script src="{{ asset('js/scripts/pages/app-todo.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-comments-popup.js') }}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script> 
<script type="text/javascript">
 
       
  $(document).ready(function() {
   
    var tag_id=[];
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
    
    $(".project_select").select2({
      dropdownAutoWidth: true,
      width: '100%',
      placeholder:'Select Project'
    });
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
    fetch_task_list();
    fetch_client_project_list();
    fetch_template_task_list();
 
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
  
    $(document).on('click','#filter_task',function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var task_filter_type=$('.task_filter_type').val();
        var task_filter_status=$('.task_filter_status').val();
      
        var task_filter_priority=$('.task_filter_priority').val();
        var task_filter_date=$('.task_filter_date').val();
        
        if(task_filter_type=='' && task_filter_status=='' && task_filter_priority=='' && task_filter_date=='')
        {
           
            $('.alert').css('display','block');
        }
        else
        {
            
            $('.alert').css('display','none');
            $.ajax({
                    type: "post",
                    url: "task_filter",
                    data: {
                    task_filter_type: task_filter_type,
                    task_filter_status: task_filter_status,
                    task_filter_priority:task_filter_priority,
                    task_filter_date:task_filter_date,
                    
                    },

            success: function (data) {
                    
                    $('.task_list').html(data);
                   
                    },
            error: function (data) {
                 $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                console.log(data);
                    }

            });
        }
    });
  
     
      
    });
</script>

@endsection