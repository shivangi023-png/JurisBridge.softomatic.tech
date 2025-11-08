<div class="table-responsive">
    <table class="table table-hover table_config dt-responsive wrap" style="width:100%; table-layout:fixed;">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th width="30%">Title</th>
                <th width="20%">Assignee</th>
                <th width="8%">Status</th>
                <th width="6%">Start Date</th>
                <th width="6%">End Date</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1;@endphp
            @foreach($projects_list as $row)
            <tr>
                <td>{{$i++}}</td>
                <td class="project_name"><a href="projects_task-{{$row->id}}">{{$row->project_name}}</a></td>
                <td>{{$row->assignee_list}}</td>
                <td>
                    @if($row->status != null)
                    @if( $row->status == 'New')
                    <span class="_status status_open">{{$row->status}}</span>
                    @elseif($row->status == 'In Progress')
                    <span class="_status status_in_progress">{{$row->status}}</span>
                    @elseif($row->status == 'On Hold- Payment')
                    <span class="_status status_Onhold_payment">{{$row->status}}</span>
                    @elseif($row->status == 'On Hold- Documents')
                    <span class="_status status_Onhold_Document">{{$row->status}}</span>
                    @elseif($row->status == 'Completed')
                    <span class="_status status_Completed">{{$row->status}}</span>
                    @elseif($row->status == 'Senior Review')
                    <span class="_status status_Senior_Review">{{$row->status}}</span>
                    @elseif($row->status == 'Cancelled')
                    <span class="_status status_Cancelled">{{$row->status}}</span>
                    @endif
                    @endif
                </td>
                <td>{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}</td>
                <td>{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}</td>

                <td>
                    <a href="#" title="Create Task From Template" class="create_task_from_template" data-project_id="{{$row->id}}">
                        <!-- <i class="bx bx-plus-circle action_template_task_icon"></i> -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style=" margin-bottom: 9px; color: #628cdd; " viewBox="0 0 20 20">
                            <path fill="currentColor" d="M0 7h4v4h2V7h4V5H6V1H4v4H0z" />
                            <path fill="currentColor" d="M4 13h2v2h12V7h-6V5h8v12H4z" />
                        </svg>
                    </a>
                    <!-- new and inprogress  -->
                    @if($row->status_id == 1 || $row->status_id == 2)
                    <a href="#" title="Create Task" class="new_task_btn" data-project_id="{{$row->id}}">
                        @else
                        <a href="#" title="Create Task" class="new_task_alert" data-project_id="{{$row->id}}" data-status="{{$row->status}}" data-text_name="task">
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" style="fill: #46b16a;margin-bottom: 12px;">
                                <path fill="ccc" d="M208 32H48a16 16 0 0 0-16 16v160a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16Zm0 176H48V48h160v160Zm-32-80a8 8 0 0 1-8 8h-32v32a8 8 0 0 1-16 0v-32H88a8 8 0 0 1 0-16h32V88a8 8 0 0 1 16 0v32h32a8 8 0 0 1 8 8Z"></path>
                            </svg>
                        </a>

                        <a href="#" title="Update Project" class="update_project" data-project_id="{{$row->id}}" data-project_name="{{$row->project_name}}" data-project_status_id="{{$row->status_id}}" data-project_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-project_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-project_staff_id="{{$row->staff_id}}" data-project_service_id="{{$row->service_id}}"><i class="bx bx-edit action_edit_icon"></i></a>

                        <a href="#" class="delete_project" title="Delete Project" data-project_id="{{$row->id}}"><i class="bx bx-trash-alt action_delete_icon"></i></a>
                        <a href="project_timeline-{{$row->id}}" class="project_timeline" data-project_id="{{$row->id}}"><i class="bx bx-time project_timeline_icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>