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

<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">

@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/app-todo.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/task.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<style type="text/css">
  .datepicker {
    z-index: 100000;
  }
  .dataTables_filter {
    float: right;
    padding: 0px 20px 0px 0px;
    margin: 0.5rem 0 !important;
  }
</style>
@endsection
{{-- page content --}}
@section('content')
<div id="alert" class="mt-1"></div>
<section id="table-success">
  <h5>Project List</h5>
  <div class="row mt-2">
    <div class="col-lg-3">
      <div class="form-group">
        <select class="form-control project_status" name="project_status">
          <option value="">Select status</option>
          @foreach($project_status_master as $row)
          <option value="{{$row->id}}">{{$row->status}}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="form-group">
        <input type="text" class="form-control datepicker project_startdate" name="project_startdate" placeholder="Start Date">
      </div>
    </div>
    <div class="col-lg-3">
      <div class="form-group">
        <input type="text" class="form-control datepicker project_enddate" name="project_enddate" placeholder="End Date">
      </div>
    </div>
    <div class="col-lg-3">
      <fieldset class="form-group">
        <button type="button" class="btn btn-outline-primary" id="filter_project"><i class="bx bx-filter-alt"></i><span class="align-middle ml-25">Filter</span></button>
      </fieldset>
    </div>
  </div>
  <div class="card _card">
    <!--   <div class="card-header">
          <h4 class="card-title">Row grouping</h4>
        </div>  -->
    <div class="project_list">

    </div>
  </div>
</section>

<!-- Template to task modal -->
<div class="modal fade text-left" id="TemplateToTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header-template">
        <h3 class="modal-title modal_template_title" id="myModalLabel1">Task Templates</h3>
        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal_err valid_err"></div>
        <input type="hidden" id="modal_project_id">
        <input type="hidden" id="modal_main_template_id">
        <div class="row">
          <div class="col-12">
            <input type="hidden" id="templateid">
            <span class="valid_err template_name_err"></span>
          </div>

          <div class="col-12 modal-template_TForm">
            <div class="card _card p-1">
              <div class="table-responsive">
                <table class="table table-hover table_config_1 dt-responsive wrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Template Name</th>
                      <th>Description</th>
                      <th>Created Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = 1; @endphp
                    @foreach($main_template_list as $row1)
                    <tr class="main_template" data-main_template_id="{{$row1->id}}">
                      <td>{{$i++}}</td>
                      <td>{{$row1->template_name}}</td>
                      <td>{{$row1->description}}</td>
                      <td>{{($row1->created_at != null) ? date('d/m/Y',strtotime($row1->created_at)) : '' }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer1">
        <button type="button" id="submit_create_template_task" class="btn btn-primary px-5 create_template_task_btn mr-1">
          <i class="bx bx-check d-block d-sm-none"></i>
          <span class="d-none d-sm-block template_btn_name">Duplicate</span>
        </button>
        <button type="button" class="btn btn-light-secondary px-5" data-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Cancel</span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- End Template to task modal -->
@endsection
@section('vendor-scripts')
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('vendors/js/extensions/dragula.min.js') }}"></script>
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
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>

@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>

<script src="{{ asset('js/scripts/pages/app-todo.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // alert('project task');
    $(document).ready(function() {
      $(".datepicker")
        .datepicker()
        .on("changeDate", function(ev) {
          $(".datepicker.dropdown-menu").hide();
        });

      $(".office_id").select2({
        dropdownAutoWidth: true,
        width: '100%',
        placeholder: 'Select Office'
      });
      $(".working_hr").select2({
        dropdownAutoWidth: true,
        width: '100%',
        placeholder: 'Working Hour'
      });
    })
 
    assignee_list();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    //main template list
    $(".table_config_1").DataTable({
      columnDefs: [{
          targets: 0,
          className: "control",
        },
        {
          // targets: [3],
          // orderable: false,
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

    fetch_project_list();
    fetch_client_project_list();
    fetch_template_task_list();
    // new task description editor
    var newTaskEditor = new Quill('.new_task_editor', {
      modules: {
        toolbar: '.new_task_quill_toolbar'
      },
      placeholder: 'Add Description..... ',
      theme: 'snow'
    });

    $(document).on('click', '#filter_project', function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var project_status = $('.project_status').val();
      var project_startdate = $('.project_startdate').val();
      var project_enddate = $('.project_enddate').val();
      fetch_project_list(project_status, project_startdate, project_enddate);
    });
  });

  function fetch_project_list(project_status, project_startdate, project_enddate) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    if (project_status == null) {
      project_status = null;
    }
    if (project_startdate == null) {
      project_startdate = null;
    }
    if (project_enddate == null) {
      project_enddate = null;
    }

    $('.alert').css('display', 'none');
    $.ajax({
      type: "post",
      url: "project_filter",
      data: {
        project_status: project_status,
        project_start_date: project_startdate,
        project_end_date: project_enddate
      },

      success: function(data) {
        $('.project_list').empty().html(data);
        // ---------
        if ($(".table_config").length) {
          var dataListView = $(".table_config").DataTable({
            columnDefs: [{
                targets: 0,
                className: "control",
              },
              {
                // targets: [0, 1],
                targets: [6],
                orderable: false,
              },
            ],
            // order: [2, "asc"],
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: [
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5'
            ],
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
      },
      error: function(data) {
        console.log(data);
      }

    });
  }
</script>
@endsection