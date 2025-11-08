 <table class="table table-bordered">
    <thead>
      <tr>
        <th>SrNo.</th>
        <th>Bill Id</th>
        <th>Bill Amount</th>
        <th>Payment Date</th>
        <th>Remark</th>
      </tr>
    </thead>
    <tbody>
      @foreach($list as $index=>$row)
      <tr>
        <td>{{++$index}}</td>
        <td>{{$row->invoice_id}}</td>
        <td>{{$row->amount}}</td>
        <td>{{date('d-m-Y',strtotime($row->date))}}</td>
        <td>{{$row->remark }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>