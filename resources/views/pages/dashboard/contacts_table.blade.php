<div class="table-responsive">
    <table class="table contacts_datatable">
        <thead>
            <tr>
                <th>S.No.</th>
                <th class='text-center'>Name</th>
                <th class='text-center'>Contacts</th>
                <th class='text-center'>Whatsapp</th>
                <th class='text-center'>Email</th>
            </tr>
        </thead>

        <tbody>
            @if(sizeof($contacts))
            <?php $i = 1; ?>
            @foreach ($contacts as $row)
            <tr>
                <td class='text-center'><small>{{$i++}}</small></td>
                <td class='text-center'><small>{{$row->name}}</small></td>
                <td class='text-center'><small>{{$row->contact}}</small></td>
                <td class='text-center'><small>{{$row->whatsapp}}</small></td>
                <td class='text-center'><small>{{$row->email}}</small></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5">No records found!! </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<script>
    $(".contacts_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
</script>