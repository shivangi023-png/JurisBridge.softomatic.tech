<div class="action-dropdown-btn d-none">
    <div class="dropdown client-filter-action">
        <button class="btn border dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="client-filter-btn">
            <a type="button" href="#" class="dropdown-item active_btn" data-value="finalize">Finalize</a>
            <a type="button" href="#" class="dropdown-item active_btn" data-value="pending">Pending</a>

        </div>
    </div>
    <div class="client-options">
        <a href="appointment-add" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true">
            <i class="bx bx-plus"></i>New Appointment</a>

    </div>
</div>
<div class="card">
    <div class="card-body">
       
        <div class="table-responsive">
            <table class="table client-data-table wrap">
                <thead>
                   
                    <tr>
                        <th>Date</th>
                        <th width="20%">Lead No</th>
                        <th>Prospects Name</th>
                        <th>Quotation Type</th>
                        <th>Units</th>
                        <th>CP Name</th>
                        <th>CP No</th>
                        <th>Quotation Send</th>
                        <th>Lead By</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{date('d-m-Y',strtotime($row->date))}}</td>
                        <td width="20%">{{$row->case_no}}</td>
                        <td>{{$row->client_name}}</td>
                        <td>{{$row->service_name}}</td>
                        <td>{{$row->service_name}}</td>
                        <td>{{$row->cp_name}}</td>
                        <td>{{$row->contact_no}}</td>
                        <td>{{$row->quotation_send}}</td>
                        <td>{{$row->lead_by}}</td>
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

            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            language: {
                search: "",
                searchPlaceholder: "Search Appointment"
            },

            select: {
                style: "multi",
                selector: "td:first-child",
                items: "row"
            },
            responsive: {
                details: {
                    type: "column",
                    target: 0
                },


            },
        });
    }

    // To append actions dropdown inside action-btn div
    var clientFilterAction = $(".client-filter-action");
    var clientOptions = $(".client-options");
    $(".action-btns").append(clientFilterAction, clientOptions);
</script>