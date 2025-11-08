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
    <h4 style="text-align:center;">Consultation Fess Report {{$FilterDate}}</h4>

    @if (sizeof($clients))
    @foreach ($clients as $val)
    @if (sizeof($val->consultation_fee_list))
    <h5 style="text-align:right;color:#39498b;">Total Consultation Fees- {{AppHelper::moneyFormatIndia($total_fees)}}</strong></h5>
    <h5 class="text-primary"><strong> Client -{{$val->case_no}} ({{$val->client_name}})</strong></h5>
    <?php $i = 1; ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Receipt No</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Visit Type</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Fee</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Meeting Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Meeting Time</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Attanded By</th>
        </tr>
        @foreach ($val->consultation_fee_list as $row)
        <tr>
            <td style="text-align:center;width:5%;">{{$i++}}</td>
            <td style="text-align:left;width:15%;">{{$row->receipt_no}}</td>
            <td style="text-align:left;width:15%;">{{$row->place_name}}</td>
            <td style="text-align:right;width:15%;">{{AppHelper::moneyFormatIndia($row->fees)}}</td>
            <td style="text-align:right;width:15%;">{{date("d-M-Y", strtotime($row->meeting_date))}}</td>
            <td style="text-align:center;width:15%;">{{$row->meeting_time}}</td>
            <td style="text-align:left;width:20%;">{{$row->meetname}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="2"></th>
            <th style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($val->grand_total)}}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
    @endif
</body>