<style>
    body {
        font-family: sans-serif;
    }

    table {
        font-family: calibri;
        font-size: 14px;
    }

    #logo {
        float: right;
        margin-bottom: 0px;
        margin-top: 0px;
    }
</style>

<body>
    <img width="50px" id="logo" src="images/invoice_img/logo.png">
    <h4 style="text-align:center;">Clientwise Quotation Finalized Report</h4>

    @foreach ($clients as $row)
    <?php $i = 1; ?>
    @if (sizeof($row->quotation_list))
  
           <?php if($row->source_name=='Facebook')

                $image='images/source_icons/facebook.png';
                    elseif($row->source_name=='Whatsapp group')
                    $image='images/source_icons/whatsApp-group.png';
                    elseif($row->source_name=='Active Sales')
                    $image='images/source_icons/active-sales.png';
                    elseif($row->source_name=='Client ref')
                    $image='images/source_icons/client-ref.png';
                    elseif($row->source_name=='Newspaper')
                    $image='images/source_icons/newspaper.png';
                    elseif($row->source_name=='Franchise')
                    $image='images/source_icons/franchise.png';
                    elseif($row->source_name=='LinkedIn')
                    $image='images/source_icons/linkedin.png';
                    elseif($row->source_name=='Quora')
                    $image='images/source_icons/quora.png';
                    elseif($row->source_name=='YouTube')
                    $image='images/source_icons/youtube.png';
                    elseif($row->source_name=='Google ads')
                    $image='images/source_icons/googleAds.png';
                    elseif($row->source_name=='Walk-in')
                    $image='images/source_icons/walk-in.png';
                   
                    ?>
    <h5 class="text-primary"><strong> Client -{{$row->case_no }}({{$row->client_name }})</strong> , Created Dt - {{date('d-M-Y', strtotime($row->created_at)) }}</h5>
    <h5 class="text-primary"><strong> Assign to -{{$row->assign_to_name}}</strong> , Assigned dt - {{date('d-M-Y', strtotime($row->assigned_at)) }} <img style="padding-top:10px" src="{{asset($image)}}" alt="Facebook"></h5>
    <table autosize="1" cellspacing="0" cellpadding="3" border="1" width="100%">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:5%;">Sr. No.</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;">Service</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Units per Amount</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:10%;">Total Amount </th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;">Send Date</th>
            <th style="background-color:#39498b; color:#fff;text-align:center; width:20%;">Finalized Date </th>
        </tr>

        @foreach ($row->quotation_list as $quot)
        <tr>
            <td style="text-align:center; width:5%;">{{$i++ }}</td>
            <td style="text-align:left; width:20%;">{{$quot->service_name }}</td>
            <td style="text-align:center; width:10%;">{{$quot->no_of_units }}</td>
            <td style="text-align:right; width:10%;">{{AppHelper::moneyFormatIndia($quot->units_per_amount) }}</td>
            <td style="text-align:right; width:10%;">{{AppHelper::moneyFormatIndia($quot->amount) }}</td>
            <td style="text-align:right; width:20%;">{{date("d-M-Y", strtotime($quot->send_date)) }}</td>
            <td style="text-align:right; width:20%;">{{date("d-M-Y", strtotime($quot->finalize_date)) }}</td>
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