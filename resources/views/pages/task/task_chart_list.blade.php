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
 @endsection

 @section('content')

 <ul class="nav nav-tabs" role="tablist" style=" margin-bottom: -20px !important;">
     <li class="nav-item">
         <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Table View</a>
     </li>
     <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">List View</a>
     </li>
 </ul>

 <div class="tab-content">
     <div class="tab-pane active" id="tabs-1" role="tabpanel">
         <div class="todo-task-list list-group ps" style="height: 100%;">


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
                                     <h5 class="todo_title">{{$row->task_title}}</h5>
                                     <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                                 </div>
                                 <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                                 <h6>{{$row->project_name}}</h6>
                                 @if($row->priority_name != "Null" && $row->priority_name != null)
                                 <p><b>({{$row->priority_name}})</b></p>
                                 @endif
                                 @if($row->type_name != "Null" && $row->type_name != null)
                                 <p><b> ({{$row->type_name}})</b></p>
                                 @endif
                                 <p>Assignee Date:{{date('M d,Y',strtotime($row->assign_date))}} </p>
                                 @if($row->due_date != "Null" && $row->due_date != null)
                                 <p> Due Date:{{date('M d,Y',strtotime($row->due_date))}}</p>
                                 @endif
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
                                     <h5 class="todo_title">{{$row->task_title}}</h5>
                                     <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                                 </div>
                                 <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                                 <h6>{{$row->project_name}}</h6>
                                 @if($row->priority_name != "Null" && $row->priority_name != null)
                                 <p><b>({{$row->priority_name}})</b></p>
                                 @endif
                                 @if($row->type_name != "Null" && $row->type_name != null)
                                 <p><b> ({{$row->type_name}})</b></p>
                                 @endif
                                 <p>Assignee Date:{{date('M d,Y',strtotime($row->assign_date))}} </p>
                                 @if($row->due_date != "Null" && $row->due_date != null)
                                 <p> Due Date:{{date('M d,Y',strtotime($row->due_date))}}</p>
                                 @endif
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
                                     <h5 class="todo_title">{{$row->task_title}}</h5>
                                     <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                                 </div>
                                 <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                                 <h6>{{$row->project_name}}</h6>
                                 @if($row->priority_name != "Null" && $row->priority_name != null)
                                 <p><b>({{$row->priority_name}})</b></p>
                                 @endif
                                 @if($row->type_name != "Null" && $row->type_name != null)
                                 <p><b> ({{$row->type_name}})</b></p>
                                 @endif
                                 <p>Assignee Date:{{date('M d,Y',strtotime($row->assign_date))}} </p>
                                 @if($row->due_date != "Null" && $row->due_date != null)
                                 <p> Due Date:{{date('M d,Y',strtotime($row->due_date))}}</p>
                                 @endif
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
                                     <h5 class="todo_title">{{$row->task_title}}</h5>
                                     <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                                 </div>
                                 <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                                 <h6>{{$row->project_name}}</h6>
                                 @if($row->priority_name != "Null" && $row->priority_name != null)
                                 <p><b>({{$row->priority_name}})</b></p>
                                 @endif
                                 @if($row->type_name != "Null" && $row->type_name != null)
                                 <p><b> ({{$row->type_name}})</b></p>
                                 @endif
                                 <p>Assignee Date:{{date('M d,Y',strtotime($row->assign_date))}} </p>
                                 @if($row->due_date != "Null" && $row->due_date != null)
                                 <p> Due Date:{{date('M d,Y',strtotime($row->due_date))}}</p>
                                 @endif
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
                                     <h5 class="todo_title">{{$row->task_title}}</h5>
                                     <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                                 </div>
                                 <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                                 <h6>{{$row->project_name}}</h6>
                                 @if($row->priority_name != "Null" && $row->priority_name != null)
                                 <p><b>({{$row->priority_name}})</b></p>
                                 @endif
                                 @if($row->type_name != "Null" && $row->type_name != null)
                                 <p><b> ({{$row->type_name}})</b></p>
                                 @endif
                                 <p>Assignee Date:{{date('M d,Y',strtotime($row->assign_date))}} </p>
                                 @if($row->due_date != "Null" && $row->due_date != null)
                                 <p> Due Date:{{date('M d,Y',strtotime($row->due_date))}}</p>
                                 @endif
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
                                     <h5 class="todo_title">{{$row->task_title}}</h5>
                                     <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                                 </div>
                                 <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                                 <h6>{{$row->project_name}}</h6>
                                 @if($row->priority_name != "Null" && $row->priority_name != null)
                                 <p><b>({{$row->priority_name}})</b></p>
                                 @endif
                                 @if($row->type_name != "Null" && $row->type_name != null)
                                 <p><b> ({{$row->type_name}})</b></p>
                                 @endif
                                 <p>Assignee Date:{{date('M d,Y',strtotime($row->assign_date))}} </p>
                                 @if($row->due_date != "Null" && $row->due_date != null)
                                 <p> Due Date:{{date('M d,Y',strtotime($row->due_date))}}</p>
                                 @endif
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
                                     <h5 class="todo_title">{{$row->task_title}}</h5>
                                     <i class="bx bx-dots-horizontal-rounded todo_title"></i>
                                 </div>
                                 <h6>{!! substr(strip_tags($row->description),0,50) !!}</h6>
                                 <h6>{{$row->project_name}}</h6>
                                 @if($row->priority_name != "Null" && $row->priority_name != null)
                                 <p><b>({{$row->priority_name}})</b></p>
                                 @endif
                                 @if($row->type_name != "Null" && $row->type_name != null)
                                 <p><b> ({{$row->type_name}})</b></p>
                                 @endif
                                 <p>Assignee Date:{{date('M d,Y',strtotime($row->assign_date))}} </p>
                                 @if($row->due_date != "Null" && $row->due_date != null)
                                 <p> Due Date:{{date('M d,Y',strtotime($row->due_date))}}</p>
                                 @endif
                                 <p>{{date('M d,Y',strtotime($row->created_at))}}</p>
                             </div>
                             @endforeach
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="tab-pane" id="tabs-2" role="tabpanel">
         <section id="table-success">
             <div class="card _card">
                 <div class="table-responsive">
                     <div class="tbl_div">
                         <table id="table-extended-success" class="table table-hover table_config">
                             <thead>
                                 <tr>
                                     <th></th>
                                     <th>#</th>
                                     <th>Title</th>
                                     <th>Assignee</th>
                                     <th>Status</th>
                                     <th>Assignee Date</th>
                                     <th>Due Date</th>
                                     <th>Start Date</th>
                                     <th>End Date</th>
                                     <th>Duration</th>
                                     <th>Action</th>
                                 </tr>
                             </thead>
                             <tbody class="task_body">
                                 @php $i = 1; @endphp
                                 @foreach($data as $row)
                                 <tr>
                                     <td class="clickable collapsed" data-toggle="collapse" data-target="#subtask-list-{{$row->id}}" aria-expanded="false" aria-controls="subtask-list-{{$row->id}}"> <a style="font-size: 18px; !important" href="javascript:void(0)">+</a> </td>
                                     <td>{{$i++}}</td>
                                     <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{$row->title}}</td>
                                     <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{$row->assignee_name}}</td>
                                     <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">
                                         @if($row->status != null)
                                         @if( $row->status_name == 'New')
                                         <span class="_status status_open">{{$row->status_name}}</span>
                                         @elseif($row->status_name == 'In Progress')
                                         <span class="_status status_in_progress">{{$row->status_name}}</span>
                                         @elseif($row->status_name == 'On Hold- Payment')
                                         <span class="_status status_Onhold_payment">{{$row->status_name}}</span>
                                         @elseif($row->status_name == 'On Hold- Documents')
                                         <span class="_status status_Onhold_Document">{{$row->status_name}}</span>
                                         @elseif($row->status_name == 'Completed')
                                         <span class="_status status_Completed">{{$row->status_name}}</span>
                                         @elseif($row->status_name == 'Senior Review')
                                         <span class="_status status_Senior_Review">{{$row->status_name}}</span>
                                         @elseif($row->status_name == 'Cancelled')
                                         <span class="_status status_Cancelled">{{$row->status_name}}</span>
                                         @endif
                                         @endif
                                     </td>
                                     <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{($row->assign_date != null) ? date('d-M-Y',strtotime($row->assign_date)) : '' }}</td>
                                     <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{($row->due_date != null) ? date('d-M-Y',strtotime($row->due_date)) : '' }}</td>
                                     <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{($row->start_date != null) ? date('d-M-Y',strtotime($row->start_date)) : '' }}</td>
                                     <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{($row->end_date != null) ? date('d-M-Y',strtotime($row->end_date)) : '' }}</td>

                                     <td>{{$row->duration}}</td>
                                     <td class="ignore-click add_subtask_btn" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}">
                                         <a href="#" title="Create SubTask" class="add_subtask_btn ignore-click" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}">
                                             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" style="fill: #46b16a;margin-bottom: 12px;">
                                                 <path fill="ccc" d="M208 32H48a16 16 0 0 0-16 16v160a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16Zm0 176H48V48h160v160Zm-32-80a8 8 0 0 1-8 8h-32v32a8 8 0 0 1-16 0v-32H88a8 8 0 0 1 0-16h32V88a8 8 0 0 1 16 0v32h32a8 8 0 0 1 8 8Z"></path>
                                             </svg>
                                         </a>
                                     </td>
                                 </tr>
                                 @php $p = 1; @endphp
                                 @foreach($row->subtask_list as $row1)
                             <tbody id="subtask-list-{{$row->id}}" class="collapse">
                                 <tr class="table-light subtask_update_click" data-id="{{$row1->id}}" data-project_id="{{$row1->project_id}}" data-task_id="{{$row1->task_id}}" data-task_title="{{$row1->task_title}}" data-subtask_file_link="{{$row1->file_link}}" data-title="{{$row1->title}}" data-task_description="{{$row1->description}}" data-type="{{$row1->type}}" data-priority="{{$row1->priority}}" data-assignee="{{$row1->assignee}}" data-status="{{$row1->status}}" data-subtask_is_milestone="{{$row1->is_milestone}}" data-task_start_date="{{($row1->start_date != null) ? date('d/m/Y',strtotime($row1->start_date)) : '' }}" data-task_end_date="{{($row1->end_date != null) ? date('d/m/Y',strtotime($row1->end_date)) : '' }}">
                                     <td colspan="2" class="text-center"> {{$p++}}</td>
                                     <td>{{$row1->title}}</td>
                                     <td>{{$row1->name}}</td>
                                     <td>
                                         @if($row1->status != null)
                                         @if( $row1->status_name == 'New')
                                         <span class="_status status_open">{{$row1->status_name}}</span>
                                         @elseif($row1->status_name == 'In Progress')
                                         <span class="_status status_in_progress">{{$row1->status_name}}</span>
                                         @elseif($row1->status_name == 'On Hold- Payment')
                                         <span class="_status status_Onhold_payment">{{$row1->status_name}}</span>
                                         @elseif($row1->status_name == 'On Hold- Documents')
                                         <span class="_status status_Onhold_Document">{{$row1->status_name}}</span>
                                         @elseif($row1->status_name == 'Completed')
                                         <span class="_status status_Completed">{{$row1->status_name}}</span>
                                         @elseif($row1->status_name == 'Senior Review')
                                         <span class="_status status_Senior_Review">{{$row1->status_name}}</span>
                                         @elseif($row1->status_name == 'Cancelled')
                                         <span class="_status status_Cancelled">{{$row1->status_name}}</span>
                                         @endif
                                         @endif
                                     </td>
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
 <script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
 <script type="text/javascript">
     $(document).ready(function() {
         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });

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