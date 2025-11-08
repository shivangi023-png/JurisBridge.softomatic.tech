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
    <h4 style="text-align:center;">Cancelled Invoices {{$FilterDate}}</h4>
    @if (sizeof($company))
    @foreach ($company as $comp)
    <?php $i = 1; ?>
    @if (sizeof($comp->invoice_list))
    <h4 style="text-align:left;">Company - {{$comp->company_name}}</h4>
    <h4 style="text-align:right;">Total Amount - {{AppHelper::moneyFormatIndia($comp->grand_total)}}</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Invoice No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Client Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:20%;" scope="col">Service</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Amount</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Inovice Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Due Date</th>
        </tr>
        @foreach ($comp->invoice_list as $row)
        <tr>
            <td style="text-align:center; width:10%;">{{$i++}}</td>
            <td style="text-align:left;width:20%;">{{$row->short_code}}-{{str_pad($row->invoice_no, 5, '0', STR_PAD_LEFT)}}/{{date('Y', strtotime($row->bill_date))}}</td>
            <td style="text-align:left;width:20%;">{{$row->case_no}}({{$row->client_name}})</td>
            <td style="text-align:left;width:20%;">{{nl2br($row->service)}}</td>
            <td style="text-align:right;width:10%;">{{AppHelper::moneyFormatIndia($row->payable)}}</td>
            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($row->bill_date))}}</td>
            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($row->due_date))}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="3"></th>
            <th style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($comp->grand_total)}}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
    @endif
</body>