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
                        <th>client name</th>
                        <th width="20%">Lead No</th>
                        <th>No of follow_up</th>
                        <th>Contact No</th>
                       <th>Email Id</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <?php  
                          $val=json_decode($row->contact, true);
                           $val1=json_decode($row->email, true);
                    ?>
                    <tr>
                         <td>{{$row->client_name}}</td>
                         <td width="20%">{{$row->case_no}}</td>
                         <td>{{$row->followup_count}}</td>
                         <td>{{$val[0]['mobile']}}</td>
                         <td>{{$val1[0]['email']}}</td>
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