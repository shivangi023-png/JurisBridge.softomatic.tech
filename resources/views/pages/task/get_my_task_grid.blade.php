
<table class="table my-task-data-table wrap table-bordered">
        <thead>
          <tr>
              
              <th>Project</th>
             
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
               
                <td>{{$row->project_name}}</td>
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


