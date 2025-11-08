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
    <h4 style="text-align:center;">Client Not Follow-Up Report {{$FilterDate}}</h4>

    <?php $i = 1; ?>
    @if (sizeof( $follow_up_list))
    <?php $i = 1; ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Sr.No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:45%;" scope="col">Client Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:45%;" scope="col">Case No</th>
        </tr>
        @foreach ($follow_up_list as $row)
        <tr>
            <td style="text-align:center;width:10%;">{{ $i++ }}</td>
            <td style="text-align:left;width:45%;">{{ $row->client_name }}</td>
            <td style="text-align:left;width:45%;">{{ $row->case_no }}</td>
        </tr>
        @endforeach
    </table>
    @endif
</body>