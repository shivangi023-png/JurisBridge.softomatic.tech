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
<style>
    .datepicker
   {
    z-index:100000;
   }
</style>
{{-- page content --}}
@section('content')
<div class="project_tasks">
<div id="alert" class="mt-1"></div>
<div class="row">
<div class="col-lg-10"><h4 class="project_heading">{{$project_name}}</h4></div>

    <!-- new and inprogress  -->
              @if($project_status->id == 1 || $project_status->id == 2)
               <div class="col-lg-2"><h4 class="project_heading"> <a href="#" title="Create Task" class="new_task_btn" data-project_id="{{$project_id}}">
Task <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" style="fill: #46b16a;"><path fill="ccc" d="M208 32H48a16 16 0 0 0-16 16v160a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16Zm0 176H48V48h160v160Zm-32-80a8 8 0 0 1-8 8h-32v32a8 8 0 0 1-16 0v-32H88a8 8 0 0 1 0-16h32V88a8 8 0 0 1 16 0v32h32a8 8 0 0 1 8 8Z"></path></svg>
               </a></h4>
</div>
              @else
                <div class="col-lg-2"><h4 class="project_heading"> <a href="#" title="Create Task" class="new_task_alert" data-project_id="{{$project_id}}" data-status="{{$project_status->status}}" data-text_name="task">
Task <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" style="fill: #46b16a;"><path fill="ccc" d="M208 32H48a16 16 0 0 0-16 16v160a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16Zm0 176H48V48h160v160Zm-32-80a8 8 0 0 1-8 8h-32v32a8 8 0 0 1-16 0v-32H88a8 8 0 0 1 0-16h32V88a8 8 0 0 1 16 0v32h32a8 8 0 0 1 8 8Z"></path></svg>
               </a></h4>
</div>
              @endif


</div>
  
              
<ul class="nav nav-tabs" role="tablist" style=" margin-bottom: -20px !important;">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">List View</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Table View</a>
  </li>
</ul><!-- Tab panes -->

<div class="tab-content">
  <div class="tab-pane active" id="tabs-1" role="tabpanel">

   <section id="table-success">
  <div class="card _card">
    <div class="table-responsive">
      <div class="tbl_div"><table id="table-extended-success" class="table table-hover table_config">
        <thead>
          <tr>
            <th></th>
            <th>#</th>
            <th>Title</th>
            <th>Assignee</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="task_body">
              @php $i = 1; @endphp
              @foreach($task_list as $row)
          <tr class="project_task_tr_{{$row->id}}">
               @if(sizeof($row->subtask_list))
              <td class="clickable collapsed" data-toggle="collapse" data-target="#subtask-list-{{$row->id}}" aria-expanded="false" aria-controls="subtask-list-{{$row->id}}" > <a style="font-size: 18px; !important" href="javascript:void(0)">+</a> </td>
              @else
              <td></td>
              @endif
              <td>{{$i++}}</td>
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-office_id="{{$row->office_id}}" data-working_hr="{{$row->working_hr}}" data-time="{{$row->time}}">{{$row->title}}</td>
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-office_id="{{$row->office_id}}" data-working_hr="{{$row->working_hr}}" data-time="{{$row->time}}">{{$row->name}}</td>
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-office_id="{{$row->office_id}}" data-working_hr="{{$row->working_hr}}" data-time="{{$row->time}}">
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
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-office_id="{{$row->office_id}}" data-working_hr="{{$row->working_hr}}" data-time="{{$row->time}}">{{($row->start_date != null) ? date('d-M-Y',strtotime($row->start_date)) : '' }}</td>
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-office_id="{{$row->office_id}}" data-working_hr="{{$row->working_hr}}" data-time="{{$row->time}}">{{($row->end_date != null) ? date('d-M-Y',strtotime($row->end_date)) : '' }}</td>
              <td>{{$row->duration}}</td>

              <!-- create subTask -->
              <!-- new and inprogress  -->
              @if($project_status->id == 1 || $project_status->id == 2)
              <td class="ignore-click add_subtask_btn" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}">
               <a href="#" title="Create SubTask" class="add_subtask_btn ignore-click" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}">
                <div class="SubTaskBtn"><img src="images/SubTaskIcon.svg" width="14px" alt="sub task icon"></div>
               </a>
               @else
               <td class="ignore-click new_task_alert" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-status="{{$project_status->status}}" data-text_name="subtask">
               <a href="#" title="Create SubTask" class="new_task_alert ignore-click" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-status="{{$project_status->status}}" data-text_name="subtask">
                <div class="SubTaskBtn"><img src="images/SubTaskIcon.svg" width="14px" alt="sub task icon"></div>
               </a>
               @endif


               <a href="#" title="Delete Task" class="delete_task ignore-click" data-task_id="{{$row->id}}">
                <div class="DeleteTaskBtn"><img src="images/delete.svg" width="14px" alt="delete task"></div>
               </a>
             </td>
          </tr>
           @php $p = 1; @endphp
           @foreach($row->subtask_list as $row_1)
           <tbody id="subtask-list-{{$row->id}}" class="collapse">
            <tr class="table-light subtask_update_click" data-id="{{$row_1->id}}" data-project_id="{{$row_1->project_id}}" data-task_id="{{$row_1->task_id}}" data-task_title="{{$row_1->task_title}}" data-subtask_file_link="{{$row_1->file_link}}" data-title="{{$row_1->title}}" data-task_description="{{$row_1->description}}" data-type="{{$row_1->type}}" data-priority="{{$row_1->priority}}" data-assignee="{{$row_1->assignee}}" data-status="{{$row_1->status}}" data-subtask_is_milestone="{{$row_1->is_milestone}}" data-task_start_date="{{($row_1->start_date != null) ? date('d/m/Y',strtotime($row_1->start_date)) : '' }}" data-task_end_date="{{($row_1->end_date != null) ? date('d/m/Y',strtotime($row_1->end_date)) : '' }}">
              <td colspan="2" class="text-center"> {{$p++}}</td>
              <td>{{$row_1->title}}</td>
              <td>{{$row_1->name}}</td>
              <td>
                @if($row_1->status != null)
              @if( $row_1->task_status == 'New')
                 <span class="_status status_open">{{$row_1->task_status}}</span>
              @elseif($row_1->task_status == 'In Progress')
                 <span class="_status status_in_progress">{{$row_1->task_status}}</span>
              @elseif($row_1->task_status == 'On Hold- Payment')
                 <span class="_status status_Onhold_payment">{{$row_1->task_status}}</span>
              @elseif($row_1->task_status == 'On Hold- Documents')
                 <span class="_status status_Onhold_Document">{{$row_1->task_status}}</span>
              @elseif($row_1->task_status == 'Completed')
                 <span class="_status status_Completed">{{$row_1->task_status}}</span>
              @elseif($row_1->task_status == 'Senior Review')
                 <span class="_status status_Senior_Review">{{$row_1->task_status}}</span>
              @elseif($row_1->task_status == 'Cancelled')
                 <span class="_status status_Cancelled">{{$row_1->task_status}}</span>
              @endif
              @endif
              </td>
              <td>{{($row_1->start_date != null) ? date('d-M-Y',strtotime($row_1->start_date)) : '' }}</td>
              <td>{{($row_1->end_date != null) ? date('d-M-Y',strtotime($row_1->end_date)) : '' }}</td>
              <td>{{$row_1->duration}}</td>
              <td></td>
           </tr>
          </tbody>
          @endforeach

          @endforeach
        </tbody>
      </table>
    </div>
    </div>
  </div>
