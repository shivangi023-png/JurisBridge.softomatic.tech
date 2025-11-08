<style>
    div.dataTables_wrapper div.dataTables_filter {
        margin-left: -22px !important;
        margin-top: 1rem !important;
    }
</style>
<div class="table-responsive">
    <table class="table client-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>Name</th>
                @if(session('role_id')==1 || session('role_id')==3)
                <th>Finalized</th>
                <th>Future Invoices</th>
                <th>Invoices Raised</th>
                <th>Payment</th>
                <th>Additional Invoices</th>
                <th>Payment</th>
                <th>Dues</th>
                <th>Unapproved Payment</th>
                @endif
            </tr>
        </thead>

        <tbody>
            <?php $total_dues = 0;
            $total_unapproved = 0;
            ?>
            @foreach($client_list as $row)
            <?php

            $total_dues += $row->due_amt;
            $total_unapproved += $row->unapproved_payment;
            ?>
            <tr>
                <td style="white-space: nowrap;">
                    <div class="client-action">
                        <a href="client_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle btn-warning glow mr-1 mb-1" data-tooltip="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <a href="#" class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1 delete_client" data-id="{{$row->id}}" data-tooltip="Delete">
                            <i class="bx bx-trash-alt"></i>
                        </a>
                    </div>
                </td>
                <td><small class="client-customer">{{ $row->client_case_no }}
                    </small></td>
                @if(session('role_id')==1 || session('role_id')==3)
                <td>{{number_format($row->finalize_quotation,2)}}</td>
                <td>{{number_format($row->future_invoices,2)}}</td>
                <td>{{number_format($row->bill_on_quotation,2)}}</td>
                <td>{{number_format($row->payment_on_quo,2)}}</td>
                <td>{{number_format($row->additional_bill,2)}}</td>
                <td>{{number_format($row->payment_on_add,2)}}</td>
                <td>{{number_format($row->due_amt,2)}}</td>
                <td>{{number_format($row->unapproved_payment,2)}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div><br>

<div class="row">
    <div class="col-12 col-md-2 pt-0 mx-25">
        Total Dues: <span><b>{{number_format($total_dues,2)}}</b></span>
    </div>
</div>
<script>
    $(document).ready(function() {
        if ($(".client-data-table").length) {
            $(".client-data-table").DataTable({
                columnDefs: [{
                        targets: 0,
                        className: "control"
                    },
                    {
                        orderable: true,
                        targets: 0,
                        // checkboxes: { selectRow: true }
                    },
                    {
                        targets: [0, 1],
                        orderable: false
                    },
                ],
                order: [2, 'asc'],
                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search Clients"
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
    });
</script>