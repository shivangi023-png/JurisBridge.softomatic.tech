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
    <h4 style="text-align:center;">Attendance Report ({{$FilterDate}})</h4>
    @foreach ($staff as $val)
    @if (sizeof($val->tasks))
    <?php $a = 1; ?>

    <h5 class="text-primary"><strong> Staff -{{$val->name}}</strong></h5>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Total Task</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;" scope="col">Assigned</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Updated</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Completed</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Appointment</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Call Follow-up</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Visit Follow-up</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Check-in</th>
        </tr>
        <?php $size=sizeof($dates);
       
        ?>
        @for($d=0;$d<$size;$d++)
        <tr>
        <td style="text-align:center;width:5%;">{{$a++}}</td>
        <td style="text-align:right; width:10%;">{{date("d-M-Y",strtotime($dates[$d]))}}</td>
     
           
            <td style="text-align:center; width:10%;">{{$val->tasks[$dates[$d]]['total_task']}}</td>
            <td style="text-align:center;">{{$val->tasks[$dates[$d]]['total_assigned']}}</td>
            <td style="text-align:center;">{{$val->tasks[$dates[$d]]['total_updated']}}</td>
            <td style="text-align:center;">{{$val->tasks[$dates[$d]]['total_completed']}}</td>
            <td style="text-align:center;">{{$val->tasks[$dates[$d]]['total_appointment']}}</td>
            <td style="text-align:center;">{{$val->tasks[$dates[$d]]['total_followup_call']}}</td>
            <td style="text-align:center;">{{$val->tasks[$dates[$d]]['total_followup_visit']}}</td>
            <td style="text-align:center;">{{$val->tasks[$dates[$d]]['total_checkin']}}</td>
        </tr>
      
        @endfor
    </table>
    @endif
    @endforeach
</body>