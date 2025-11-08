<body>
    <h4 style="text-align:center;">Daily Visit Report</h4>
    <h3>Today's staff on leave</h3>
    @foreach($staff as $stf)
    @if(!empty($stf->daily_leave) && sizeof($stf->daily_leave))
    <h5> {{$stf->name}}</h5>
    @foreach ($stf->daily_leave as $row)
    <ul>
        <li><small>{{$row->type}}</small></li>
    </ul>
    @endforeach
    @endif
    @endforeach

    <h3>Today's staff on visit</h3>
    @foreach($staff as $stf)
    @if(!empty($stf->office_visit) && sizeof($stf->office_visit))
    <h5> {{$stf->name}}</h5>
    @foreach ($stf->office_visit as $row)
    <ul>
        <li>Time:- <small>{{date("h:i:s", strtotime($row->created_at))}}</small></li>
        @if($row->department_name)
        <li>Department Name:-<small>{{$row->department_name}} ((Location:-{{$row->location}}),(Address:-{{$row->address}}))</small></li>
        @else
        <li>Client Name:- <small>{{$row->client_name}}(Location:-{{$row->location}})</small></li>
        @endif
        <li>Discussion:- <small>{{$row->discussion}}</small></li>

    </ul>
    @endforeach
    @endif
    @endforeach
</body>