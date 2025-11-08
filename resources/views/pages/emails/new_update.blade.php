<!DOCTYPE html>
<style>
    .all_table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
  font-size:14px;
}

.all_table td, .all_table th {
  border: 1px solid #ddd;
  padding: 8px;
}

.all_table tr:nth-child(even){background-color: #f2f2f2;}

.all_table tr:hover {background-color: #ddd;}

.all_table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color:#006769;
  color: white;
}
</style>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Data</title>
</head>


<body>
<div>
 
            <h3>Today's leaves={{$no_of_leaves}}</h3>
      
    </div><br>
    @if(sizeof($leaves)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Status</th>
            </tr>

        </thead>
        <tbody>
            @foreach($leaves as $row)
            <tr>
                <td>{{$row->name}}</td>
                @if($row->status=='Approved')
                <td style="color:Green">{{$row->status}}</td>
                @elseif($row->status=='Rejected')
                <td style="color:red">{{$row->status}}</td>
                @else
                <td style="color:#FF8000">{{$row->status}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    <div>
        <h3>Today's Appointment={{sizeof($todays_appointment)}}</h3>
    </div>
   
    @if(sizeof($todays_appointment)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Meeting With</th>
                <th>Schedule By</th>
                <th>Place</th>
                <th>Time</th>
            </tr>

        </thead>
        <tbody>
            @foreach($todays_appointment as $row)
            <tr>
                <td>{{$row->case_no}}({{$row->client_name}})</td>
                <td>{{$row->meeting_with_name}}</td>
                <td>{{$row->name}}</td>
                <td>{{$row->place_name}}</td>
                <td>{{$row->meeting_time}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif

    <div>
        <h3>Today's Hearing={{sizeof($task_list)}}</h3>
    </div>
   
    @if(sizeof($task_list)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Task</th>
                <th>Project Name</th>
                <th>Description</th>
                <th>Attended By</th>
                <th>Office Name</th>
                <th>Office address</th>
            </tr>

        </thead>
        <tbody>
            @foreach($task_list as $row)
            <tr>
            <td>{{$row->title}}</td>
                <td>{{$row->project_name}}</td>
                <td>{{$row->description}}</td>
                <td>{{$row->assignee_name}}</td>
                <td>{{$row->dept_name}}</td>
                <td>{{$row->dept_address}}</th>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    <hr>
    
    <div>
        <center>
            <h3><u>Yesterday Statistics</u></h3>
        </center>
    </div>
   
    <div>
        <h3>Quotation Finalized={{$no_of_quotation_finalize}}</h3>
    </div>
   
    @if(sizeof($quotation_finalize)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Service</th>
                <th>Amount</th>
                <th>Units</th>
            </tr>

        </thead>
        <tbody>
            @foreach($quotation_finalize as $row)
            <tr>
                <td width="70%">{{$row->case_no}}({{$row->client_name}})</td>
                <td>{{$row->name}}</td>
                <td>{{number_format($row->amount,2)}}</td>
                <td align="center">{{$row->no_of_units}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    <div>
        <h3>Quotation Sent=</b>{{$no_of_quotation_sent}}</h3>
    </div>
    
    @if(sizeof($quotation_sent)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Quotation Sent</th>
                <th>Amount</th>
                <th>Units</th>
            </tr>

        </thead>
        <tbody>
            @foreach($quotation_sent as $row)
            <tr>
                <td>{{$row->case_no}}({{$row->client_name}})</td>
                <td align="center">{{$row->total}}</td>
                <td align="center">{{number_format($row->total_amt,2)}}</td>
                <td align="center">{{$row->no_of_units}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
    <div>
        <h3>Follow up by visit={{$no_of_visit_follow_up}}</h3>
    </div>
   
    @if(sizeof($visit_follow_up)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Visit By</th>
                <th>Discussion</th>
                <th>Next Followup</th>
            </tr>

        </thead>
        <tbody>
            @foreach($visit_follow_up as $row)
            <tr>
                <td width="35%">{{$row->case_no}}({{$row->client_name}})</td>
                <td>{{$row->visit_by}}</td>
                <td width="40%">{{$row->discussion}}</td>
                <td>{{date('d-m-Y',strtotime($row->next_followup_date))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <div>
        <h3>Follow up by call={{$no_of_call_follow_up}}</h3>
    </div>
    
    @if(sizeof($call_follow_up)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Call By</th>
                <th>Discussion</th>
                <th>Next Followup</th>
            </tr>

        </thead>
        <tbody>
            @foreach($call_follow_up as $row)
            <tr>
                <td width="35%">{{$row->case_no}}({{$row->client_name}})</td>
                <td>{{$row->call_by}}</td>
                <td width="40%">{{$row->discussion}}</td>
                <td>{{date('d-m-Y',strtotime($row->next_followup_date))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
      <div>
        <h3>Yesterday's Check-ins</h3>
    </div>
   
    @if(sizeof($checkins)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Office</th>
                <th>Client</th>
                <th>Visit By</th>
                <th>Location</th>
                <th>Address</th>
                <th>Time</th>
                <th>Discussion</th>
            </tr>

        </thead>
        <tbody>
            @foreach($checkins as $row)
            <tr>
                <td>{{$row->department_name}}</td>
               <td>{{$row->case_no}}({{$row->client_name}})</td>
                <td>{{$row->name}}</td>
                <td>{{$row->location}}</td>
                <td>{{$row->address}}</td>
                <td>{{date('h:i a',strtotime($row->created_at))}}</td>
                <td>{{$row->discussion}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    <div>
        <h3>New Leads={{$no_of_new_leads}}</h3>
    </div>
   
    @if(sizeof($new_leads)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>City</th>
            </tr>

        </thead>
        <tbody>
            @foreach($new_leads as $row)
            <tr>
                <td>{{$row->case_no}}({{$row->client_name}})</td>
                <td>{{$row->city_name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>

@endif
    <div>
        <h3>New leads follow up={{sizeof($call_follow_up_new_leads)}}</h3>
    </div>
    
    @if(sizeof($call_follow_up_new_leads)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Mobile No</th>
                <th>Contact By</th>
                <th>Discussion</th>
                <th>Next Followup</th>
            </tr>

        </thead>
        <tbody>
            @foreach($call_follow_up_new_leads as $row)
            <tr>
                <td width="35%">{{$row->name}}</td>
                <td>{{$row->mobile_no}}</td>
                <td>{{$row->contact_by}}</td>
                <td width="40%">{{$row->discussion}}</td>
                <td>{{date('d-m-Y',strtotime($row->next_followup_date))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    <div>
        <h3>Assigned Leads={{$no_of_assigned_leads}}</h3>
    </div>
    
    @if(sizeof($assigned_leads)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Assigned Staff</th>
            </tr>

        </thead>
        <tbody>
            @foreach($assigned_leads as $row)
            <tr>
                <td>{{$row->case_no}}({{$row->client_name}})</td>
                <td>{{$row->name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    <div>
        <h3>Unassigned Leads={{$no_of_unassigned_leads}}</h3>
    </div>
   
    @if(sizeof($unassigned_leads)>0)
    <table class="all_table" border="1" cellspacing="0">
        <thead>
            <tr>
                <th>Client Name</th>
            </tr>

        </thead>
        <tbody>
            @foreach($unassigned_leads as $row)
            <tr>
                <td>{{$row->case_no}}({{$row->client_name}})</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
     
   
</body>

</html>