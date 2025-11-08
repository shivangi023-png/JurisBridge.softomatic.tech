<div class="row">
    <div class="col-3 pr-2">
        <div class="card reportMenu">
            @if (session ('role_id')==8)
            <a href="#salesTab">
                <div class="card-header border sales_report bg-primary text-white RMenuBox" id="sales_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Sales
                        Reports </strong>
                </div>
            </a>
            <a href="#quotationTab">
                <div class=" card-header border mt-1 text-dark RMenuBox" id="quotation_report" onclick="get_report(this.id)">
                    <i class="bx bx-file bx_icon mr-2"></i> <strong class="ml-2">Quotation
                        Reports</strong>
                </div>
            </a>
            <a href="#clientTab">
                <div class="card-header border mt-1 text-dark RMenuBox" id="client_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Client
                        Reports</strong>
                </div>
            </a>
            @endif
            @if (session ('role_id')!=8)
            <a href="#expenseTab">
                <div class="card-header border mt-1 text-dark RMenuBox" id="expense_report" onclick="get_report(this.id)">
                    <i class="bx bx-money bx_icon"></i> <strong class="ml-2">Expense
                        Reports</strong>
                </div>
            </a>
            <a href="#attendanceTab">
                <div class="card-header border mt-1 text-dark RMenuBox" id="attendance_report" onclick="get_report(this.id)">
                    <span><img src="{{asset('images/icon/attendance-report.svg')}}" width=16px; height="17px;" class="bx_icon attendance_img1">
                        <strong class="ml-2">Attendance
                            Reports</strong></span>
                </div>
            </a>
            <a href="#accountingTab">
                <div class="card-header border mt-1 text-dark RMenuBox" id="accounting_report" onclick="get_report(this.id)">
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
            @endif
        </div>
    </div>
    <div class="col-9">
        <div class="card border_primary sales_card" id="salesTab">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">1. Assigned Leads Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="sales_assigned_leads_pdf">
                            <a href="javascript:void(0);" id="sales_assigned_leads_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="sales_assigned_leads_excel">
                            <a href="javascript:void(0);" class="ml-1" id="sales_assigned_leads_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="sales_assigned_leads_print">
                            <a href="javascript:void(0);" class="ml-1" id="sales_assigned_leads_print" data-title="Assigned Leads" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">2. Quotation Sent Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="sales_quotation_sent_pdf">
                            <a href="javascript:void(0);" id="sales_quotation_sent_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="sales_quotation_sent_excel">
                            <a href="javascript:void(0);" class="ml-1" id="sales_quotation_sent_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="sales_quotation_sent_print">
                            <a href="javascript:void(0);" data-title="Quotation Sent" class="ml-1" id="sales_quotation_sent_print" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">3. Quotation Finalized Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="sales_quotation_finalized_pdf">
                            <a href="javascript:void(0);" id="sales_quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="sales_quotation_finalized_excel">
                            <a href="javascript:void(0);" class="ml-1" id="sales_quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="sales_quotation_finalized_print">
                            <a href="javascript:void(0);" class="ml-1" id="sales_quotation_finalized_print" data-title="Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">4. Invoices against Quotation</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="sales_invoice_against_quotation_pdf">
                            <a href="javascript:void(0);" id="sales_invoice_against_quotation_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="sales_invoice_against_quotation_excel">
                            <a href="javascript:void(0);" class="ml-1" id="sales_invoice_against_quotation_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="sales_invoice_against_quotation_print">
                            <a href="javascript:void(0);" class="ml-1" id="sales_invoice_against_quotation_print" data-title="Invoice against Quotation" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>