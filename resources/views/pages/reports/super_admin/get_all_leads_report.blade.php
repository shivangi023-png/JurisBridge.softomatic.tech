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
    <h4 style="text-align:center;">All Leads Report {{$FilterDate}}</h4>
    <?php $i = 1; ?>
    @if (sizeof($company))
    @foreach ($company as $comp)
    <?php $i = 1; ?>
    @if (!empty($comp->client_list) && sizeof($comp->client_list)>0)
    <h4 style="text-align:left;">Company - {{$comp->company_name}}</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;">Sr. No. </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Case No </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Client Name</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;">No of Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Property Type</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Source </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Location</th>


            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Created By </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Address </th>
        </tr>
        @foreach ($comp->client_list as $row)
        <tr>
            <td style="text-align:center;width:5%;">{{ $i++ }}</td>
            <td style="text-align:left;width:10%;">{{ $row->case_no }}</td>
            <td style="text-align:left;width:10%;">{{ $row->client_name }}</td>
            <td style="text-align:center;width:5%;">{{ $row->no_of_units }}</td>
            <td style="text-align:left;width:10%;">{{ $row->property_type_name }}</td>
            <td style="text-align:center;width:10%;">
                @if($row->source_name=='Facebook')
                <img src="{{asset('images/source_icons/facebook.png')}}" alt="Facebook">
                @elseif($row->source_name=='Whatsapp group')
                <img src="{{asset('images/source_icons/whatsApp-group.png')}}" alt="Whatsapp group">
                @elseif($row->source_name=='Active Sales')
                <img src="{{asset('images/source_icons/active-sales.png')}}" alt="Active Sales">
                @elseif($row->source_name=='Client ref')
                <img src="{{asset('images/source_icons/client-ref.png')}}" alt="Client ref">
                @elseif($row->source_name=='Newspaper')
                <img src="{{asset('images/source_icons/newspaper.png')}}" alt="Newspaper">
                @elseif($row->source_name=='Franchise')
                <img src="{{asset('images/source_icons/franchise.png')}}" alt="Franchise">
                @elseif($row->source_name=='LinkedIn')
                <img src="{{asset('images/source_icons/linkedin.png')}}" alt="LinkedIn">
                @elseif($row->source_name=='Quora')
                <img src="{{asset('images/source_icons/quora.png')}}" alt="Quora">
                @elseif($row->source_name=='YouTube')
                <img src="{{asset('images/source_icons/youtube.png')}}" alt="YouTube">
                @elseif($row->source_name=='Google ads')
                <img src="{{asset('images/source_icons/googleAds.png')}}" alt="Google ads">
                @elseif($row->source_name=='Walk-in')
                <img src="{{asset('images/source_icons/walk-in.png')}}" alt="Walk-in">
                @endif
            </td>
            <td style="text-align:left;width:10%;">
                @if($row->latitude !='')
                {{ $row->latitude }}, {{ $row->longitude }}
                @endif
            </td>


            <td style="text-align:left;width:10%;">{{ $row->created_by_name }}</td>
            <td style="text-align:left;width:10%;">{{ $row->address }}</td>
        </tr>
        @endforeach
    </table>
    @endif
    @endforeach
    @endif

</body>