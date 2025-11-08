<table class="table client-data-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>No of units</th>
            <th>Property type</th>
            <th>Quotations</th>
            <th>Follow-up</th>
            <th>Appointments</th>
            <th>Source</th>
            <th>Address</th>
            <th>City</th>
            <th>Pincode</th>
        </tr>
    </thead>

    <tbody>
        @if($client_list!='[]')
        @foreach($client_list as $row)
        <tr>
            <td><span class="client-customer">{{$row->case_no }}<small>({{$row->client_name }})</small></span>
            </td>
            <td><a class="text-success" href="javascript:void(0);" onclick="showContact(<?php $row->id ?>)">View</a></td>
            <td>{{$row->no_of_units }}</td>
            <td>{{$row->property_type_name }}</td>
            <td><a href="#" class="detailBtn" data-client_id="{{$row->id }}" data-detail="quotation" data-toggle="modal" data-target="#detailModal">{{$row->quotations }}</a></td>
            <td><a href="#" class="detailBtn" data-client_id="{{$row->id }}" data-detail="followup" data-toggle="modal" data-target="#detailModal">{{$row->followups }}</a></td>
            <td><a href="#" class="detailBtn" data-client_id="{{$row->id }}" data-detail="appointment" data-toggle="modal" data-target="#detailModal">{{$row->appointments }}</a></td>
            <td>{{$row->source_name }}</td>
            <td><small>{{$row->address }}</small></td>
            <td>{{$row->city_name }}</td>
            <td>{{$row->pincode }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td align="center" colspan="11">No data available in table</td>
        </tr>
        @endif
    </tbody>
</table>
</div>
<script>
    $(document).ready(function() {
        var dataListView = $(".client-data-table").DataTable({
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    });
</script>