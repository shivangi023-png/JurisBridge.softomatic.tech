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
    <h4 style="text-align:center;">Quotation By Office Report -{{$FilterDate}}</h4>
    <h4 style="text-align:right;color:green;">Total Amount : {{AppHelper::moneyFormatIndia($total)}}</h4>

    @foreach ($staff as $row)
    <?php $i = 1; ?>
    @if (sizeof($row->quotation_list))
    <h5 class="text-primary"><strong> Staff -{{$row->name}}</strong></h5>
    <table autosize="1" cellspacing="0" cellpadding="3" border="1" width="100%">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:5%;">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;">Client</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;">Services</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:5%;">No of Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:15%;">Units per Amount</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:15%;">Total Amount </th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Finalize</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Send Date </th>
        </tr>

        @foreach ($row->quotation_list as $quot)
        <tr>
            <td style="text-align:center; width:5%;">{{$i++}}</td>
            <td style="text-align:left; width:20%;">{{$quot->case_no}} ({{$quot->client_name}})</td>
            <td style="text-align:left; width:20%;">{{$quot->service_name}}</td>
            <td style="text-align:center; width:10%;">{{$quot->no_of_units}}</td>
            <td style="text-align:right; width:10%;">{{AppHelper::moneyFormatIndia($quot->units_per_amount)}}</td>
            <td style="text-align:right; width:15%;">{{AppHelper::moneyFormatIndia($quot->amount)}}</td>
            <td style="text-align:center; width:10%;">{{$quot->finalize}}</td>
            <td style="text-align:right; width:10%;">{{date("d-M-Y", strtotime($quot->send_date))}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="3"></th>
            <th colspan="2" style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($row->grand_total)}}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
</body>