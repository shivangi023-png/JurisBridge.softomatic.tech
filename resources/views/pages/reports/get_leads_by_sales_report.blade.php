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
    <h4 style="text-align:center;">Leads By Sales Report {{$FilterDate}}</h4>

    @foreach ($staff as $row)
    @if (sizeof($row->client_list))
    <?php $i = 1; ?>
    <h5 class="text-primary"><strong> Staff -{{$row->name}}</strong></h5>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Case No </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Client Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">No of Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Properity Type</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Latitude</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Longitude </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">City </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Cretaed By </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Address </th>
        </tr>
        @foreach ($row->client_list as $row1)
        <tr>
            <td style="text-align:right;width:5%;">{{ $i++ }}</td>
            <td style="width:10%;">{{ $row1->case_no }}</td>
            <td style="width:10%;">{{ $row1->client_name }}</td>
            <td style="text-align:center;width:5%;">{{ $row1->no_of_units }}</td>
            <td style="width:10%;">{{ $row1->property_type_name }}</td>
            <td style="width:10%;">{{ $row1->source_name }}</td>
            <td style="width:10%;">{{ $row1->latitude }}</td>
            <td style="width:10%;">{{ $row1->longitude }}</td>
            <td style="width:10%;">{{ $row1->city_name }}</td>
            <td style="width:10%;">{{ $row1->created_by_name }}</td>
            <td style="width:10%;">{{ $row1->address }}</td>
        </tr>
        @endforeach
    </table>
    @endif
    @endforeach
</body>