</section>


  </div>

  <div class="tab-pane" id="tabs-2" role="tabpanel">
    <div class="todo-task-list list-group ps" style="height: 100%; !important;">
            
<div class="task_list">

  <div style="gap: 10px;display: inline-flex;margin: 34px 10px 10px 8px;">

          <div class="task_div">
            <div class="todoHead bg_todo">
              <h4>New - {{sizeof($task_New)}}</h4>
              <i class="bx bx-plus-circle"></i>
            </div>
            <div class="todoMain">

             @foreach($task_New as $row)
              <div class="todoCBox">
                <div class="todoCBoxHead">
                  <h5 class="todo_title">{{$row->title}}</h5>
                  <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                </div>
                <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
              </div>
              @endforeach
              
            </div>
          </div>

          <div class="task_div">
            <div class="todoHead bg_in_process">
              <h4>In Progress -{{sizeof($task_in_progress)}}</h4>
              <i class="bx bx-plus-circle"></i>
            </div>
            <div class="todoMain">
              @foreach($task_in_progress as $row)
              <div class="todoCBox">
                <div class="todoCBoxHead">
                  <h5 class="todo_title">{{$row->title}}</h5>
                  <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                </div>
                <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
              </div>
              @endforeach
              
            </div>
          </div>

          <div class="task_div">
            <div class="todoHead bg_testing">
              <h4>On Hold- Documents - {{sizeof($task_Onhold_Document)}}</h4>
              <i class="bx bx-plus-circle"></i>
            </div>
            <div class="todoMain">

             @foreach($task_Onhold_Document as $row)
              <div class="todoCBox">
                <div class="todoCBoxHead">
                  <h5 class="todo_title">{{$row->title}}</h5>
                  <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                </div>
                <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
              </div>
              @endforeach
              
            </div>
          </div>


          <div class="task_div">
            <div class="todoHead bg_On_Hold_Payment">
              <h4>On Hold- Payment - {{sizeof($task_Onhold_payment)}}</h4>
              <i class="bx bx-plus-circle"></i>
            </div>
            <div class="todoMain">
              @foreach($task_Onhold_payment as $row)
              <div class="todoCBox">
                <div class="todoCBoxHead">
                  <h5 class="todo_title">{{$row->title}}</h5>
                  <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                </div>
                <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
              </div>
              @endforeach
            </div>
          </div>

            <div class="task_div">
            <div class="todoHead bg_completed">
              <h4>Completed - {{sizeof($task_Completed)}}</h4>
              <i class="bx bx-plus-circle"></i>
            </div>
            <div class="todoMain">
               @foreach($task_Completed as $row)
              <div class="todoCBox">
                <div class="todoCBoxHead">
                  <h5 class="todo_title">{{$row->title}}</h5>
                  <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                </div>
                <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
              </div>
              @endforeach
            </div>
          </div>

           <div class="task_div">
            <div class="todoHead bg_senior_review">
              <h4>Senior Review - {{sizeof($task_Senior_Review)}}</h4>
              <i class="bx bx-plus-circle"></i>
            </div>
            <div class="todoMain">
             @foreach($task_Senior_Review as $row)
              <div class="todoCBox">
                <div class="todoCBoxHead">
                  <h5 class="todo_title">{{$row->title}}</h5>
                  <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                </div>
                <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
              </div>
              @endforeach
            </div>
          </div>

          <div class="task_div">
            <div class="todoHead bg_cancelled">
              <h4>Cancelled - {{sizeof($task_Cancelled)}}</h4>
              <i class="bx bx-plus-circle"></i>
            </div>
            <div class="todoMain">
              @foreach($task_Cancelled as $row)
              <div class="todoCBox">
                <div class="todoCBoxHead">
                  <h5 class="todo_title">{{$row->title}}</h5>
                  <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                </div>
                <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
              </div>
              @endforeach
            </div>
          </div>


        </div></div>
        </div>
  </div>
</div>



</div>
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