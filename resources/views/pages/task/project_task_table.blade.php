<table id="table-extended-success" class="table table-hover table_config">
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
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-time="{{$row->time}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{$row->title}}</td>
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-time="{{$row->time}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{$row->name}}</td>
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-time="{{$row->time}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">
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
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{($row->start_date != null) ? date('d-M-Y',strtotime($row->start_date)) : '' }}</td>
              <td class="project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_description="{{$row->description}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}">{{($row->end_date != null) ? date('d-M-Y',strtotime($row->end_date)) : '' }}</td>
              <td>{{$row->duration}}</td>

              <!-- create subTask -->
              <td class="ignore-click add_subtask_btn" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}">
                  <a href="#" title="Create SubTask" class="add_subtask_btn ignore-click" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}">
                <div class="SubTaskBtn"><img src="images/SubTaskIcon.svg" width="14px" alt="sub task icon"></div>
               </a>
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