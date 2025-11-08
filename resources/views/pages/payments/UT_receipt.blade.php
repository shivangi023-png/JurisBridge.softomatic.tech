<!DOCTYPE html>
<html>
<head>
<style>



.logo{
    float: left;
}





 body{
                    font-family: 'Ubuntu', sans-serif;
                }
                .main {
                   
                    margin:15;
                   
                   
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
                
              
                .abc {
                    border-collapse: collapse;
                }
                .abc th, td {
                    padding: 6px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                .footer {
    width: 100%;
    padding: 14px 12px;
    background-color: #000;
}
          .footer_tbl {
    margin: auto;
    border-collapse: collapse;
    text-align: center;
    font-family: Georgia, serif;
    color: white;
    width: 100%;
    padding: 14px 12px;
  }

  .footer_tbl td {
    padding: 5px 20px;
    border: none;
    background:black;
    color:white;
    font-size:16px;
  }

  .footer_tbl a {
    color: #fff;
    
    font-weight: bold;
  }

  .footer_tbl a:hover {
    text-decoration: underline;
  }

  .footer_tbl img.icon {
    vertical-align: middle;
    width: 20px;
    height: 20px;
    margin-right: 5px;
  }

  .footer_address {
    padding-top: 20px;
    color: white;
  }
  

                    #leftbox { 
                    float:left;  
                   
                    width:50%; 
                    
                } 
                
                #rightbox{ 
                    float:right; 
                 
                    width:50%; 
                    margin-top:0px;
                    padding-top:0px;
                    
                }
                 .header-top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 5px solid #d4ad7f;
      padding: 10px 0;
    }
    .header-top img.logo {
      height: 50px;
    }
    .header-contact {
      border-left: 2px solid #000;
      padding-left: 15px;
      font-size: 16px;
    }
    .header-contact div {
      margin-bottom: 5px;
    }
    .header-contact img.icon {
      height: 14px;
      vertical-align: middle;
      margin-right: 5px;
    }
</style>
            </head>
<body>

        

   <div class="header-top">
      <table class='header-contact' width='100%'>
        <tr>
            <td  style='border:none' width='75%'>
              <img src="{{ base_path(session('company_logo')) }}" data-holder-rendered="true" />
            </td>
            <td class='add' style='color:#fff;border:none;border-left: 2px solid #1e1d1cff' width='35%'>
                <p style='color:#000;padding-left: 10px;font-size: 15px;'>
                     <img src="{{ base_path('images/invoice_img/mailicon.jpg') }}" data-holder-rendered="true" /> legal@dearsociety.in
                </p>
                <p style='color:#000;padding-left: 10px;margin: 10px 0px 0px 0px;font-size: 15px;'><img src="{{url('images/invoice_img/callicon.jpg')}}" data-holder-rendered="true" /> +91 7020876285</p>
            </td>
        </tr>
    </table>
</div>
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