<div class="row">
    <div class="col-3 pr-2">
        <div class="card reportMenu">
            <a href="#quotationTab">
                <div class=" card-header border quotation_report text-dark RMenuBox" id="quotation_report" onclick="get_report(this.id)">
                    <i class="bx bx-file bx_icon mr-2"></i> <strong class="ml-2">Quotation
                        Reports</strong>
                </div>
            </a>
            <a href="#expenseTab">
                <div class="card-header border mt-1 expense_report text-dark RMenuBox" id="expense_report" onclick="get_report(this.id)">
                    <i class="bx bx-money bx_icon"></i> <strong class="ml-2">Expense
                        Reports</strong>
                </div>
            </a>
            <a href="#clientTab">
                <div class="card-header border mt-1 client_report text-dark RMenuBox" id="client_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Client
                        Reports</strong>
                </div>
            </a>
            <a href="#attendanceTab">
                <div class="card-header border mt-1 attendance_report text-dark RMenuBox" id="attendance_report" onclick="get_report(this.id)">
                    <!-- <i class="bx bx-receipt bx_icon"></i> -->
                    <span>
                        <img src="{{asset('images/icon/attendance-report.svg')}}" width=16px; height="17px;" class="bx_icon attendance_img2">
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
                <div class="card-header border mt-1 bg-primary admin_report text-white RMenuBox" id="admin_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Admin
                        Reports </strong>
                </div>
            </a>
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
        <div class="card border_primary admin_card" id="adminTab">
            <div class="card-body">
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">1. Quotations Finalized Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="admin_quotation_finalized_pdf">
                            <a href="javascript:void(0);" id="admin_quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="admin_quotation_finalized_excel">
                            <a href="javascript:void(0);" class="ml-1" id="admin_quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="admin_quotation_finalized_print">
                            <a href="javascript:void(0);" class="ml-1" id="admin_quotation_finalized_print" data-title="Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">2. Servicewise Quotations Finalized
                            Report
                        </h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_servicewise_quotation_finalized_pdf">
                            <a href="javascript:void(0);" id="Admin_servicewise_quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_servicewise_quotation_finalized_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_servicewise_quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_servicewise_quotation_finalized_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_servicewise_quotation_finalized_print" data-title="Servicewise Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">3. Leads Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_leads_pdf">
                            <a href="javascript:void(0);" id="Admin_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_leads_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_leads_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_leads_print" data-title="All Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">4. Assigned Leads Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_assigned_leads_pdf">
                            <a href="javascript:void(0);" id="Admin_assigned_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_assigned_leads_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_assigned_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_assigned_leads_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_assigned_leads_print" data-title="Assigned Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">5. Unassigned Leads Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_unassigned_leads_pdf">
                            <a href="javascript:void(0);" id="Admin_unassigned_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_unassigned_leads_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_unassigned_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_unassigned_leads_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_unassigned_leads_print" data-title="Assigned Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">6. Invoices Against Quotation</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_invoice_against_quotation_pdf">
                            <a href="javascript:void(0);" id="Admin_invoice_against_quotation_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_invoice_against_quotation_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_invoice_against_quotation_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_invoice_against_quotation_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_invoice_against_quotation_print" data-title="Invoice against Quotation" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">7. Additional Invoices</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_additional_invoices_pdf">
                            <a href="javascript:void(0);" id="Admin_additional_invoices_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_additional_invoices_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_additional_invoices_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_additional_invoices_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_additional_invoices_print" data-title="Additional Invoices" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">8. Cancelled Invoices</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_cancelled_invoice_pdf">
                            <a href="javascript:void(0);" id="Admin_cancelled_invoice_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_cancelled_invoice_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_cancelled_invoice_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_cancelled_invoice_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_cancelled_invoice_print" data-title="Cancelled Invoice" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">9. Consultation Fee Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_consultation_fee_pdf">
                            <a href="javascript:void(0);" id="Admin_consultation_fee_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_consultation_fee_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_consultation_fee_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_consultation_fee_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_consultation_fee_print" data-title="Consultation Fee" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">10. Daily Office Visit</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="Admin_daily_visit_pdf">
                            <a href="javascript:void(0);" id="Admin_daily_visit_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="Admin_daily_visit_excel">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_daily_visit_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="Admin_daily_visit_print">
                            <a href="javascript:void(0);" class="ml-1" id="Admin_daily_visit_print" data-title="Daily Visit" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">11. Check In Staff Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="checkIn_staff_pdf">
                            <a href="javascript:void(0);" id="checkIn_staff_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="checkIn_staff_excel">
                            <a href="javascript:void(0);" class="ml-1" id="checkIn_staff_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="checkIn_staff_print">
                            <a href="javascript:void(0);" class="ml-1" id="checkIn_staff_print" data-title="Check In Staff" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/scripts/pages/get_report.js')}}"></script>