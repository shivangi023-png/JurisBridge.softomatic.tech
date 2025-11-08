<div style="height:400;overflow-y:auto;">
    <div class="table-responsive">
        <table class="table client-data-table wrap">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Lead Name</th>
                    <th>Assign To</th>
                    <th>Assign Date</th>
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                @foreach ($lead_list as $row)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->assign_staff_name}}</td>
                    <td>{{date('d-M-Y', strtotime($row->created_at))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>