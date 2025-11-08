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
    <h4 style="text-align:center;">Quotation Finalized Report {{$FilterDate}}</h4>
    @foreach($staff as $stf)
    @if (sizeof($stf->quotation_list))
    <?php $i = 1; ?>
    <h4 style="text-align:left;">Staff - {{$stf->name}}</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:25%;" scope="col">Client </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:25%;" scope="col">Assign To </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assigned dt </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Follow Up </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:15%;" scope="col">Services</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Amount/Unit</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Total Amt </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Send Dt</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Finalized Dt </th>
        </tr>
        @foreach ($stf->quotation_list as $row)
        <tr>
            <td style="text-align:center;width:5%;">{{$i++}}</td>

            <td style="text-align:left;width:25%;">{{$row->case_no}} {{$row->client_name}}</td>
            <td style="text-align:left;width:25%;">{{$row->assign_to_name}}</td>
            <td style="text-align:left;width:10%;">{{$row->assigned_at}}</td>
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
            <td style="text-align:center;width:10%;">{{$row->total_followup}}</td>

            <td style="text-align:left;width:15%;">{{$row->service_name}}</td>

            <td style="text-align:center;width:5%;">{{$row->no_of_units}}</td>

            <td style="text-align:right;width:10%;">{{$row->units_per_amount}}</td>

            <td style="text-align:right;width:10%;">{{AppHelper::moneyFormatIndia($row->amount)}}</td>

            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($row->send_date))}}</td>

            <td style="text-align:right;width:10%;">{{date('d-M-Y', strtotime($row->finalize_date))}}</td>

        </tr>

        @endforeach
        <tr>
            <th colspan="4"></th>
            <th colspan="2" style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($stf->grand_total) }}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
</body>