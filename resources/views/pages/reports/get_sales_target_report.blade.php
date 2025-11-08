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
    <h4 style="text-align:center;">Sales Target Report - {{$FilterDate}}</h4>

    @foreach($staff as $stf)
    @if (sizeof($stf->payment_details))
    <?php $i = 1; ?>
    <h4 style="text-align:left;">Staff - {{$stf->name}}</h4>
    <h4 style="text-align:right;">Total Amount - {{AppHelper::moneyFormatIndia($stf->total_payment)}}</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Client Name </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Service Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Invoice No</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Amount</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Payment Date </th>
        </tr>
        @foreach ($stf->payment_details as $row)

        <tr>
            <td style="text-align:right;width:5%;">{{$i++}}</td>
            <td style="width:10%;">{{$row->client_name}}</td>
            <td></td>
            <td style="text-align:left;width:5%;">{{$row->invoice_prefix}}</td>
            <td style="text-align:right;width:5%;">{{AppHelper::moneyFormatIndia($row->payment)}}</td>
            <td style="text-align:center;width:5%;">{{date('m-d-Y',strtotime($row->payment_date))}}</td>

        </tr>
        @endforeach
    </table>
    @endif
    @endforeach
</body>