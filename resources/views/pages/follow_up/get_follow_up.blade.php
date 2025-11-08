<div class="action-dropdown-btn d-none">

    <div class="dropdown client-filter-action">

        <button class="btn border dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="filter_status">Filter Follow-up</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="client-filter-btn">
            <a type="button" href="javascript:void(0);" class="dropdown-item active_btn" data-value="finalized">finalized</a>
            <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="lead_closed">Not Interested</a>
            <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="this_month">
                This month</a>
            <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="this_week">
                This Week</a>
            <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="today">
                Today</a>
            <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="last_month">
                Last month</a>
        </div>
    </div>

</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table client-data-table wrap">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Client</th>
                        <th>Contact to</th>
                        <th>Method</th>
                        <th>Contact By</th>
                        <th>Follow-up Date</th>
                        <th>Next Follow-Up date</th>
                        <th>Finalized</th>
                        <th>Not Interested</th>
                        @if(session('role_id') == 1)
                        <th>Leads</th>
                        @endif
                        <th>Discussion</th>
                    </tr>
                </thead>
                <tbody>
                  
                    @foreach (json_decode($follow_up[0]) as $row)
                    <tr>
                        <td style="white-space: nowrap;">
                            <div class="client-action">
                                <button data-target="#saveFollowup" data-toggle="modal" type="button" class="btn btn-icon rounded-circle btn-success glow saveFollowupBtn" data-client_id="{{$row->client_id}}" data-client_name="{{$row->client_name}}" data-tooltip="Save"><i class="bx bx-plus"></i></button>
                                <button type="button" class="btn btn-icon rounded-circle btn-danger glow delete_follow_up" data-client_id="{{$row->id}}" data-tooltip="Delete"><i class="bx bx-trash"></i></button>
                                 <button type="button" class="btn btn-icon rounded-circle glow whatsapp_follow" style="background-color:#a5c90f" data-client_id="{{$row->client_id}}" data-tooltip="whatsapp"><i class="bx bxl-whatsapp"></i></button>
                            </div>
                        </td>
                        <td><a href="#" data-toggle="modal" data-target="#FollowUpHistory" class="followup_call_detail_btn" data-client_id="{{$row->client_id}}">{{$row->case_no}} ({{$row->client_name}})
                            </a></td>
                        <td>{{$row->contact_to_data}}</td>
                        <td>{{$row->method_data}}</td>
                        <td>{{$row->contact_by_name}}</td>
                        <td data-sort="{{strtotime($row->followup_date)}}">{{date('d-m-Y', strtotime($row->followup_date))}}</td>
                        <td data-sort="{{strtotime($row->next_followup_date)}}">
                            @if ($row->next_followup_date != '')
                            {{date('d-m-Y', strtotime($row->next_followup_date))}}
                            @endif
                        </td>
                        <td>{{$row->finalized}}</td>
                        <td>{{$row->lead_closed}}</td>
                        @if(session('role_id') == 1)
                        <td>{{$row->staff_name}}</td>
                        @endif
                        <td>{{$row->discussion}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    if ($(".client-data-table").length) {
        var dataListView = $(".client-data-table").DataTable({
            iDisplayLength: 50,
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            language: {
                search: "",
                searchPlaceholder: "Search Follow-up",
            },

            select: {
                style: "multi",
                selector: "td:first-child",
                items: "row",
            },
            responsive: {
                details: {
                    type: "column",
                    target: 0,
                },
            },
        });
    }
</script>