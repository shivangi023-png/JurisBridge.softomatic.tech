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
    <h4 style="text-align:center;">Unassigned Leads Report {{$FilterDate}}</h4>
    <?php $i = 1; ?>
    @if (sizeof($company))
    <?php $i = 1; ?>
    @foreach ($company as $comp)
    @if (!empty($comp->client_list) && sizeof($comp->client_list)>0)

    <h4 style="text-align:left;">Company - {{$comp->company_name}}</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Case No </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Client Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">No of Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Properity Type</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">City </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Created By </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Address </th>
        </tr>
        @foreach ($comp->client_list as $row)
        <tr>
            <td style="text-align:right;width:5%;">{{$i++}}</td>
            <td style="width:10%;">{{$row->case_no}}</td>
            <td style="width:10%;">{{$row->client_name}}</td>
            <td style="text-align:center;width:5%;">{{$row->no_of_units}}</td>
            <td style="width:10%;">{{$row->property_type_name}}</td>
            <td style="width:10%;">{{$row->source_name}}</td>
            <td style="width:10%;">{{$row->city_name}}</td>
            <td style="width:10%;">{{$row->created_by_name}}</td>
            <td style="width:10%;">{{$row->address}}</td>
        </tr>
        @endforeach
    </table>
    @endif
    @endforeach
    @endif
</body>