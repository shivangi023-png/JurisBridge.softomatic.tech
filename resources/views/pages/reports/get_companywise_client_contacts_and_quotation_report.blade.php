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
    <h4 style="text-align:center;">Company-wise Client Contacts and Quotation Report</h4>

    @if (sizeof($client_list))
    <?php $i = 1; ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr.No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Client Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;" scope="col">Case No</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;" scope="col">Contact Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;" scope="col">Contact Number</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;" scope="col">Whatsapp Number</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;" scope="col">Email Id</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Quotation</th>
        </tr>
        @foreach ($client_list as $row)
        <tr>
            <td style="text-align:center;width:5%;">{{ $i++ }}</td>
            <td style="text-align:left;width:10%;">{{ $row->client_name }}</td>
            <td>{{ $row->case_no }}</td>
            <td>{{ $row->contact_name }}</td>
            <td>{{ $row->client_contact }}</td>
            <td>{{ $row->client_whatsapp }}</td>
            <td>{{ $row->client_email }}</td>
            <td style="text-align:center; width:10%;">{{ $row->total_quotations }}</td>
        </tr>
        @endforeach
    </table>
    @endif
</body>