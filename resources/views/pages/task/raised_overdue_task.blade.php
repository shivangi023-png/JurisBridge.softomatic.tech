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
   
</style>
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">

<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
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
<div id="alert"></div>
@include('layouts.task_overdue_tab')
<hr>


<div class="raised_overdue_task_list table-responsive"> </div>
<div class="modal fade text-left" id="ApproveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel"></h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
                <div class="modal-body">
            <form id="modal_form">
           
                <div class="row">
                            <input type="hidden" class="form-control raised_id">
                            <input type="hidden" class="form-control raise_task_id">
                            <input type="hidden" class="form-control raise_staff_id" value="{{session('staff_id')}}">
                        
                    </div>
                  <div class="row">
                        <div class="col">
                            <fieldset class="d-flex form-label-group">
                            <input type="text" class="form-control mr-2 mb-50 mb-sm-0 datepicker raise_end_date" placeholder="End Date">
                                <label for="raise_end_date">End Date</label>
                            </fieldset>
                            <span class="valid_err raise_end_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                        <fieldset class="form-group flex-grow-1" style="margin-bottom:0px;padding-right:12px">
                          <select class="form-control raise_task_status select2">
                                  
                                @foreach($task_status_master as $row)
                                <option value="{{$row->id}}">{{$row->status}}</option>
                                @endforeach
                                        </select>
                                          
                          </fieldset>
                            <span class="valid_err raise_task_status_err"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="approve_overdue" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>

@endsection
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
    fetch_raised_overdue_task();
    fetch_template_task_list();
    fetch_client_project_list();
    $('.datepicker').datepicker().on('changeDate', function(ev) {
                        $('.datepicker.dropdown-menu').hide();
                    });

       
  });
  $(document).ready(function() {
    
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
    $(".select2").select2({
      dropdownAutoWidth: true,
      width: '100%'
    });
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

 $(document).on("click", ".reject_overdue_task", function (e) {
  e.preventDefault();
  var reject_overdue_task_id = $(this).data("reject_overdue_task_id");
  Swal.fire({
    title: "Are you sure?",
    text: "You want reject raised overdue task?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes",
    confirmButtonClass: "btn btn-warning",
    cancelButtonClass: "btn btn-danger ml-1",
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        type: "post",
        url: "reject_overdue_task",
        data: {
          raised_id: reject_overdue_task_id,
        },
        success: function (res) {

          if (res.status == "success") {
           
            fetch_raised_overdue_task();
            $("#alert")
              .empty()
              .html(
                '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              );
          } else if (res.status == "error") {
            $("#alert")
              .empty()
              .html(
                '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              );
          }
        },
        error: function (data) {
          console.log(data);
        },
      });
    }
  });

  if ($(e.target).hasClass("ignore-click") || $("a").hasClass("ignore-click")) {
    return false;
  }
});
});
$(document).on('click','.approve_overdue_task',function(){
    var task_id=$(this).data('task_id');
    var raised_id=$(this).data('raised_id');
    $('.raise_end_date').val($(this).data('raise_end_date'));
    $(".raise_task_status").val($(this).data('raised_task_status'));
              $(".raise_task_status").select2({
            dropdownAutoWidth: true,
            width: "100%",
            placeholder: "Task Status",
            }).trigger('change');
    $('.raised_id').val(raised_id);
    $('.raise_task_id').val(task_id);
    $('#myModalLabel').html($(this).data('task_name')+' ('+$(this).data('project_name')+')');
});
$(document).on('click','#approve_overdue',function(){
    var raised_id=$('.raised_id').val();
    var task_id=$('.raise_task_id').val();
    var staff_id=$('.raise_staff_id').val();
    var raise_end_date=$('.raise_end_date').val();
    var raise_task_status=$('.raise_task_status').val();
    if(raise_end_date=='')
          {
            $('.raise_end_date_err').html('End date required').css('color', 'red');
            return false
          }
          else
          {
            $('.raise_end_date_err').html('');
            
          $.ajax({
              type: "post",
              url: "approve_overdue",
              data: {
                task_id: task_id,
                staff_id:staff_id,
                raise_end_date:raise_end_date,
                raised_id:raised_id,
                raise_task_status:raise_task_status
              },
              success: function (res) {
                console.log(res);
                if(res.status=="success")
                {
                  fetch_overdue_grid_task()
                  $('#ApproveModal').modal('toggle')
                  fetch_raised_overdue_task();
                  $("#alert").html(
                    '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                      res.msg +
                      "</span></div></div>"
                  );
                }
                else
                {
                  $('#ApproveModal').modal('toggle')
                  $("#alert").html(
                    '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                      res.msg +
                      "</span></div></div>"
                  );
                }
               
              
                
              },
              error: function (data) {
                $('#ApproveModal').modal('toggle')
                    $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  data +
                  "</span></div></div>"
              );
              },
            });
          }
});

</script>

@endsection