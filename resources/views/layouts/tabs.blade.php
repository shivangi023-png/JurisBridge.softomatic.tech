<ul class="nav nav-tabs" role="tablist">
    @if('staff_add'== Request::path()||'company_add'== Request::path()||'bank_add'== Request::path() ||'template_add'==Request::path() ||
    'service_add'== Request::path() || 'assign_leads' == Request::path()|| 'lead_type_add' == Request::path() ||
    'add_office' == Request::path() || 'staff_shift' == Request::path() || 'leave-analytics'== Request::path() || 'office-address'== Request::path() || 'export-lead'== Request::path())
    <li class="nav-item">
        @if ('staff_add'== Request::path())
        <a class="nav-link active" href="staff_add" aria-controls="home" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Add Staff</span>
        </a>
        @else
        <a class="nav-link" href="staff_add" aria-controls="home" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Add Staff</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('company_add'== Request::path())
        <a class="nav-link active" href="company_add" aria-controls="profile" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Company</span>
        </a>
        @else
        <a class="nav-link" href="company_add" aria-controls="profile" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Company</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('bank_add'== Request::path())
        <a class="nav-link active" href="bank_add" aria-controls="bank" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Bank</span>
        </a>
        @else
        <a class="nav-link" href="bank_add" aria-controls="bank" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Bank</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('template_add'== Request::path())
        <a class="nav-link active" href="template_add" aria-controls="template" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Template</span>
        </a>
        @else
        <a class="nav-link" href="template_add" aria-controls="template" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Template</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('service_add'== Request::path())
        <a class="nav-link active" href="service_add" aria-controls="services" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Service</span>
        </a>
        @else
        <a class="nav-link" href="service_add" aria-controls="services" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Add Service</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('assign_leads'== Request::path())
        <a class="nav-link active" href="assign_leads" aria-controls="leads" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Assign Leads</span>
        </a>
        @else
        <a class="nav-link" href="assign_leads" aria-controls="leads" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Assign Leads</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('lead_type_add'== Request::path())
        <a class="nav-link active" href="lead_type_add" aria-controls="leads" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Lead Type</span>
        </a>
        @else
        <a class="nav-link" href="lead_type_add" aria-controls="leads" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Lead Type</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('add_office'== Request::path())
        <a class="nav-link active" href="add_office" aria-controls="leads" role="tab" aria-selected="false">
            <span class="align-middle">Office Master</span>
        </a>
        @else
        <a class="nav-link" href="add_office" aria-controls="leads" role="tab" aria-selected="false">
            <span class="align-middle">Office Master</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('staff_shift'== Request::path())
        <a class="nav-link active" href="staff_shift" aria-controls="leads" role="tab" aria-selected="false">
            <span class="align-middle">Employee Shift</span>
        </a>
        @else
        <a class="nav-link" href="staff_shift" aria-controls="leads" role="tab" aria-selected="false">
            <span class="align-middle">Employee Shift</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('leave-analytics'== Request::path())
        <a class="nav-link active" href="leave-analytics" aria-controls="staff_leave_analytics" role="tab" aria-selected="true">
            <span class="align-middle">Staff Leave</span>
        </a>
        @else
        <a class="nav-link" href="leave-analytics" aria-controls="staff_leave_analytics" role="tab" aria-selected="true">
            <span class="align-middle">Staff Leave</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('office-address'== Request::path())
        <a class="nav-link active" href="office-address" aria-controls="office_address" role="tab" aria-selected="true">
            <span class="align-middle">Office Address</span>
        </a>
        @else
        <a class="nav-link" href="office-address" aria-controls="office_address" role="tab" aria-selected="true">
            <span class="align-middle">Office Address</span>
        </a>
        @endif
         <li class="nav-item">
        @if ('export-lead'== Request::path())
        <a class="nav-link active" href="export-lead" aria-controls="office_address" role="tab" aria-selected="true">
            <span class="align-middle">Export New Lead</span>
        </a>
        @else
        <a class="nav-link" href="export-lead" aria-controls="office_address" role="tab" aria-selected="true">
            <span class="align-middle">Export New Lead</span>
        </a>
        @endif
    </li>
    </li>
    @endif
    @if('expense_list'== Request::path()||'travelling_allowance'== Request::path()||'expenses_report'==
    Request::path()||'travelling_allowance_report'== Request::path())
    <li class="nav-item">
        @if ('expense_list'== Request::path())
        <a class="nav-link active" href="expense_list" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Expenses</span>
        </a>
        @else
        <a class="nav-link" href="expense_list" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Expenses</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('travelling_allowance'== Request::path())
        <a class="nav-link active" href="travelling_allowance" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Travelling Allowance</span>
        </a>
        @else
        <a class="nav-link" href="travelling_allowance" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Travelling Allowance</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('expenses_report'== Request::path())
        <a class="nav-link active" href="expenses_report" aria-controls="report" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Expense Report</span>
        </a>
        @else
        <a class="nav-link" href="expenses_report" aria-controls="report" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Expense Report</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('travelling_allowance_report'== Request::path())
        <a class="nav-link active" href="travelling_allowance_report" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Travelling Allowance Report</span>
        </a>
        @else
        <a class="nav-link" href="travelling_allowance_report" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Travelling Allowance Report</span>
        </a>
        @endif
    </li>
    @endif
    @if('invoice_list'== Request::path()||'proforma_invoice_list'== Request::path())
    <li class="nav-item">
        @if ('invoice_list'== Request::path())
        <a class="nav-link active" href="invoice_list" aria-controls="invoice" role="tab" aria-selected="true">
            <span class="align-middle">Tax Invoice</span>
        </a>
        @else
        <a class="nav-link" href="invoice_list" aria-controls="invoice" role="tab" aria-selected="true">
            <span class="align-middle">Tax Invoice</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('proforma_invoice_list'== Request::path())
        <a class="nav-link active" href="proforma_invoice_list" aria-controls="invoice" role="tab" aria-selected="false">
            <span class="align-middle">Proforma Invoice</span>
        </a>
        @else
        <a class="nav-link" href="proforma_invoice_list" aria-controls="invoice" role="tab" aria-selected="false">
            <span class="align-middle">Proforma Invoice</span>
        </a>
        @endif
    </li>
    @endif
    @if('client_list'== Request::path()||'leads'== Request::path() || 'client_ledger'==
    Request::path() ||'statistics_leads'== Request::path()||'my-leads'== Request::path() || 'new_leads'== Request::path() || 'lead_data' == Request::path() || 'campaign_wise_leads' == Request::path() || 'campaign_wise_total' == Request::path())

    <li class="nav-item">
        @if ('client_ledger'== Request::path())
        <a class="nav-link active" href="client_ledger" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Client ledger</span>
        </a>
        @else
        <a class="nav-link" href="client_ledger" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Client ledger</span>
        </a>
        @endif
    </li>
    @if(session('role_id')==1 || session('role_id')==3)


    <li class="nav-item">
        @if ('client_list'== Request::path())
        <a class="nav-link active" href="client_list" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Clients</span>
        </a>
        @else
        <a class="nav-link" href="client_list" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Clients</span>
        </a>
        @endif
    </li>
    @endif
    <li class="nav-item">
        @if ('leads'== Request::path())
        <a class="nav-link active" href="leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">
                @if(session('role_id')==1)
                Assign Leads
                @else
                My Leads
                @endif
            </span>
        </a>
        @else
        <a class="nav-link" href="leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">
                @if(session('role_id')==1)
                Assign Leads
                @else
                My Leads
                @endif
            </span>
        </a>
        @endif
    </li>
    @if(session('role_id')==1)
    <li class="nav-item">
        @if ('statistics_leads'== Request::path())
        <a class="nav-link active" href="statistics_leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Lead Statistics</span>
        </a>
        @else
        <a class="nav-link" href="statistics_leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Lead Statistics</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('my-leads'== Request::path())
        <a class="nav-link active" href="my-leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">My Leads</span>
        </a>
        @else
        <a class="nav-link" href="my-leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">My Leads</span>
        </a>
        @endif
    </li>

    @endif
    <li class="nav-item">
        @if ('new_leads'== Request::path())
        <a class="nav-link active" href="new_leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">New Leads</span>
        </a>
        @else
        <a class="nav-link" href="new_leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">New Leads</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('lead_data'== Request::path())
        <a class="nav-link active" href="lead_data" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Lead Data</span>
        </a>
        @else
        <a class="nav-link" href="lead_data" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Lead Data</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('campaign_wise_leads'== Request::path())
        <a class="nav-link active" href="campaign_wise_leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Campaign Wise Leads</span>
        </a>
        @else
        <a class="nav-link" href="campaign_wise_leads" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Campaign Wise Leads</span>
        </a>
        @endif
    </li>
    <li class="nav-item">
        @if ('campaign_wise_total'== Request::path())
        <a class="nav-link active" href="campaign_wise_total" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Campaign Wise Total</span>
        </a>
        @else
        <a class="nav-link" href="campaign_wise_total" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">Campaign Wise Total</span>
        </a>
        @endif
    </li>
    @endif

    @if('follow-up-list'== Request::path()||'my_next_followup'== Request::path() || 'my_followup'== Request::path())
    @if(session('role_id') == 1)
    <li class="nav-item">

        @if ('follow-up-list'== Request::path())
        <a class="nav-link active" href="follow-up-list" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->

            <span class="align-middle">Follow-up</span>

        </a>
        @else
        <a class="nav-link" href="follow-up-list" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->

            <span class="align-middle">Follow-up</span>

        </a>
        @endif
    </li>
    @endif
    <li class="nav-item">
        @if ('my_next_followup'== Request::path())
        <a class="nav-link active" href="my_next_followup" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">My Next Follow-up</span>
        </a>
        @else
        <a class="nav-link" href="my_next_followup" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">My Next Follow-up</span>
        </a>
        @endif

    <li class="nav-item">
        @if ('my_followup'== Request::path())
        <a class="nav-link active" href="my_followup" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">My Follow-up</span>
        </a>
        @else
        <a class="nav-link" href="my_followup" aria-controls="allowance" role="tab" aria-selected="false">
            <!-- <i class="bx bx-user align-middle"></i> -->
            <span class="align-middle">My Follow-up</span>
        </a>
        @endif
    </li>
    @endif
    @if('upload_document'== Request::path() || 'search_document'== Request::path())
    <li class="nav-item">
        @if ('upload_document'== Request::path())
        <a class="nav-link active" href="upload_document" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Upload Document</span>
        </a>
        @else
        <a class="nav-link" href="upload_document" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Upload Document</span>
        </a>
        @endif
    </li>

    <li class="nav-item">
        @if ('search_document'== Request::path())
        <a class="nav-link active" href="search_document" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Search Document</span>
        </a>
        @else
        <a class="nav-link" href="search_document" aria-controls="expense" role="tab" aria-selected="true">
            <!-- <i class="bx bxs-calculator align-middle"></i> -->
            <span class="align-middle">Search Document</span>
        </a>
        @endif
    </li>
    @endif

</ul>