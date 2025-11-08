<table class="table staff-data-table wrap">
    <thead>
        <tr>
            <th>Action</th>
            <th>id</th>
            <th>Lead Type</th>
        </tr>
    </thead>
    <tbody class="data_div">
        <?php $i = 1; ?>
        @foreach($lead_type as $val)
        <tr>
            <td><a href="#" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle glow btn-warning updateModal" data-tooltip="Edit" data-id="{{$val->id}}" data-type="{{$val->type}}"><i class="bx bx-edit"></i></a>
            <td>{{$i++}}</td>
            <td>{{$val->type}}</td>
        </tr>
        @endforeach
    </tbody>
</table>