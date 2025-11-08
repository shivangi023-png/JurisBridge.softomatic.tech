<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table client-data-table wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>CONSUMER NAME</th>
                        <th>MOBILE NO</th>
                        <th>ADDRESS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    @foreach ($data as $row)
                    <tr>
                    <td>{{$i++}}</td>
                        <td>{{$row->consumer_name}}</td>
                        <td>{{$row->mobile_no}}</td>
                        <td>{{$row->address}}</td>
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
                searchPlaceholder: "Search Data",
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