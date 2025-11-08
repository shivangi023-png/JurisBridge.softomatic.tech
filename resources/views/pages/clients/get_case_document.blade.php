<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Document Link</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1;@endphp
        @foreach($case_documents as $row)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$row->name}}</td>
            <td><a href="{{$row->document_link}}" target="_blank">{{$row->document_link}}</a></td>
            <td>{{$row->description}}</td>
            <td>{{date('d-m-Y',strtotime($row->date))}}</td>
        </tr>
        @endforeach
    </tbody>
</table>