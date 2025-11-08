<table class="table staff-data-table wrap">
    <thead>
        <tr>
            <th>Action</th>
            <th>id</th>
            <th>Template Name</th>
            <th>Subject</th>
            <th>Message</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($template as $val)
        <tr>
            <td><a href="template_edit-{{$val->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
            </td>
            <td>{{$i++}}</td>
            <td>{{$val->template_name}}</td>
            <td>{{$val->subject}}</td>
            <td>{{$val->message}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<script src="{{asset('js/scripts/pages/staff.js')}}"></script>