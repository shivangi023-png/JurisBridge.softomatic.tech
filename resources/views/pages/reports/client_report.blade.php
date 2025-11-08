<div class="row">
    <div class="col-3 pr-2">
        <div class="card reportMenu">
            @if ( session('role_id') == 1 || session('role_id') == 3 || session('role_id') == 5 ||session ('role_id')==8 || session('role_id' == 10))
            <a href="#quotationTab">
                <div class=" card-header border quotation_report text-dark RMenuBox" id="quotation_report" onclick="get_report(this.id)">
                    <i class="bx bx-file bx_icon mr-2"></i> <strong class="ml-2">Quotation
                        Reports</strong>
                </div>
            </a>
            @endif
            @if ( session('role_id') != 8)
            <a href="#expenseTab">
                <div class="card-header border mt-1 expense_report text-dark RMenuBox" id="expense_report" onclick="get_report(this.id)">
                    <i class="bx bx-money bx_icon"></i> <strong class="ml-2">Expense
                        Reports</strong>
                </div>
            </a>
            @endif
            @if ( session('role_id') == 1 || session('role_id') == 3 || session('role_id') == 5 ||session ('role_id')==8 || session('role_id' == 10))
            <a href="#clientTab">
                <div class="card-header border mt-1 bg-primary client_report text-white RMenuBox" id="client_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Client
                        Reports</strong>
                </div>
            </a>
            @endif
            @if ( session('role_id') != 8)
            <a href="#attendanceTab">
                <div class="card-header border mt-1 attendance_report text-dark RMenuBox" id="attendance_report" onclick="get_report(this.id)">
                    <!-- <i class="bx bx-receipt bx_icon"></i> -->
                    <span><img src="{{asset('images/icon/attendance-report.svg')}}" width=16px; height="17px;" class="bx_icon attendance_img1">
                        <strong class="ml-2">Attendance
                            Reports</strong></span>
                </div>
            </a>
            <a href="#accountingTab">
                <div class="card-header border mt-1 accounting_report text-dark RMenuBox" id="accounting_report" onclick="get_report(this.id)">
                    <i class="bx bx-spreadsheet bx_icon"></i>
                    <strong class="ml-2">Accounting
                        Reports </strong>
                </div>
            </a>
            <a href="#prettyCashTab">
                <div class="card-header border mt-1 text-dark RMenuBox" id="pretty_cash_report" onclick="get_report(this.id)">
                    <i class="bx bx-spreadsheet bx_icon"></i>
                    <strong class="ml-2">Pretty Cash
                        Reports </strong>
                </div>
            </a>
            @if (session ('role_id')==10)
            <a href="#adminTab">
                <div class="card-header border mt-1 admin_report text-dark RMenuBox" id="admin_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Admin
                        Reports </strong>
                </div>
            </a>
            @endif
            @endif
            @if (session ('role_id')==8)
            <a href="#salesTab">
                <div class="card-header border mt-1 sales_report text-dark RMenuBox" id="sales_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Sales
                        Reports </strong>
                </div>
            </a>
            @endif
        </div>
    </div>
    <div class="col-9">
        <div class="card border_primary client_card" id="clientTab">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">1. Clients Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="all_clients_pdf">
                            <a href="javascript:void(0);" id="all_clients_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="all_clients_excel">
                            <a href="javascript:void(0);" class="ml-1" id="all_clients_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="all_clients_print">
                            <a href="javascript:void(0);" class="ml-1" id="all_clients_print" data-title="All Clients" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">2. Leads Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="all_leads_pdf">
                            <a href="javascript:void(0);" id="all_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="all_leads_excel">
                            <a href="javascript:void(0);" class="ml-1" id="all_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="all_leads_print">
                            <a href="javascript:void(0);" class="ml-1" id="all_leads_print" data-title="All Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>


                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">3. Follow-Up Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="client_followup_pdf">
                            <a href="javascript:void(0);" id="client_followup_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="client_followup_excel">
                            <a href="javascript:void(0);" class="ml-1" id="client_followup_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="client_followup_print">
                            <a href="javascript:void(0);" class="ml-1" id="client_followup_print" data-title="Client Follow-up" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">4. Not Follow-Up Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="client_not_followup_pdf">
                            <a href="javascript:void(0);" id="client_not_followup_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="client_not_followup_excel">
                            <a href="javascript:void(0);" class="ml-1" id="client_not_followup_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="client_not_followup_print">
                            <a href="javascript:void(0);" class="ml-1" id="client_not_followup_print" data-title="Client Not Follow-up" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">5. No Email Id Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="client_no_email_pdf">
                            <a href="javascript:void(0);" id="client_no_email_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="client_no_email_excel">
                            <a href="javascript:void(0);" class="ml-1" id="client_no_email_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="client_no_email_print">
                            <a href="javascript:void(0);" class="ml-1" id="client_no_email_print" data-title="Client With No Email" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">6. No Contact Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="client_no_contact_pdf">
                            <a href="javascript:void(0);" id="client_no_contact_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="client_no_contact_excel">
                            <a href="javascript:void(0);" class="ml-1" id="client_no_contact_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="client_no_contact_print">
                            <a href="javascript:void(0);" class="ml-1" id="client_no_contact_print" data-title="Client With No Contact" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">7. Assigned Leads Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="assigned_leads_pdf">
                            <a href="javascript:void(0);" id="assigned_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="assigned_leads_excel">
                            <a href="javascript:void(0);" class="ml-1" id="assigned_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="assigned_leads_print">
                            <a href="javascript:void(0);" class="ml-1" id="assigned_leads_print" data-title="Assigned Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">8. Unassigned Leads Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="unassigned_leads_pdf">
                            <a href="javascript:void(0);" id="unassigned_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="unassigned_leads_excel">
                            <a href="javascript:void(0);" class="ml-1" id="unassigned_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="unassigned_leads_print">
                            <a href="javascript:void(0);" class="ml-1" id="unassigned_leads_print" data-title="Assigned Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">9. Lead Contacts Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="companywise_lead_contacts_pdf">
                            <a href="javascript:void(0);" id="companywise_lead_contacts_pdf" onclick="download_pdf1(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="companywise_lead_contacts_excel">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_lead_contacts_excel" onclick="download_excel1(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="companywise_lead_contacts_print">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_lead_contacts_print" data-title="Lead Contacts" onclick="print1(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">10. Client Contacts Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="companywise_client_contacts_pdf">
                            <a href="javascript:void(0);" id="companywise_client_contacts_pdf" onclick="download_pdf1(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="companywise_client_contacts_excel">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_client_contacts_excel" onclick="download_excel1(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="companywise_client_contacts_print">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_client_contacts_print" data-title="Company-wise Client Contacts" onclick="print1(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">11. Leads's Services</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="leads_services_pdf">
                            <a href="javascript:void(0);" id="leads_services_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="leads_services_excel">
                            <a href="javascript:void(0);" class="ml-1" id="leads_services_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="leads_services_print">
                            <a href="javascript:void(0);" class="ml-1" id="leads_services_print" data-title="Leads Services" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">12. Lead Contacts and Quotation Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="companywise_lead_contacts_and_quotation_pdf">
                            <a href="javascript:void(0);" id="companywise_lead_contacts_and_quotation_pdf" onclick="download_pdf1(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="companywise_lead_contacts_and_quotation_excel">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_lead_contacts_and_quotation_excel" onclick="download_excel1(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="companywise_lead_contacts_and_quotation_print">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_lead_contacts_and_quotation_print" data-title="Lead Contacts and Quotation" onclick="print1(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">13. Client Contacts and Quotation Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="companywise_client_contacts_and_quotation_pdf">
                            <a href="javascript:void(0);" id="companywise_client_contacts_and_quotation_pdf" onclick="download_pdf1(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="companywise_client_contacts_and_quotation_excel">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_client_contacts_and_quotation_excel" onclick="download_excel1(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="companywise_client_contacts_and_quotation_print">
                            <a href="javascript:void(0);" class="ml-1" id="companywise_client_contacts_and_quotation_print" data-title="Company-wise Client Contacts and Quotation" onclick="print1(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/scripts/pages/get_report.js')}}"></script>