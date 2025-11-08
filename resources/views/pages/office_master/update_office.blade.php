        <?php $i = 1; ?>
        @foreach($office_master as $val)
        <tr>
            <td><a href="#" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle glow btn-warning updateModal" data-tooltip="Edit" data-id="{{$val->id}}" data-name="{{$val->name}}"><i class="bx bx-edit"></i></a></td>
            <td>{{$i++}}</td>
            <td>{{$val->name}}</td>
        </tr>
        @endforeach
    