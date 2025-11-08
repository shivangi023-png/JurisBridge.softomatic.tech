<div style="height:400;overflow-y:auto;">
    <div class="table-responsive">
        <table class="table client-data-table wrap">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Client Name</th>
                    <th>Created By</th>
                    <th>Assign To</th>
                    <th>Assign Date</th>
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                @foreach ($client_list as $row)
                <tr>

                    <td>{{$i++}}</td>
                    <td>{{$row->client_case_no}}</td>
                    <td>{{$row->created_by_name}}</td>
                    <td>{{$row->assign_staff_name}}</td>
                    <td>{{date('d-M-Y', strtotime($row->created_at))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>