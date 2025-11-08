 <table class="table table-bordered">
    <thead>
      <tr>
        <th>SrNo.</th>
        <th>Bill Id</th>
        <th>Bill Amount</th>
        <th>Mode of Payment</th>
        <th>Reference No</th>
        <th>Cheque No</th>
        <th>Payment</th>
        <th>Payment Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($list as $index=>$row)
      @php
          $bill_id = '';
         if(!empty($row->bill_id)){
            $id = explode('"',$row->bill_id);
            $bill_id = $id[1];
         }
         $bill_amt = '';
         if(!empty($row->bill_amt)){
            $amt = explode('"',$row->bill_amt);
            $bill_amt = $amt[1];
         }
      @endphp
      <tr>
        <td>{{++$index}}</td>
        <td>{{$bill_id}}</td>
        <td>{{$bill_amt}}</td>
        <td>{{$row->mode_of_payment }}</td>
        <td>{{$row->reference_no }}</td>
        <td>{{$row->cheque_no }}</td>
        <td>{{$row->payment }}</td>
        <td>{{date('d-m-Y',strtotime($row->payment_date))}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>