<style>
    .staff-dropdown {
        height: 250px !important;
        overflow-y: auto !important;
    }
</style>
<div class="action-dropdown-btn">
    @if(session('role_id') == 1)


    <div class="dropdown client-filter-action">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="selection">Action</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right company-dropdown" aria-labelledby="commpany_filter">
            <a type="button" href="#" class="dropdown-item multi_convert_client" id="multi_convert_client">Convert</a>
            <a type="button" href="#" class="dropdown-item Complete" id="complete">Complete</a>
            <a type="button" href="#" class="dropdown-item delete" id="delete">Delete</a>

        </div>
    </div>
    <div class="dropdown client-filter-action" style="padding-left:10px">
        <div class="dropdown client-filter-action">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="commpany_filter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="selection">Assign company</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right company-dropdown" aria-labelledby="commpany_filter">
                @foreach(session('company_full') as $com)
                <a type="button" href="#" class="dropdown-item assign_com_btn" data-company_id="{{$com->id}}" data-company_val="{{$com->company_name}}">{{$com->company_name}}</a>
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
                    <th>Society Name</th>
                    <th>Company</th>
                    <th>Source</th>
                    <th>Units</th>
                    <th>Mobile No</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Any Query</th>
                    <th>Area</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Services</th>
                    <th>Lead Type</th>
                    <th>Commitee Member</th>
                    <th>Fb id</th>
                    <th>Ad id</th>
                    <th>Ad name</th>
                    <th>Adset id</th>
                    <th>Adset name</th>
                    <th>Campaign id</th>
                    <th>Campaign name</th>
                    <th>Form id</th>
                    <th>Form name</th>
                    <th>Status</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($new_leads_list as $row)
                <tr>
                    <td></td>
                    <td></td>
                    <td><input type="hidden" class="form-control leadID" value="{{ $row->id }}"></td>
                    <td>
                        <div class="row client-action">
                              <a href="#" class="btn btn-icon rounded-circle glow btn-secondary add_followup" data-id="{{$row->id}}" data-name="{{$row->name}}" data-tooltip="Add follow-up" data-toggle="modal" data-target="#followupModal">
                                                <i class="bx bx-phone-call"></i>
                                            </a>
                            <a href="leads_edit-{{$row->id}}" class="client-action-edit btn btn-icon rounded-circle glow btn-warning margin_right" data-tooltip="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            @if(session('role_id')==1 || session('role_id')==3)
                            <a href="#" class="btn btn-icon rounded-circle glow btn-danger delete_leads margin_right" data-id="{{$row->id}}" data-tooltip="Delete">
                                <i class="bx bx-trash-alt"></i>
                            </a>
                            @if($row->company=='' || $row->company==NULL)
                            <button href="#" class="btn btn-icon rounded-circle glow btn-secondary margin_right convert_client" data-tooltip="Convert to Lead" disabled="disabled">
                                <i class="bx bx-transfer"></i>
                            </button>
                            @else
                            <button href="#" data-id="{{$row->id}}" data-company_id="{{$row->company}}" class="btn btn-icon rounded-circle glow btn-secondary margin_right convert_client" data-tooltip="Convert to Lead">
                                <i class="bx bx-transfer"></i>
                            </button>
                            @endif
                            <div class="save_lead_div" style="display:none;">
                                <button href="#" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-success margin_right save_lead_type" data-tooltip="Save Lead Type">
                                    <i class="bx bx-save"></i>
                                </button>

                                <button href="#" data-id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-danger margin_right close_lead_type" data-tooltip="Close Lead Type">
                                    <i class="bx bx-window-close"></i>
                                </button>
                            </div>
                            <!-- <button href="#" data-client_id="{{$row->id}}" class="btn btn-icon rounded-circle glow btn-primary margin_right change_lead_type" data-tooltip="Change Lead Type">
                                            <i class="bx bx-analyse"></i>
                                        </button> -->
                            @endif
                        </div>
                    </td>
                    <td><small class="client-customer">{{ $row->name }}</small></td>

                    <td>{{$row->society_name}}</td>
                    <td>{{$row->company_name}}</td>
                    <td>
                        @if($row->from=='web')
                        <img src="{{asset('images/source_icons/web.png')}}" alt="Web">
                        @elseif($row->from=='app')
                        <img src="{{asset('images/source_icons/app.png')}}" alt="app">
                        @elseif($row->from=='fb')
                        <img src="{{asset('images/source_icons/facebook.png')}}" alt="Facebook">
                        @elseif($row->from=='newspaper')
                        <img src="{{asset('images/source_icons/newspaper.png')}}" alt="newspaper">
                        @elseif($row->from=='whatsApp-group')
                        <img src="{{asset('images/source_icons/whatsApp-group.png')}}" alt="whatsApp-group">
                        @elseif($row->from=='walk-in')
                        <img src="{{asset('images/source_icons/walk-in.png')}}" alt="walk-in">
                        @elseif($row->from=='client-ref')
                        <img src="{{asset('images/source_icons/client-ref')}}" alt="client-ref">
                        @endif
                    </td>
                    <td>{{$row->units}}</td>
                    <td>{{$row->mobile_no}}</td>
                    <td>{{$row->email}}</td>
                    <td>{{$row->city}}</td>
                    <td>{{$row->any_query}}</td>
                    <td>{{$row->area}}</td>
                    <td>{{$row->address}}</td>
                    <td>{{$row->role}}</td>
                    <td>{{$row->services}}</td>
                    <td>
                        @if ($row->type == 'New')
                        <span class="badge badge-light-success badge-pill">{{$row->type}}</span>
                        @elseif($row->type == 'Cold')
                        <span class="badge badge-light-danger badge-pill">{{$row->type}}</span>
                        @elseif($row->type == 'Potential')
                        <span class="badge badge-light-primary badge-pill">{{$row->type}}</span>
                        @elseif($row->type == 'Hot')
                        <span class="badge badge-light-warning badge-pill">{{$row->type}}</span>
                        @elseif($row->type == 'Closed')
                        <span class="badge badge-light-danger badge-pill">{{$row->type}}</span>
                        @elseif($row->type == 'Reopen')
                        <span class="badge badge-light-brown badge-pill">{{$row->type}}</span>
                        @elseif($row->type == 'Not Interested')
                        <span class="badge badge-light-secondary badge-pill">Nt Int</span>
                        @elseif($row->type == 'NCT')
                        <span class="badge badge-light-secondary badge-pill">{{$row->type}}</span>
                        @endif
                    </td>
                    <td>{{ $row->check_commitee_member }}</td>
                    <td>{{ $row->fb_id }}</td>
                    <td>{{ $row->ad_id }}</td>
                    <td>{{ $row->ad_name }}</td>
                    <td>{{ $row->adset_id }}</td>
                    <td>{{ $row->adset_name }}</td>
                    <td>{{ $row->campaign_id }}</td>
                    <td>{{ $row->campaign_name }}</td>
                    <td>{{ $row->form_id }}</td>
                    <td>{{ $row->form_name }}</td>
                    <td>{{ $row->status }}</td>
                    <td>
                        @if($row->created_at!='')
                        {{date('d-M-Y',strtotime($row->created_at))}}
                        @endif
                    </td>
                </tr>
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
                    iDisplayLength: 20,
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
        });
    </script>