<div class="table-responsive">
    <table class="table staff-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>id</th>
                <th>Service Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody class="data_div">
            <?php $i = 1; ?>
            @foreach($services as $val)
            <tr>
                <td><a href="#" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle glow btn-warning updateModal" data-tooltip="Edit" data-id="{{$val->id}}" data-name="{{$val->name}}" data-description="{{$val->description}}"><i class="bx bx-edit"></i></a>
                <td>{{$i++}}</td>
                <td>{{$val->name}}</td>
                <td>{{$val->description}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>