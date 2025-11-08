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
@endsection
{{-- page content --}}
@section('content')
<div class="row">
  <div class="col-md-10">
  <h4 class="project_heading">{{$main_template_name}}</h4>
  </div>
  <div class="col-md-2 mt-1">
    <div class="ThreeIcons">
     <a href="#" title="Create Task" class="new_task_Template_btn" data-main_template_id="{{$main_template_id}}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" style="fill: #46b16a;margin-bottom: 0px;margin-top: -8px;"><path fill="ccc" d="M208 32H48a16 16 0 0 0-16 16v160a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16Zm0 176H48V48h160v160Zm-32-80a8 8 0 0 1-8 8h-32v32a8 8 0 0 1-16 0v-32H88a8 8 0 0 1 0-16h32V88a8 8 0 0 1 16 0v32h32a8 8 0 0 1 8 8Z"></path></svg>
               </a>
               <a href="#" title="Update Template" class="update_main_template" data-main_template_id="{{$main_template_id}}" data-main_template_name="{{$main_template_name}}" data-status="{{$main_template_status}}" data-main_template_description="{{$main_template_description}}"><i class="bx bx-edit action_edit_icon"></i></a>
               <a href="#" class="delete_template" title="Delete Template" data-main_template_id="{{$main_template_id}}"><i class="bx bx-trash-alt action_delete_icon"></i></a>
    </div>           
  </div>
  </div>

  <section id="table-success" style="margin-top: -10px;">
  <div class="card _card">
    <div class="table-responsive">
      <div><table id="table-extended-success" class="table table-hover table_config">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Assignee</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration</th>
          </tr>
        </thead>
        <tbody>
              @php $i = 1; @endphp
              @foreach($task_list as $row)
          <tr class="template_task" data-main_template_id="{{$row->main_template_id}}" data-task_template_id="{{$row->id}}" data-task_title="{{$row->title}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-file_link="{{$row->file_link}}">
              <td>{{$i++}}</td>
              <td>{{$row->title}}</td>
              <td>{{$row->name}}</td>
              <td>
              @if($row->status != null)
              @if( $row->task_status == 'New')
                 <span class="_status status_open">{{$row->task_status}}</span>
              @elseif($row->task_status == 'In Progress')
                 <span class="_status status_in_progress">{{$row->task_status}}</span>
              @elseif($row->task_status == 'On Hold- Payment')
                 <span class="_status status_Onhold_payment">{{$row->task_status}}</span>
              @elseif($row->task_status == 'On Hold- Documents')
                 <span class="_status status_Onhold_Document">{{$row->task_status}}</span>
              @elseif($row->task_status == 'Completed')
                 <span class="_status status_Completed">{{$row->task_status}}</span>
              @elseif($row->task_status == 'Senior Review')
                 <span class="_status status_Senior_Review">{{$row->task_status}}</span>
              @elseif($row->task_status == 'Cancelled')
                 <span class="_status status_Cancelled">{{$row->task_status}}</span>
              @endif
              @endif
            </td>
              <td>{{($row->start_date != null) ? date('d-M-Y',strtotime($row->start_date)) : '' }}</td>
              <td>{{($row->end_date != null) ? date('d-M-Y',strtotime($row->end_date)) : '' }}</td>
              <td>{{$row->duration}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    </div>
  </div>
</section>
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
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>

<script src="{{ asset('js/scripts/pages/app-todo.js') }}"></script>
<script src="{{ asset('js/scripts/pages/task-common.js') }}"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
   
    $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });
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
    
 
  $(".project_select").select2({
    dropdownAutoWidth: true,
    width: '100%',
    placeholder:'Select Project'
  });
  fetch_client_project_list();
  fetch_template_task_list();
 
  // $('#list_template').css('background-color','#ff0');

  // new task description editor
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