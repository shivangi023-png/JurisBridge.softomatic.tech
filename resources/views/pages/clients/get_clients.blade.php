<div class="action-dropdown-btn">
    <div class="dropdown client-filter-action">
        @if(session('role_id') == 1)
        <div class="dropdown client-filter-action">
            <button class="btn btn-outline-primary dropdown-toggle mr-1" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="selection">Assign Lead To Team</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right staff-dropdown" aria-labelledby="client-filter-btn">
                @foreach ($staff as $item)
                <a type="button" href="#" class="dropdown-item assign_btn" data-assign_id="{{$item->sid}}" data-assign_val="{{$item->name}}">{{$item->name}}</a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
<div class="table-responsive">
    <table class="table client-data-table wrap">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Action</th>
                <th>Name</th>
                <th>Assign Date</th>
                <th>Assign To</th>
                <th>No of units</th>
                <th>Property type</th>
                <th>Quotations</th>
                <th>Follow-up</th>
                <th>Appointments</th>
                <th>Source</th>
                <th>Remarks</th>
                <th>Address</th>
            </tr>
        </thead>

        <tbody>
            @foreach($client_list as $row)
            <tr>
                <td></td>
                <td></td>
                <td><input type="hidden" class="form-control clientID" value="{{ $row->id }}"></td>
                <td style="white-space: nowrap;">
                    <div class="client-action">
                        <a href="client_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle glow btn-warning" data-tooltip="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <a href="#" class="btn btn-icon rounded-circle glow btn-info add_appointment_btn" data-id="{{$row->id}}" data-client_name="{{$row->client_name}}" data-tooltip="Add appointment" data-toggle="modal" data-target="#appointmentModal">
                            <i class="bx bx-alarm-add"></i>
                        </a>
                        <a href="#" class="btn btn-icon rounded-circle glow btn-secondary add_followup" data-id="{{$row->id}}" data-client_name="{{$row->client_name}}" data-tooltip="Add follow-up" data-toggle="modal" data-target="#followupModal">
                            <i class="bx bx-phone-call"></i>
                        </a>
                        @if(session('role_id')==1 || session('role_id')==3)
                        @if($row->status=='active')
                        <a href="#" class="btn btn-icon rounded-circle glow btn-danger delete_client" data-id="{{$row->id}}" data-tooltip="Delete">
                            <i class="bx bx-trash-alt"></i>
                        </a>
                        @endif
                        @endif
                    </div>
                </td>
                <td><small class="client-customer">{{ $row->client_case_no }}</small>
                </td>
                <td class="assigned_at">
                    @if($row->assigned_at!='')
                    <small>{{$row->assigned_at}}</small>
                    @endif
                </td>
                <td><a href="javascript:void(0);" class="lead_history" data-client_id="{{$row->id}}"><small class="assign_staff">{{$row->assign_staff_name}}</small></a></td>
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
                <td class="remarks">
                    @if($row->remarks)
                    <a href="javascript:void();" class="badge badge-light-warning" data-toggle="modal" data-target="#remarkModal{{$row->id}}">Remark</a>
                    @endif
                </td>
                <td class="address"><small>{{$row->address}} , {{$row->city_name}}</small>
                    @if($row->pincode)
                    <small>, {{$row->pincode}}</small>
                    @endif
                </td>
            </tr>
            <div class="modal fade text-left" id="remarkModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title detailModal-title" id="myModalLabel2">Remark -</h3>
                            <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{$row->remarks}}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        // var dataListView = '';
        //dataListView.destroy();
        if ($(".client-data-table").length) {
            var dataListView = $(".client-data-table").DataTable({
                // scrollX: true,
                // scrollCollapse: true,
                // autoWidth: true,
                // iDisplayLength: 50,
                columnDefs: [{
                        width: "240px",
                        targets: [3]
                    },
                    {
                        targets: 0,
                        className: "control",
                    },
                    {
                        orderable: true,
                        targets: 1,
                        checkboxes: {
                            selectRow: true,
                        },
                    },
                    {
                        targets: [0, 1, 2],
                        orderable: false,
                    },
                ],
                order: [1, "asc"],

                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

                select: {
                    style: "multi",
                    selector: "td:first-child",
                    items: "row"
                },
                responsive: {
                    details: {
                        type: "column",
                        target: 0
                    }
                }
            });
        }
        // To append actions dropdown inside action-btn div
        var clientFilterAction = $(".client-filter-action");
        $(".action-btns").append(clientFilterAction);
        $(".dt-checkboxes-cell")
            .find("input")
            .on("change", function() {
                var $this = $(this);
                if ($this.is(":checked")) {
                    $this.closest("tr").addClass("selected-row-bg");
                } else {
                    $this.closest("tr").removeClass("selected-row-bg");
                }
            });
        // Select all checkbox
        $(document).on("change", ".dt-checkboxes-select-all input", function() {
            if ($(this).is(":checked")) {
                $(".dt-checkboxes-cell")
                    .find("input")
                    .prop("checked", this.checked)
                    .closest("tr")
                    .addClass("selected-row-bg");
            } else {
                $(".dt-checkboxes-cell")
                    .find("input")
                    .prop("checked", "")
                    .closest("tr")
                    .removeClass("selected-row-bg");
            }
        });
        //dataListView.destroy();
    });
</script>