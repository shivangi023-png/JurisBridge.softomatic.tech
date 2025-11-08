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
    <h4 style="text-align:center;">Clientwise TDS Report {{$FilterDate}}</h4>

    @foreach ($clients as $val)
    @if (sizeof($val->tds_list))
    <h5 class="text-primary"><strong> Client -{{$val->case_no}}({{$val->client_name}})</strong></h5>
    <?php $i = 1; ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:40%;" scope="col">Invoice No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:30%;" scope="col">Invoice Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">TDS</th>
        </tr>
        @foreach ($val->tds_list as $row)
        <tr>
            <td style="text-align:center;width:10%;">{{$i++}}</td>
            <td style="text-align:left;width:40%;">{{session('short_code')}}-{{str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT)}}/{{date('Y', strtotime($row->bill_date))}}</td>
            <td style="text-align:right;width:30%;">{{date('d-M-Y', strtotime($row->bill_date))}}</td>
            <td style="text-align:right;width:20%;">{{$row->tds}}</td>
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
</body>