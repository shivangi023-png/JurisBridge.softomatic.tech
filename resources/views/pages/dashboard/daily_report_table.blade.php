<div class="card widget-todo">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h6 class="d-flex">
            Open Quotation
        </h6>
        <ul class="list-inline d-flex mb-0">
           <li class="d-flex align-items-center">
                <a href="open_quotation_report" class="btn btn-primary btn-sm round mr-1">
                    View More
                    <i class='bx bx-chevrons-right'></i>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table dail_report_datatable">

                <thead>
                    <th>S.No.</th>
                    <th>client name</th>
                    <th>services</th>
                    <th>Send Date</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                    @if(!empty($open_quotation))
                    <?php $i = 1; ?>
                    @foreach($open_quotation as $row)
                    <tr>
                        <td><small>{{$i++}}</small></td>
                        <td><small>{{$row->client_name}}</small></td>
                        <td><small>{{$row->service_name}}</small></td>
                        <td><small>{{$row->send_date}}</small></td>
                        <td align="right"><small>{{number_format($row->amount,2)}}</small></td>
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
    </div>
</div>
<div class="modal fade text-left show" id="viewDiscussion">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title detailModal-title" id="myModalLabel1">Discussion</h5>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p id="discussionPara"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(".dail_report_datatable").DataTable({
        ordering: false,
        lengthChange: false,
        bFilter: false,
        searching: false,
        info: false,
        pageLength: 5,
    });
    $(".viewDiscussion").click(function() {
        var discussion = $(this).data('discussion');
        $('#discussionPara').empty().append(discussion);
        $("#viewDiscussion").modal('show');
    });
</script>