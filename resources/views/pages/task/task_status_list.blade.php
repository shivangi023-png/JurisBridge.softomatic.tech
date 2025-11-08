<style>
    .todo-task-list {
        overflow-y: scroll !important;
    }
</style>
<div class="TaskHearingBox" style="padding-left:14px;padding-right:40px">
    <div class="pull-right">
        <h6 style="color:#5a8dee">Total Task : {{sizeof($task_list)}}</h6>
    </div><br>
    @foreach($task_list as $row)
    <div class="row project_task" data-project_id="{{$row->project_id}}" data-task_id="{{$row->id}}" data-task_title="{{$row->title}}" data-file_link="{{$row->file_link}}" data-task_type="{{$row->type}}" data-task_priority="{{$row->priority}}" data-task_assignee="{{$row->assignee}}" data-task_status="{{$row->status}}" data-task_is_milestone="{{$row->is_milestone}}" data-task_start_date="{{($row->start_date != null) ? date('d/m/Y',strtotime($row->start_date)) : '' }}" data-task_end_date="{{($row->end_date != null) ? date('d/m/Y',strtotime($row->end_date)) : '' }}" data-task_description="{{$row->description}}" data-office_id="{{$row->office_id}}" data-working_hr="{{$row->working_hr}}">
        <div class="col-sm-9 HearingContent">
            <h6>{{$row->project_name}}</h6>
            <h4>{{$row->title}}</h4>
            <p><?php echo nl2br($row->description) ?></p>
            @if($row->dept_address != null)
            <h5>
            <i class="bx bx-map"></i>&nbsp;
            @if($row->lat_long != null)
            <!-- <a href="https://www.google.com/maps/?q=" target="_blank"> -->
            @else
            <!-- <a href="#"> -->
            @endif    
               <a href="https://www.google.com/maps/search/?api=1&query={{$row->dept_address}}" target="_blank"> {{$row->dept_address}}</a>
            </h5>
            @endif
            @if($row->priority_name!='' || $row->status_name!='')
            <div class="HLowBtn">
                @if($row->priority_name!='')
                <a class="LowBtn" href="#">{{$row->priority_name}}</a>
                @endif
                @if($row->status_name!='')
                <a class="NewBtn" href="#">{{$row->status_name}}</a>
                @endif
            </div>
            @endif
        </div>
        <div class="col-sm-3">
            @if($row->start_date!='' || $row->end_date!='')
            <div class="HDate">
                <p><i class="bx bx-time"></i>&nbsp;{{date('d-M-Y',strtotime($row->start_date))}} | {{date('d-M-Y',strtotime($row->end_date))}}</p>
            </div>
            @endif
            <div class="HAssignee">
                <p><i class="bx bx-user-circle"></i>&nbsp;{{$row->assignee_name}}</p>
            </div>
            <div class="HearingTWDay">
                <p><i class="bx bx-time"></i>&nbsp;{{$row->total_working_hr}}</p>
            </div>
        </div>
    </div>
    <hr>
    @endforeach

</div>