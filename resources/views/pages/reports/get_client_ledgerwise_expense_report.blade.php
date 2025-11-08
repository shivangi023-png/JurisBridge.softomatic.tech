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
    <h4 style="text-align:center;">Client Ledgerwise Expense Report {{$FilterDate}}</h4>
    <h4 style="text-align:right;color:green;">Total Expense : {{AppHelper::moneyFormatIndia($total)}}</h4>
    @if (sizeof($clients))
    @foreach ($clients as $val)
    <h5 class="text-primary"><strong> Client -{{$val->client_name}}</strong></h5>
    @foreach ($val->accounting_sub_heads as $heads)
    <?php $i = 1; ?>
    @if (sizeof($heads->expense_list))
    <?php $i = 1; ?>

    <h5><strong> Ledger -{{$heads->sub_heads}}</strong></h5>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">
                Expenses#
            </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Entry By</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Reimburse</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Amount</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Is Bill Attached</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Mode of payment</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Reference No</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Approval Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Approval By</th>
        </tr>
        @foreach ($heads->expense_list as $row)

        <tr>
            <td style="text-align:center;width:5%;">{{$i++}}</td>
            <td style="text-align:left;width:10%;">EXP{{$row->id}}</td>
            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($row->date))}}</td>
            <td style="text-align:left;width:10%;">{{$row->entry_by}}</td>
            @if($row->self == 'yes' || $row->self == 'YES')
            <td style="text-align:center;width:10%;">YES</td>
            @elseif($row->self == 'no' || $row->self == 'NO')
            <td style="text-align:center;width:10%;">NO</td>
            @endif
            <td style="text-align:right;width:10%;">{{AppHelper::moneyFormatIndia($row->amount)}}</td>
            @if($row->bill != "")
            <td style="text-align:center;width:5%;">YES</td>
            @else
            <td style="text-align:center;width:5%;">NO</td>
            @endif

            <td style="text-align:center;width:10%;">{{$row->mode_of_payment}}</td>
            <td style="text-align:right;width:10%;">{{$row->ref_no}}</td>
            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($row->approve_date))}}</td>
            <td style="text-align:left;width:10%;">{{$row->approved_by_name}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="3"></th>
            <th colspan="2" style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($heads->grand_total)}}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
    @endforeach
    @endif
</body>