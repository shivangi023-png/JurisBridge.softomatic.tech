
    <table class="table overdue-task-data-table wrap table-bordered">
        <thead>
          <tr>
              <th>Ovedue days</th>
              <th>Project</th>
              <th>Action</th>
              <th>Task Id</th>
              <th>Task</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Status</th>
              <th>Type</th>
              <th>Priority</th>
              <th>Assignee</th>
          </tr>
        </thead>
        <tbody>
          @foreach($task_list as $row)
            <tr>
                <td align="center">{{$row->overdue_days}}</td>
                <td>{{$row->project_name}}</td>

                <td>
                    @if($row->raise_count==0)
                    <button type="button" data-toggle="modal" data-target="#raiseModal" class="btn btn-icon btn-light-success mr-1 mb-1 raise_overdue" fdprocessedid="9fjj3r" data-task_id="{{$row->id}}" data-task_name="{{$row->title}}" data-project_name="{{$row->project_name}}">
                    <i class="bx bx-right-arrow-alt"></i></button>
                    @else
                    <button type="button" data-toggle="modal" data-target="#raiseModal" class="btn btn-icon btn-light-success mr-1 mb-1 raise_overdue" fdprocessedid="9fjj3r" data-task_id="{{$row->id}}" data-task_name="{{$row->title}}" data-project_name="{{$row->project_name}}" disabled>
                    <i class="bx bx-right-arrow-alt"></i></button>
                    @endif
                </td>
                <td>{{$row->id}}</td>
                <td>{{$row->title}}</td>
                <td width="10%">{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}</td>
                <td width="10%">{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}</td>
                <td>{{$row->status_name}}</td>
                <td>{{$row->type_name}}</td>
                <td>{{$row->priority_name}}</td>
                <td>{{$row->assignee_name}}</td>
            </tr>
          @endforeach
        </tbody>            
      </table>


