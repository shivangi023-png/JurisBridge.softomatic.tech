<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Data</title>
</head>


<body>
    @if(sizeof($staff)>0)
    @foreach($staff as $row)

    <h3>{{$row->name}}</h3>

    @if(!empty($row->total_followUp))
    <div><b>Follow up=</b>{{$row->total_followUp}}</div><br>

    <table border="1" cellspacing="0">
        <thead>
            <tr>
                <td>Client Name</td>
                <td>Date</td>
            </tr>

        </thead>
        <tbody>
            @foreach($row->follow_up as $row1)
            <tr>
                <td>{{$row1->case_no}}({{$row1->client_name}})</td>
                <td>{{$row1->followup_date}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    @if(!empty($row->total_assigned_leads))
    <div><b>assigned leads=</b>{{$row->total_assigned_leads}}</div><br>

    <table border="1" cellspacing="0">
        <thead>
            <tr>
                <td>Client Name</td>
                <td>Date</td>
                <td>Address</td>
            </tr>

        </thead>
        <tbody>
            @foreach($row->assigned_leads as $row1)
            <tr>
                <td>{{$row1->case_no}}({{$row1->client_name}})</td>
                <td>{{$row1->assigned_at}}</td>
                <td>{{$row1->address}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif
    @if(!empty($row->total_followUp_by_visit))
    <div><b>Follow up by Visit=</b>{{$row->total_followUp_by_visit}}</div><br>

    <table border="1" cellspacing="0">
        <thead>
            <tr>
                <td>Client Name</td>
                <td>Date</td>
            </tr>

        </thead>
        <tbody>
            @foreach($row->followUp_by_visit as $row1)
            <tr>
                <td>{{$row1->case_no}}({{$row1->client_name}})</td>
                <td>{{$row1->followup_date}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
    @endif


    @endforeach
    @endif


</body>

</html>