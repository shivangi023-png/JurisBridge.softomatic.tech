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
    <h4 style="text-align:center;">Pretty Cash Report {{$FilterDate}}</h4>
    @foreach ($staff as $stf)
    <?php $i = 1; ?>
    @if (sizeof($stf->pretty_cash_list))
    <?php $i = 1; ?>
    <h5 class="text-primary"><strong> Staff Name -{{$stf->name}}</strong></h5>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
    <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
    <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Date</th>
    <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;"  scope="col">Expense</th>
    <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;"  scope="col">Receipt</th>
    <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;"  scope="col">Balance Amount</th>
        </tr>
        @php
           $prevBalance = 0;
        @endphp
        @foreach ($stf->pretty_cash_list as $row)
        <tr>
          @php
                $expense = ($row->cash_type == 'expense') ? $row->amount : 0;
                $receipt = ($row->cash_type == 'receipt') ? $row->amount : 0;
                $balance = ($prevBalance + $receipt) - $expense;
            @endphp
                 <td style="text-align:center;width:5%;">{{ $i++ }}</td>
                 <td style="text-align:center;width:5%;">{{ date('d-m-Y',strtotime($row->date)) }}</td>
                 <td style="text-align:center;width:5%;">{{ ($expense == 0) ? '' : $expense }}</td>
                 <td style="text-align:center;width:5%;">{{ ($receipt == 0) ? '' : $receipt  }}</td>
                 <td style="text-align:right;width:5%;">{{ ($balance == 0) ? '' : $balance  }}</td>
        </tr>
        @php
            $prevBalance = $balance;
        @endphp     
        @endforeach
    </table>
    @endif
    @endforeach
</body>