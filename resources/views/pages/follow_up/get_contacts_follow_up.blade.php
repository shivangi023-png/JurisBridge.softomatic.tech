<div class="table-responsive">
    <h6>Contacts</h6>
    <table class="table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Whatsapp</th>
                <th>Email</th>

            </tr>
        </thead>
        <tbody class="client_body">
            <?php $i = 1; ?>
            @foreach ($client_contact as $row)
            <tr>
                <td>
                    <div class="checkbox checkbox-primary">
                        <input type="checkbox" id="colorCheckbox{{$row->id}}" value="{{$row->id}}" class="contact_to">
                        <label for="colorCheckbox{{$row->id}}"></label>
                    </div>
                </td>
                <td>{{$row->name}}</td>
                <td>{{$row->contact}}</td>
                <td>{{$row->whatsapp}}</td>
                <td>{{$row->email}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>