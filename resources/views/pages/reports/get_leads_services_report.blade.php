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
    <h4 style="text-align:center;">Leads Services Report {{$FilterDate}}</h4>

    @foreach($staff as $row)
    @if (sizeof($row->client_list))
    <?php $i = 1; ?>
    <h4 style="text-align:left;">Staff - {{$row->name}}</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="3">
        <tr>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">Sr. No. </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Case No </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Lead</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:5%;" scope="col">No of Units</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Properity Type</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Services</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Source </th>
            
           
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assign To </th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Assigned Dt</th>
            <th style="background-color:#39498b; color:#fff;text-align:center;width:10%;" scope="col">Address </th>
        </tr>
        @foreach ($row->client_list as $row1)
        <?php
        if($row1->assigned_at!="")
        {
            $row1->assigned_at=date('d-M-Y',strtotime($row1->assigned_at));
        }
        ?>
        <tr>
            <td style="text-align:right;width:5%;">{{$i++}}</td>
            <td style="width:10%;">{{$row1->case_no}}</td>
            <td style="width:10%;">{{$row1->client_name}}</td>
            <td style="text-align:center;width:5%;">{{$row1->no_of_units}}</td>
            <td style="text-align:center;width:5%;">{{$row1->services}}</td>
            <td style="width:10%;">{{$row1->property_type_name}}</td>
            <td style="width:10%;">
            @if($row1->source_name=='Facebook')
                    <img src="{{asset('images/source_icons/facebook.png')}}" alt="Facebook">
                    @elseif($row1->source_name=='Whatsapp group')
                    <img src="{{asset('images/source_icons/whatsApp-group.png')}}" alt="Whatsapp group">
                    @elseif($row1->source_name=='Active Sales')
                    <img src="{{asset('images/source_icons/active-sales.png')}}" alt="Active Sales">
                    @elseif($row1->source_name=='Client ref')
                    <img src="{{asset('images/source_icons/client-ref.png')}}" alt="Client ref">
                    @elseif($row1->source_name=='Newspaper')
                    <img src="{{asset('images/source_icons/newspaper.png')}}" alt="Newspaper">
                    @elseif($row1->source_name=='Franchise')
                    <img src="{{asset('images/source_icons/franchise.png')}}" alt="Franchise">
                    @elseif($row1->source_name=='LinkedIn')
                    <img src="{{asset('images/source_icons/linkedin.png')}}" alt="LinkedIn">
                    @elseif($row1->source_name=='Quora')
                    <img src="{{asset('images/source_icons/quora.png')}}" alt="Quora">
                    @elseif($row1->source_name=='YouTube')
                    <img src="{{asset('images/source_icons/youtube.png')}}" alt="YouTube">
                    @elseif($row1->source_name=='Google ads')
                    <img src="{{asset('images/source_icons/googleAds.png')}}" alt="Google ads">
                    @elseif($row1->source_name=='Walk-in')
                    <img src="{{asset('images/source_icons/walk-in.png')}}" alt="Walk-in">
                    @endif
            </td>
     
            
            <td style="width:10%;">{{$row1->assign_to_name}}</td>
            <td style="width:10%;">{{$row1->assigned_at}}</td>
            <td style="width:10%;">{{$row1->address}}</td>
        </tr>
        @endforeach
    </table>
    @endif
    @endforeach
</body>