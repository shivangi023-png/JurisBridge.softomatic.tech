<div class="table-responsive">
    <table class="table staff-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>Id</th>
                <th>Department Name</th>
                <th>Short Name</th>
                <th>Address</th>
                <th>Geolocation</th>
                <th>Landmark</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($office_address as $val)
            <tr>
                <td><a href="#" class="updateModal btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-toggle="modal" data-target="#updateModal" data-office_id="{{$val->id}}" data-dept_name="{{$val->department_name}}" data-short_name="{{$val->short_name}}" data-address="{{$val->address}}" data-latitude="{{$val->latitude}}" data-longitude="{{$val->longitude}}" data-landmark="{{$val->landmark}}" data-tooltip="Edit"><i class="bx bx-edit"></i></a></td>
                <td>{{$i++}}</td>
                <td>{{$val->department_name}}</td>
                <td>{{$val->short_name}}</td>
                <td>{{$val->address}}</td>
                <td>{{$val->geolocation }}</td>
                <td>{{$val->landmark}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>