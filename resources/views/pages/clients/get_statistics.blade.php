<div class="table-responsive">
    <table class="table client-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>Name</th>
                <th>Assigned To</th>
                <th>Lead Type</th>
                <th>No of units</th>
                <th>Property type</th>
                <th>Quotations</th>
                <th>Follow-up</th>
                <th>Appointments</th>
                <th>Source</th>
                <th>Address</th>
            </tr>
        </thead>

        <tbody>
            @foreach($client_list as $row)
            <tr>
                <td style="white-space: nowrap;">
                    <div class="client-action">
                        <a href="client_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle glow btn-warning" data-tooltip="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        @if(session('role_id')==1 || session('role_id')==3)
                        <a href="#" class="btn btn-icon rounded-circle glow btn-danger delete_client" data-id="{{$row->id}}" data-tooltip="Delete">
                            <i class="bx bx-trash-alt"></i>
                        </a>
                        <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-secondary convert_client" data-tooltip="Convert to client">
                            <i class="bx bx-transfer"></i>
                        </button>
                        @endif
                    </div>
                </td>
                <td><small class="client-customer">{{ $row->client_case_no }}</small>
                </td>
                <td><small>{{$row->assign_staff_name}}</small></td>
                <td>
                    <div class="lead_type_view">
                       @if($row->type == 'New')
                        <span class="badge badge-light-success badge-pill">{{ucwords($row->type)}}</span>
                        @elseif($row->type == 'Cold')
                        <span class="badge badge-light-danger badge-pill">{{ucwords($row->type)}}</span>
                        @elseif($row->type == 'Potential')
                        <span class="badge badge-light-primary badge-pill">{{ucwords($row->type)}}</span>
                        @elseif($row->type == 'Hot')
                        <span class="badge badge-light-warning badge-pill">{{ucwords($row->type)}}</span>
                        @elseif($row->type == 'Closed')
                        <span class="badge badge-light-danger badge-pill">{{ucwords($row->type)}}</span>
                        @elseif($row->type == 'Reopen')
                        <span class="badge badge-light-brown badge-pill">{{ucwords($row->type)}}</span>
                      
                        @elseif($row->type == 'Not Interested')
                        <span class="badge badge-light-secondary badge-pill">Nt Int</span>
                         @elseif($row->type == 'NCT')
                        <span class="badge badge-light-gray badge-pill">{{ucwords($row->type)}}</span>
                        @endif
                    </div>
                </td>
                <td>{{$row->no_of_units}}</td>
                <td>{{$row->abbrev}}</td>
                <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="Quotation" data-toggle="modal" data-target="#detailModal">{{$row->quotations}}</a></td>
                <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="Follow-Up" data-toggle="modal" data-target="#detailModal">{{$row->followups}}</a></td>
                <td><a href="#" class="detailBtn" data-client_id="{{$row->id}}" data-detail="Appointment" data-toggle="modal" data-target="#detailModal">{{$row->appointments}}</a></td>
                <td class="source_name">
                    @if($row->source_name=='Facebook')
                    <img src="{{asset('images/source_icons/facebook.png')}}" alt="Facebook">
                    @elseif($row->source_name=='Whatsapp group')
                    <img src="{{asset('images/source_icons/whatsApp-group.png')}}" alt="Whatsapp group">
                    @elseif($row->source_name=='Active Sales')
                    <img src="{{asset('images/source_icons/active-sales.png')}}" alt="Active Sales">
                    @elseif($row->source_name=='Client ref')
                    <img src="{{asset('images/source_icons/client-ref.png')}}" alt="Client ref">
                    @elseif($row->source_name=='Newspaper')
                    <img src="{{asset('images/source_icons/newspaper.png')}}" alt="Newspaper">
                    @elseif($row->source_name=='Franchise')
                    <img src="{{asset('images/source_icons/franchise.png')}}" alt="Franchise">
                    @elseif($row->source_name=='LinkedIn')
                    <img src="{{asset('images/source_icons/linkedin.png')}}" alt="LinkedIn">
                    @elseif($row->source_name=='Quora')
                    <img src="{{asset('images/source_icons/quora.png')}}" alt="Quora">
                    @elseif($row->source_name=='YouTube')
                    <img src="{{asset('images/source_icons/youtube.png')}}" alt="YouTube">
                    @elseif($row->source_name=='Google ads')
                    <img src="{{asset('images/source_icons/googleAds.png')}}" alt="Google ads">
                    @elseif($row->source_name=='Walk-in')
                    <img src="{{asset('images/source_icons/walk-in.png')}}" alt="Walk-in">
                    @endif
                </td>
                <td><span style="font-size:12px">{{$row->address}}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
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
                    searchPlaceholder: "Search Leads"
                },

                select: {
                    style: "multi",
                    selector: "td:first-child",
                    items: "row"
                },
            });
        }
    });
</script>