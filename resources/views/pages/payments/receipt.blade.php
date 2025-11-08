<!DOCTYPE html>
<html>
<head>
   <style>
                body{
                    font-family: 'Ubuntu', sans-serif;
                }
                .main {
                   
                    margin:15;
                   
                   
                }
                .head{
                    background-color:#000;
                    padding:2px 10px 2px 10px;
                    overflow:hidden;
                }
                .logo{
                    float:left;
                }
                .logo img{
                    width: 80px;
                    margin-top: 0px;
                }
                .add{
                    float:right;
                }
              
                .main h4{
                    text-align:center;
                    margin: 0px 0px 0px 0px;
                    font-size: 20px;
                }
                p.signtext{
                    text-align: right;
                    margin-left: 30px;
                    margin-bottom:0px;
                    margin-top:0px;
                }
                
                .footer{
                    margin-top:40px;
                }
                .footer p{
                    font-size:13px;
                    text-align:center;
                    border-bottom: 1px solid #faa41a;
                    padding-bottom:5px;
                    margin:0px;
                }
                .footer ul{
                    margin: 6px 0px 0px 0px;
                }
                .footer ul li{
                    display:inline-block;
                    font-size:13px;
                }
                .footer li{
                    padding-right:24px;
                }
                .footer li i{
                    color:#d58504;
                }
                .abc {
                    border-collapse: collapse;
                }
                .abc th, td {
                    padding: 6px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .footer-tbl
                {
                    border-collapse: collapse;
                    margin-bottom:30;
                }
                #leftbox { 
                    float:left;  
                   
                    width:50%; 
                    
                } 
                
                #rightbox{ 
                    float:right; 
                 
                    width:50%; 
                    
                } 
               
                </style>
</head>
<body>
    <table class='head' width='100%'>
        <tr>
            <td class='logo' style='border:none' width='75%'>
                <img width="{{ session('company_id') == 3 ? '150px' : '80px' }}" src="{{ session('company_logo') }}">
            </td>
            <td class='add' style='color:#fff;border:none;border-left: 2px solid #ffc524' width='35%'>
                <p style='color:#fff;padding-left: 10px;margin: 10px 0px 0px 0px;font-size: 15px;'>
                    {{ session('company_address') }}
                </p>
            </td>
        </tr>
    </table>

    <div class="main">
        <h4>Receipt</h4>
        <table width='100%' style='border:none'>
            <tr style='height:4px'>
                <td style='border:none;'>Receipt No. <strong>{{ $receipt_no }}</strong></td>
                <td style='border:none' align='right'>Date: <strong>{{ \Carbon\Carbon::parse($data->payment_date)->format('d') }}-{{ $month_name }}-{{ \Carbon\Carbon::parse($data->payment_date)->format('Y') }}</strong></td>
            </tr>
        </table>
        <table width='100%' style='border:none'>
            <tr>
                <td style='border:none'>Received From: <strong>{{ $data->client_name }}</strong>, {{ $data->address }}, {{ $city }}</td>
            </tr>
        </table>

        <table class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
            <tr>
                <th style='text-align:center;'>S. No.</th>
                <th style='text-align:center;'>Particulars</th>
                <th style='text-align:center;'>Amount</th>
            </tr>
            @php $count = 1; @endphp
            @foreach ($data->services as $service)
                <tr>
                    <td style='text-align:center;'>{{ $count++ }}</td>
                    <td style='text-align:left;font-family:hindi'>{!! $service['name'] !!}</td>
                    <td style='text-align:right;'>{{ $service['amount'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td style='text-align:right;'><strong>Total</strong></td>
                <td style='text-align:right;'><strong>{{ number_format($total_amt, 2) }}</strong></td>
            </tr>
            <tr>
                <td style='text-align:center;'><strong>In Words</strong></td>
                <td colspan='2'><strong>{{ $amount_in_words }}</strong></td>
            </tr>
        </table>

        <div id='leftbox'>
            <table class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                <tr>
                    <th colspan="3" style="text-align:center;">Mode</th>
                </tr>
                <tr>
                    <td width="20%">Cash</td>
                    <td width="20%" align="center">
                        <img width="{{ $data->mode_of_payment == 'cash' ? '30px' : '15px' }}" src="images/invoice_img/{{ $data->mode_of_payment == 'cash' ? 'checked' : 'unchecked' }}.png">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Cheque</td>
                    <td align="center">
                        <img width="{{ $data->mode_of_payment == 'cheque' ? '30px' : '15px' }}" src="images/invoice_img/{{ $data->mode_of_payment == 'cheque' ? 'checked' : 'unchecked' }}.png">
                    </td>
                    <td><strong>{{ $data->cheque_no }}</strong></td>
                </tr>
                <tr>
                    <td>Online</td>
                    <td align="center">
                        <img width="{{ $data->mode_of_payment == 'online' ? '30px' : '15px' }}" src="images/invoice_img/{{ $data->mode_of_payment == 'online' ? 'checked' : 'unchecked' }}.png">
                    </td>
                    <td><strong>{{ $data->reference_no }}</strong></td>
                </tr>
            </table><br>

            <table class='abc' width='100%' border='1' cellspacing='0' cellpadding='0'>
                <tr><th>Bill No</th><th>Payment</th></tr>
                {!! $bill_html !!}
            </table><br>
        </div>

        <div id='rightbox'>
            <p class='signtext'><img src="{{ base_path($image_path) }}" width='150px'></p>
        </div>
        <p class='signtext'>Authorised Signature</p>
    </div>
</body>
</html>
