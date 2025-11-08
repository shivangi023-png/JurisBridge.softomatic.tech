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
    @if (sizeof($val->attendance_list))
    <?php $i = 1; ?>

    <h5 class="text-primary"><strong> Staff -{{$val->name}}</strong></h5>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Sign In Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Sign In Time</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;" scope="col">Sign In Location</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Sign Out Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Sign Out Time</th>
          <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;" scope="col">Sign Out Location</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:8%;" scope="col">Total working Hr</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Remark</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;" scope="col">Staus</th>
        </tr>
        <?php $size=sizeof($dates); ?>
        @for($d=0;$d<$size;$d++)
        @foreach ($val->attendance_list[$dates[$d]] as $row)
       
        <tr>
            <td style="text-align:center;width:5%;">{{$i++}}</td>
            <td style="text-align:right; width:10%;">{{date("d-M-Y", strtotime($row['signin_date']))}}</td>
            <td style="text-align:center; width:10%;">{{$row['signin_time']}}</td>
             <td style="text-align:center; width:25%;">{{$row['signin_location']}}<br>{{$row['signin_address']}}</td>
            @if(!empty($row['signout_date']))
            <td style="text-align:right; width:10%;">{{date("d-M-Y", strtotime($row['signout_date']))}}</td>
            <td style="text-align:center;width:10%;">{{$row['signout_time']}}</td>
            <td style="text-align:center; width:25%;">{{$row['signout_location']}}<br>{{$row['signout_address']}}</td>
            @else
            <td style="text-align:right;width:10%;"></td>
            <td style="text-align:center; width:10%;"></td>
            <td style="text-align:center; width:25%;"></td>
            @endif

       
           
            <td style="text-align:right;width:8%;">{{$row['total_working_hr']}}</td>
            <td style="text-align:right;width:10%;">{{$row['remark']}}</td>
            <td style="text-align:right;width:10%;">{{$row['check_ofc_status']}}</td>
        </tr>
        @endforeach
        @endfor
    </table>
    @endif
    @endforeach
</body>