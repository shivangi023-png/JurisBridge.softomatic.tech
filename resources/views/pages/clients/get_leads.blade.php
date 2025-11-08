<style>
    .staff-dropdown {
        height: 250px !important;
        overflow-y: auto !important;
    }
</style>
<div class="action-dropdown-btn">
    @if(session('role_id') == 1)
     <div class="action-dropdown-btn mt-2">
        <div class="dropdown client-filter-action source-action mr-1">
        <select name="source" id="bulk_source" class="form-control">
            <option value="">Select Source</option>
            @foreach($source as $val)
            <option value="{{$val->id}}">{{$val->source}}</option>
            @endforeach
        </select>
    </div>
    </div> 

    <div class="dropdown client-filter-action">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="client-filter-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
<div class="table-responsive">
    <table class="table client-data-table wrap">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Action</th>
                <th>Name</th>
                <th>Lead Type</th>
                <th>Assigned To</th>
                <th>No of units</th>
                <th>Property type</th>
                <th>Source</th>
                <th>Created Date</th>
                <th>Assigned Date</th>
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
                <td>
                    <div class="row client-action">
                        <a href="client_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle glow btn-warning" data-tooltip="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        @if(session('role_id')==1 || session('role_id')==3)
                        <a href="#" class="btn btn-icon rounded-circle glow btn-danger  delete_client" data-id="{{$row->id}}" data-tooltip="Delete">
                            <i class="bx bx-trash-alt"></i>
                        </a>
                        <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-secondary convert_client" data-tooltip="Convert to client">
                            <i class="bx bx-transfer"></i>
                        </button>
                        @endif
                        @if(session('role_id')==1 || session('role_id')==3 || session('role_id')==8)
                        <div class="save_lead_div" style="display:none;">
                            <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-success save_lead_type" data-tooltip="Save Lead Type">
                                <i class="bx bx-save"></i>
                            </button>

                            <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-danger close_lead_type" data-tooltip="Close Lead Type">
                                <i class="bx bx-window-close"></i>
                            </button>
                        </div>
                        <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-primary change_lead_type" data-tooltip="Change Lead Type">
                            <i class="bx bx-analyse"></i>
                        </button>
                        @endif
                    </div>
                </td>
                <td><small class="client-customer">{{ $row->client_case_no }}</small></td>
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
                    <div class="select_lead_type" style="display:none;">
                        <select class="form-control lead_type" name="lead_type">
                            @foreach($leadtype as $val)
                            <option value="{{$val->id}}" {{ ($val->id == $row->lead_type) ? 'selected' : '' }}>{{$val->type}}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td><a href="javascript:void(0);" class="lead_history" data-client_id="{{$row->id}}"><small class="assign_staff">{{$row->assign_staff_name}}</small></a></td>
                <td class="no_of_units">{{$row->no_of_units}}</td>
                <td class="property_type_name">{{$row->abbrev}}</td>
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
                <td><small>{{date('d-m-Y',strtotime($row->created_at))}}</small></td>
                <td class="assigned_at">
                    @if($row->assigned_at!='')
                    <small>{{$row->assigned_at}}</small>
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
        if ($(".client-data-table").length) {
            var dataListView = $(".client-data-table").DataTable({
                scrollX: true,
                scrollCollapse: true,
                autoWidth: true,
                iDisplayLength: 50,
                columnDefs: [{
                        width: "150px",
                        targets: [3],
                    },
                    {
                        width: "100px",
                        targets: [10],
                    },
                    {
                        width: "100px",
                        targets: [11],
                    },
                    {
                        width: "100px",
                        targets: [13],
                    },
                    {
                        targets: 0,
                        className: "control",
                    },
                    {
                        orderable: false,
                        targets: 1,
                        checkboxes: {
                            selectRow: true,
                        },
                    },
                    {
                        targets: [0, 1, 2, 3],
                        orderable: false,
                    },
                ],
                order: [5, "desc"],
                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
                language: {
                    search: "",
                    searchPlaceholder: "Search Leads",
                },

                select: {
                    style: "multi",
                    selector: "td:first-child",
                    items: "row",
                },
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
    });
     // Select all checkbox
        $(document).on("change", ".dt-checkboxes-select-all input", function() {
            if ($(this).is(":checked")) {
                $(".dt-checkboxes-cell")
                    .find("input")
                    .prop("checked", this.checked)
                    .closest("tr")
                    .addClass("selected-row-bg");
                    $('.source-action').hide();
            } else {
                $(".dt-checkboxes-cell")
                    .find("input")
                    .prop("checked", "")
                    .closest("tr")
                    .removeClass("selected-row-bg");
                    $('.source-action').show();
            }
        });

         $(document).on("change", ".dt-checkboxes-cell input", function() {
            if ($(this).is(":checked")) {
                $(this)
                    .find("input")
                    .prop("checked", this.checked)
                    .closest("tr")
                    .addClass("selected-row-bg");
                    $('.source-action').hide();
            } else {
                $(this)
                    .find("input")
                    .prop("checked", "")
                    .closest("tr")
                    .removeClass("selected-row-bg");
                    $('.source-action').show();
            }
        });
</script>