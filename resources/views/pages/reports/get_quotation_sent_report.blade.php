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
    <h4 style="text-align:center;">Quotation Sent Report {{$FilterDate}}</h4>
    @if (sizeof($quotation_list))
    <?php $i = 1; ?>

    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Sr. No. </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Client </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Assign to </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assigned Dt </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Services</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:8%;" scope="col">Amt/Unit</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Total Amt </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Finalized</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Send Date
         </th>
        </tr>
        @foreach ($quotation_list as $row)

        <tr>
            <td style="text-align:center;width:10%;">{{$i++}}</td>
            <td style="text-align:left;width:20%;">{{$row->case_no}} ({{$row->client_name}})</td>
            <td style="text-align:left;width:20%;">{{$row->assign_to_name}}</td>
            <td style="text-align:right;width:10%;">{{$row->assigned_at}}</td>
            <td style="text-align:center;width:10%;">{{$row->source_name}}</td>
            <td style="text-align:left;width:20%;">{{$row->service_name}}</td>

            <td style="text-align:center;width:5%;">{{$row->no_of_units}}</td>

            <td style="text-align:right;width:8%;">{{$row->units_per_amount}}</td>

            <td style="text-align:right;width:10%;">{{AppHelper::moneyFormatIndia($row->amount)}}</td>

            <td style="text-align:center;width:10%;">{{$row->finalize}}</td>

            <td style="text-align:right;width:20%;">{{date('d-M-Y', strtotime($row->send_date))}}</td>

        </tr>
        @endforeach
        <tr>
            <th colspan="3"></th>
            <th colspan="2" style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($grand_total)}}</h4>
            </th>
        </tr>
    </table>
    @endif
</body>