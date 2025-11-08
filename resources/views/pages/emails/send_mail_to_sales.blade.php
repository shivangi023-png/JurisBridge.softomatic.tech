<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    @php
    $todayFollowupExist =$pendingFollowupExist= $newLeadExist=$pendingNewLeadExist=false;
    foreach ($company as $row1) {
    if (!empty($row1->todayFollowup) && sizeof($row1->todayFollowup) > 0) {
    $todayFollowupExist = true;
    }

    if(!empty($row1->pendingFollowup) && sizeof($row1->pendingFollowup)>0){
    $pendingFollowupExist = true;
    }
    if(!empty($row1->newLead) && sizeof($row1->newLead)>0){
    $newLeadExist=true;
    }
    if(!empty($row1->pendingNewLead) && sizeof($row1->pendingNewLead)>0){
    $pendingNewLeadExist=true;
    }
    }
    @endphp

    @if ($todayFollowupExist)
    <div>
        <center>
            <h3><u>FOLLOW UP FOR TODAY</u></h2>
        </center>
    </div>
    @endif
    @foreach ($company as $row1)
    @if(!empty($row1->todayFollowup) && sizeof($row1->todayFollowup)>0)
    <h3><strong>{{$row1->company_name}} : </strong></h3>
    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>FollowUp Date</th>
                <th>Next FollowUp Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($row1->todayFollowup as $row2)
            <tr>
                <td>{{$row2->case_no}} ({{$row2->client_name}})</td>
                <td>{{date("d-M-Y", strtotime($row2->followup_date))}}</td>
                <td>{{date("d-M-Y", strtotime($row2->next_followup_date))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endforeach

    @if ($pendingFollowupExist)
    <div>
        <center>
            <h3><u>PENDING FOLLOW UP</u></h2>
        </center>
    </div>
    @endif
    @foreach ($company as $row1)
    @if(!empty($row1->pendingFollowup) && sizeof($row1->pendingFollowup)>0)
    <h3><strong>{{$row1->company_name}} : </strong></h3>
    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>FollowUp Date</th>
                <th>Next FollowUp Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($row1->pendingFollowup as $row2)
            <tr>
                <td>{{$row2->case_no}} ({{$row2->client_name}})</td>
                <td>{{date("d-M-Y", strtotime($row2->followup_date))}}</td>
                <td>{{date("d-M-Y", strtotime($row2->next_followup_date))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endforeach

    @if ($newLeadExist)
    <div>
        <hr>
        <center>
            <h3><u>NEW LEADS ASSIGN TO YOU YESTERDAY</u></h2>
        </center>
    </div>
    @endif
    @foreach ($company as $row1)
    @if(!empty($row1->newLead) && sizeof($row1->newLead)>0)
    <h3><strong>{{$row1->company_name}} : </strong></h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Created date</th>
                <th>Assign Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($row1->newLead as $row2)
            <tr>
                <td>{{$row2->case_no}} ({{$row2->client_name}})</td>
                <td>{{date('d-M-Y',strtotime($row2->created_at))}}</td>
                <td> @if($row2->assigned_at!=''){{date('d-M-Y',strtotime($row2->assigned_at))}}
                    @endif
                </td>
                <td>NEW</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endforeach

    @if($pendingNewLeadExist)
    <div>
        <center>
            <h3><u>THE LEADS YOU HAVE NOT WORKED YET</u></h2>
        </center>
    </div>
    @endif
    @foreach ($company as $row1)
    @if(!empty($row1->pendingNewLead) && sizeof($row1->pendingNewLead)>0)
    <h3><strong>{{$row1->company_name}} : </strong></h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Created Date</th>
                <th>Assign Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($row1->pendingNewLead as $row2)
            <tr>
                <td>{{$row2->case_no}} ({{$row2->client_name}})</td>
                <td>{{date('d-M-Y',strtotime($row2->created_at))}}</td>
                <td> @if($row2->assigned_at!=''){{date('d-M-Y',strtotime($row2->assigned_at))}}@endif
                </td>
                <td>NEW</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endforeach

    @if(!empty($appointmentSchedule) && sizeof($appointmentSchedule)>0)
    <div>
        <center>
            <h3><u>APPOINTMENT SCHEDULE BY ME</u></h2>
        </center>
    </div>
    <table>
        <thead>
            <tr>
                <th>Client Name </th>
                <th>Meeting Type</th>
                <th>Scheduled By</th>
                <th>Attended By</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointmentSchedule as $arow)
            <tr>
                <td>{{$arow->case_no}}<br />({{$arow->client_name}})</td>
                <td>{{$arow->aname}}</td>
                <td>{{$arow->scheduled_by_staff}}</td>
                <td>{{$arow->meeting_with_staff}}</td>
                <td>{{date("d-m-Y", strtotime($arow->meeting_date))}}</td>
                <td>{{$arow->meeting_time}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(!empty($appointmentMeet) && sizeof($appointmentMeet)>0)
    <div>
        <center>
            <h3><u>APPOINTMENT MEETING WITH ME</u></h2>
        </center>
    </div>
    <table>
        <thead>
            <tr>
                <th>Client Name </th>
                <th>Meeting Type</th>
                <th>Scheduled By</th>
                <th>Attended By</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointmentMeet as $row2)
            <tr>
                <td>{{$row2->case_no}} ({{$row2->client_name}})</td>
                <td>{{$row2->aname}}</td>
                <td>{{$row2->scheduled_by_staff}}</td>
                <td>{{$row2->meeting_with_staff}}</td>
                <td>{{date("d-m-Y", strtotime($row2->meeting_date))}}</td>
                <td>{{$row2->meeting_time}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(!empty($newTask) && sizeof($newTask)>0)
    <div>
        <center>
            <h3><u>TASK FOR ME TODAY</u></h2>
        </center>
    </div>
    <table>
        <thead>
            <tr>
                <th>Task Id</th>
                <th>Title</th>
                <th>Project Name</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($newTask as $row2)
            <tr>
                <td>{{$row2->id}}</td>
                <td> {{$row2->title}}</td>
                <td>{{$row2->project_name}}</td>
                <td>{{$row2->task_status}}</td>
                <td>@if($row2->start_date!=NULL){{date('d-M-Y',strtotime($row2->start_date))}}@else<span></span>@endif</td>
                <td>@if($row2->end_date!=NULL){{date('d-M-Y',strtotime($row2->end_date))}}@else<span></span>@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
     @if(!empty($overdue_task_list) && sizeof($overdue_task_list)>0)
    <div>
        <center>
            <h3><u>Overdue Task</u></h2>
        </center>
    </div>
    <table>
        <thead>
            <tr>
                <th>Task Id</th>
                <th>Title</th>
                <th>Project Name</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($overdue_task_list as $row2)
            <tr>
                <td>{{$row2->id}}</td>
                <td> {{$row2->title}}</td>
                <td>{{$row2->project_name}}</td>
               <td>{{$row2->task_status}}</td>
                <td>@if($row2->start_date!=NULL){{date('d-M-Y',strtotime($row2->start_date))}}@else<span></span>@endif</td>
                <td>@if($row2->end_date!=NULL){{date('d-M-Y',strtotime($row2->end_date))}}@else<span></span>@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>

</html>