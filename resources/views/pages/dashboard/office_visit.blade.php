<div class="card">
    <div class="card-header">
        <h6>
            Office Visits
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table office_visit_datatable">

                <thead>
                    <th>S.No.</th>
                    <th>Department Name</th>
                    <th>Staff Name</th>
                    <th>Client Name</th>
                    <th>Location</th>
                    <th>Discussion</th>
                    <th>Date</th>
                    <th>Time</th>

                </thead>

                <tbody>
                    @if(!empty($get_office_visit))
                    <?php $i = 1; ?>
                    @foreach($get_office_visit as $row)
                    <tr>
                        <td><small>{{$i++}}</small></td>
                        <td><small>{{$row->dept_address}}</small></td>
                        <td><small>{{$row->name}}</small></td>
                        <td><small>{{$row->client_name}}</small></td>
                        <td><small>{{$row->location}}</small></td>
                        <td><a class="text-success viewDiscussion" href="JavaScript:void(0)" data-discussion="{{$row->discussion}}">view</a></td>
                        <td><small>{{date('d-m-Y',strtotime($row->visit_date))}}</small></td>
                        <td><small><?php echo date("H:i:s", strtotime($row->created_at)); ?></small></td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4">No records found!! </td>
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
    $(".office_visit_datatable").DataTable({
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