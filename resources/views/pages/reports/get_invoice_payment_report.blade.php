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
    <h4 style="text-align:center;">Invoice/Payment Report {{$FilterDate}}</h4>
    <h4 style="text-align:center;">Grand Total={{AppHelper::moneyFormatIndia($all_total,2)}}</h4>
    @if (sizeof($bill_payment))
    @foreach ($bill_payment as $row1)
    @if (sizeof($row1->payment_list))
    <h4><span> Invoice No - {{session('short_code')}}-{{str_pad($row1->invoice_no, 5, '0', STR_PAD_LEFT)}}/{{date('Y', strtotime($row1->invoice_date))}}</span>
        <span> Invoice Date - {{date('d-M-Y', strtotime($row1->invoice_date))}}</span> ,
        <span> Amount - {{AppHelper::moneyFormatIndia($row1->invoice_amount)}}</span>
        <span> Status - {{$row1->status}}</span>
    </h4>
    <?php $i = 1; ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Client</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:6%;" scope="col">Mode of Payment</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:7%;" scope="col">Cheque No</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:7%;" scope="col">Reference No</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Bank Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">TDS</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Payment</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Payment Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Approved By</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Approved Date</th>
        </tr>
        @foreach ($row1->payment_list as $val)
        <tr>
            <td style="text-align:center;width:5%;">{{$i++}}</td>
            <td style="text-align:left;width:15%;">{{$val->case_no}}({{$val->client_name}})</td>
            <td style="text-align:center;width:6%;">{{$val->mode_of_payment}}</td>
            <td style="text-align:right;width:7%;">{{$val->cheque_no}}</td>
            <td style="text-align:right;width:7%;">{{$val->reference_no}}</td>
            <td style="text-align:right;width:10%;">{{$val->deposite_bank_name}}</td>
            @if($val->tds != 'null' || $val->tds != 0)
            <td style="text-align:center;width:5%;">{{$val->tds}}</td>
            @else
            <td style="text-align:center;width:5%;"></td>
            @endif
            <td style="text-align:right;width:10%;">{{AppHelper::moneyFormatIndia($val->payment)}}</td>
            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($val->payment_date))}}</td>
            @if($val->approve_date != '')
            <td style="text-align:left;width:15%;">{{$val->approved_by_name}}</td>
            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($val->approve_date))}}</td>
            @else
            <td style="text-align:right;width:15%;"></td>
            <td style="text-align:right;width:10%;"></td>
            @endif
        </tr>
        @endforeach
        <tr>
            <th colspan="5"></th>
            <th colspan="2" style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($row1->grand_total)}}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
    @endif
</body>