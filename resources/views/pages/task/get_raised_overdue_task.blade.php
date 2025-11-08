
    <table class="table raised-overdue-task-data-table wrap table-bordered">
        <thead>
          <tr>
              <th>Ovedue days</th>
              <th>Project</th>
              <th>Action</th>
              <th>Task Id</th>
              <th>Task</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Raised Status</th>
              <th>Status</th>

              <th>Type</th>
              <th>Priority</th>
              <th>Assignee</th>
          </tr>
        </thead>
        <tbody>
          @foreach($task_list as $row)
            <?php
              if($row->raised_status == 'rejected')
              {
                $spancls='text-danger';
              }
             else if($row->raised_status == 'approved')
              {
                $spancls='text-success';
              }
              else
              {
                $spancls='text-warning';
              }
            ?>
            <tr>
                <td align="center">{{$row->overdue_days}}</td>
                <td>{{$row->project_name}}</td>
                <td>
                <div class="invoice-action">
                <a href="#" title="Approve" data-toggle="modal" data-target="{{($row->raised_status == 'open') ?'#ApproveModal': '' }}" class="{{($row->raised_status == 'open') ?'approve_overdue_task': '' }}" data-raised_id="{{$row->raised_id}}" data-task_id="{{$row->id}}" data-end_date="{{$row->end_date}}" data-task_name="{{$row->title}}" data-project_name="{{$row->project_name}}"  data-raised_task_status="{{$row->status}}" data-raise_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}"><i class="bx bxs-check-square action_approve_icon"></i></a>

                <a href="#" class="{{($row->raised_status == 'open') ?'reject_overdue_task': '' }}" title="Reject" data-reject_overdue_task_id="{{$row->raised_id}}" ><i class="bx bxs-x-square action_cancel_icon"></i></a>
                </div>
                </td>
                <td>{{$row->id}}</td>
                <td>{{$row->title}}</td>
                <td width="10%">{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}</td>
                <td width="10%">{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}</td>
                <td><span class="<?php echo $spancls ?>"><b>{{$row->raised_status}}</b></span></td>
                <td>{{$row->status_name}}</td>
                <td>{{$row->type_name}}</td>
                <td>{{$row->priority_name}}</td>
                <td>{{$row->assignee_name}}</td>
            </tr>
          @endforeach
        </tbody>            
      </table>
