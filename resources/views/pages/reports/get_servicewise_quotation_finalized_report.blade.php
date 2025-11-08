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
    <h4 style="text-align:center;">Servicewise Quotation Finalized Report -{{$FilterDate}}</h4>
    <h4 style="text-align:right;color:green;">Total Amount :{{AppHelper::moneyFormatIndia($total) }}</h4>

    @foreach ($task_id as $row)
    <?php $i = 1; ?>
    @if (sizeof($row->quotation_list))
    <h5 class="text-primary"><strong> Service -{{$row->name}}</strong></h5>
    <table autosize="1" cellspacing="0" cellpadding="3" border="1" width="100%">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:5%;">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:25%;">Client</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:25%;">Assign to</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:25%;">Assigned Dt</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;">Source</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Follow Up</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">No of Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Units per Amount</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Total Amount </th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:15%;">Send Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:15%;">Finalized Date </th>
        </tr>

        @foreach ($row->quotation_list as $quot)
        <tr>
            <td style="text-align:center; width:5%;">{{ $i++}}</td>
            <td style="text-align:left; width:25%;">{{ $quot->case_no}}({{ $quot->client_name}})</td>
            <td style="text-align:left; width:25%;">{{ $quot->assign_to_name}}</td>
            <td style="text-align:left; width:25%;">{{ $quot->assigned_at}}</td>
            <td style="text-align:center;width:10%;">
            @if($quot->source_name=='Facebook')
                    <img src="{{asset('images/source_icons/facebook.png')}}" alt="Facebook">
                    @elseif($quot->source_name=='Whatsapp group')
                    <img src="{{asset('images/source_icons/whatsApp-group.png')}}" alt="Whatsapp group">
                    @elseif($quot->source_name=='Active Sales')
                    <img src="{{asset('images/source_icons/active-sales.png')}}" alt="Active Sales">
                    @elseif($quot->source_name=='Client ref')
                    <img src="{{asset('images/source_icons/client-ref.png')}}" alt="Client ref">
                    @elseif($quot->source_name=='Newspaper')
                    <img src="{{asset('images/source_icons/newspaper.png')}}" alt="Newspaper">
                    @elseif($quot->source_name=='Franchise')
                    <img src="{{asset('images/source_icons/franchise.png')}}" alt="Franchise">
                    @elseif($quot->source_name=='LinkedIn')
                    <img src="{{asset('images/source_icons/linkedin.png')}}" alt="LinkedIn">
                    @elseif($quot->source_name=='Quora')
                    <img src="{{asset('images/source_icons/quora.png')}}" alt="Quora">
                    @elseif($quot->source_name=='YouTube')
                    <img src="{{asset('images/source_icons/youtube.png')}}" alt="YouTube">
                    @elseif($quot->source_name=='Google ads')
                    <img src="{{asset('images/source_icons/googleAds.png')}}" alt="Google ads">
                    @elseif($quot->source_name=='Walk-in')
                    <img src="{{asset('images/source_icons/walk-in.png')}}" alt="Walk-in">
                    @endif
        </td>
            <td style="text-align:center; width:10%;">{{ $quot->total_followup}}</td>
            <td style="text-align:center; width:10%;">{{ $quot->no_of_units}}</td>
            <td style="text-align:right; width:10%;">{{ AppHelper::moneyFormatIndia($quot->units_per_amount)}}</td>
            <td style="text-align:right; width:10%;">{{ AppHelper::moneyFormatIndia($quot->amount)}}</td>
            <td style="text-align:right; width:15%;">{{ date("d-M-Y", strtotime($quot->send_date))}}</td>
            <td style="text-align:right; width:15%;">{{ date("d-M-Y", strtotime($quot->finalize_date))}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="3"></th>
            <th colspan="2" style="text-align:left;">
                <h4 style="color:#5a8dee;">Grand Total</h4>
            </th>
            <th style="text-align:right;">
                <h4 style="color:#5a8dee;">{{AppHelper::moneyFormatIndia($row->grand_total)}}</h4>
            </th>
        </tr>
    </table>
    @endif
    @endforeach
</body>