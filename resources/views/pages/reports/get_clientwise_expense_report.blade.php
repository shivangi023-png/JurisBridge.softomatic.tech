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
    <h4 style="text-align:center;">Clientwise Expense Report {{$FilterDate}}</h4>
    <h4 style="text-align:right;color:green;">Total Expense : {{AppHelper::moneyFormatIndia($total)}}</h4>

    @foreach ($clients as $val)
    <?php $i = 1; ?>
    @if (sizeof($val->expense_list))
    <?php $i = 1; ?>
    <h5 class="text-primary"><strong> Client -{{$val->case_no}} ({{$val->client_name}} )</strong></h5>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">
                Expenses#
            </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Ledger</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Entry By</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Reimburse</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Amount</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Is Bill Attached</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Mode of payment</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Reference No</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Approval Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Approval By</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Client</th>
        </tr>
        @foreach ($val->expense_list as $row)
        <tr>
            <td style="text-align:center;width:5%;">{{ $i++ }}</td>
            <td style="text-align:left;width:5%;">EXP{{ $row->id }}</td>
            <td style="text-align:right;width:5%;">{{ date('d-M-Y', strtotime($row->date)) }}</td>
            <td style="text-align:left;width:10%;">{{ $row->sub_heads }}</td>
            <td style="text-align:left;width:10%;">{{ $row->entry_by }}</td>
            @if($row->self == 'yes' || $row->self == 'YES')
            <td style="text-align:center;width:5%;">YES</td>
            @elseif($row->self == 'no' || $row->self == 'NO')
            <td style="text-align:center;width:5%;">NO</td>
            @endif
            <td style="text-align:right;width:10%;">{{ AppHelper::moneyFormatIndia($row->amount) }}</td>
            @if ($row->bill != "")
            <td style="text-align:center;width:5%;">YES</td>
            @else
            <td style="text-align:center;width:5%;">NO</td>
            @endif

            <td style="text-align:center;width:5%;">{{ $row->mode_of_payment }}</td>
            <td style="text-align:right;width:5%;">{{ $row->ref_no }}</td>
            <td style="text-align:right;width:10%;">{{ date('d-M-Y', strtotime($row->approve_date)) }}</td>
            <td style="text-align:left;width:10%;">{{ $row->approved_by_name }}</td>
            @if ($row->client_name != "")
            <td style="text-align:left;width:15%;">{{ $row->case_no }}({{ $row->client_name }})</td>
            @else
            <td style="text-align:left;width:15%;"></td>
            @endif
        </tr>
        @endforeach
        <tr>
            <th colspan="4"></th>
            <th colspan="2" style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{ AppHelper::moneyFormatIndia($val->grand_total) }}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
</body>