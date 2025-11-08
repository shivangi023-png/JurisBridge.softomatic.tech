<style>
    body {
        font-family: sans-serif;
    }

    table {
        font-family: calibri;
        font-size: 12px;
    }

    #logo {
        float: right;
        margin-bottom: 0px;
        margin-top: 0px;
    }
</style>

<body>
    <img width="50px" id="logo" src="images/invoice_img/logo.png">
    <h4 style="text-align:center;">OnHold Task Report </h4>

    @foreach($staff as $stf)
    <?php $i = 1; ?>
    @if (sizeof($stf->task))
    <?php $i = 1; ?>
    <h5 class="text-primary"><strong> Staff -{{$stf->name}} - {{sizeof($stf->task)}}</strong></h5>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:10%;" scope="col">Project Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:5%;" scope="col">Title</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:20%;" scope="col">Description</th>
            <th style="background-color:#39498b; color:#fff;text-align:right;width:10%;" scope="col">Start Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:right;width:10%;" scope="col">End Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:5%;" scope="col">Type</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:5%;" scope="col">Priority</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:5%;" scope="col">Status</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:5%;" scope="col">Assignee Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:left;width:10%;" scope="col">Department</th>
            <th style="background-color:#39498b; color:#fff;text-align:right;width:5%;" scope="col">Total Working Hour</th>
        </tr>
        @foreach($stf->task as $row)
        <tr>
            <td style="text-align:center;width:5%;">{{ $i++ }}</td>
            <td style="text-align:left;width:10%;">{{$row->project_name}}</td>
            <td style="text-align:left;width:5%;">{{$row->title}}</td>
            <td style="text-align:left;width:20%;"><?php echo nl2br($row->description) ?></td>
            <td style="text-align:right;width:10%;">{{ $row->start_date ? date('d-M-Y', strtotime($row->start_date)) : '' }}</td>
            <td style="text-align:right;width:10%;">{{ $row->end_date ? date('d-M-Y', strtotime($row->end_date)) : '' }}</td>
            <td style="text-align:left;width:5%;">{{$row->type_name}}</td>
            <td style="text-align:left;width:5%;">{{$row->priority_name}}</td>
            <td style="text-align:left;width:5%;">{{$row->status_name}}</td>
            <td style="text-align:left;width:5%;">{{$row->assignee_name}}</td>
            @if(!empty($row->dept_name))
            <td style="text-align:left;width:10%;">{{$row->dept_name}} ,({{ $row->dept_address }})<br>{{ $row->lat_long }}</td>
            @else
            <td style="text-align:left;width:10%;"></td>
            @endif
            <td style="text-align:right;width:5%;">{{$row->total_working_hr}}</td>
        </tr>
        @endforeach
    </table>
    @endif
    @endforeach
</body>