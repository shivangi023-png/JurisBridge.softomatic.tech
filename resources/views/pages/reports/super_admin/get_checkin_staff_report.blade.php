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
    <h4 style="text-align:center;">Checkin Staff Report {{$FilterDate}}</h4>
    <?php $c = 1; ?>
    @foreach($staff as $stf)
    <h4 style="text-align:left;">Staff Name- {{$stf->name}}</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">SignIn TiME</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">SignOut Time</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Leave</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">CheckIn</th>
        </tr>
        @if(!empty($stf->checkin))
        <?php $checkin = $stf->checkin; ?>
        @for ($a = 0; $a < count($checkin); $a++) <tr>
            <td style="text-align:center;width:5%;">{{$c++}}</td>
            <td style="text-align:left;width:15%;">{{$checkin[$a]['date']}}</td>
            <td style="text-align:left;width:15%;">{{$checkin[$a]['signin_time']}}</td>
            <td style="text-align:right;width:15%;">{{$checkin[$a]['signout_time']}}</td>
            <td style="text-align:right;width:15%;">{{$checkin[$a]['leave_type']}}</td>
            <td style="text-align:center;width:15%;">
                <ul>
                    <li>{{$checkin[$a]['department_name']}}</li>
                    <li>{{$checkin[$a]['location']}}</li>
                    <li>{{$checkin[$a]['address']}}</li>
                </ul>
            </td>
            </tr>
            @endfor
            @endif
    </table>
    @endforeach
</body>