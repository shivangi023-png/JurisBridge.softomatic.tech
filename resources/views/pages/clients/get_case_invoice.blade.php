<table class="table client-data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Invoice No.</th>
            <th>Document Link</th>
            <th>Bill Date</th>
            <th>Due Date</th>
            <th>Service</th>
            <th>Amount</th>
            <th>Discount</th>
            <th>GST per</th>
            <th>GST Amount</th>
            <th>CGST per</th>
            <th>CGST Amount</th>
            <th>IGST per</th>
            <th>IGST Amount</th>
            <th>Total</th>
            <th>Total Amount</th>
            <th>Seal</th>
            <th>Status</th>
            <th>Company</th>
            <th>Active</th>
            <th>Description</th>
            <th>Created Date</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1;@endphp
        @foreach($case_invoice as $row)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$row->invoice_no}}</td>
            <td><a href="{{$row->document_link}}" target="_blank">{{$row->document_link}}</a></td>
            <td>{{$row->bill_date}}</td>
            <td>{{$row->due_date}}</td>
            <td>{{$row->service}}</td>
            <td>{{$row->amount}}</td>
            <td>{{$row->discount}}</td>
            <td>{{$row->gst_per}}</td>
            <td>{{$row->gst_amount}}</td>
            <td>{{$row->cgst_per}}</td>
            <td>{{$row->cgst_amount}}</td>
            <td>{{$row->igst_per}}</td>
            <td>{{$row->igst_amount}}</td>
            <td>{{$row->total}}</td>
            <td>{{$row->total_amount}}</td>
            <td>{{$row->seal}}</td>
            <td>{{$row->status}}</td>
            <td>{{$row->company}}</td>
            <td>{{$row->active}}</td>
            <td>{{$row->description}}</td>
            <td>{{$row->created_at}}</td>
        </tr>
        @endforeach
    </tbody>
</table>