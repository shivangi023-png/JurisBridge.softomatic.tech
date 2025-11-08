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
                <div class="card-header border mt-1 bg-primary expense_report text-white RMenuBox" id="expense_report" onclick="get_report(this.id)">
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
        <div class="card border_primary expense_card" id="expenseTab">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">1. Expense Report - Staffwise</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="expense_report_staffwise_pdf">
                            <a href="javascript:void(0);" id="expense_report_staffwise_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="expense_report_staffwise_excel">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_staffwise_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="expense_report_staffwise_print">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_staffwise_print" data-title="Staffwise Expense" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">2. Expense Report - Ledgerwise</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="expense_report_ledgerwise_pdf">
                            <a href="javascript:void(0);" id="expense_report_ledgerwise_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="expense_report_ledgerwise_excel">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_ledgerwise_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <spa data-tooltip="Print" data-value="expense_report_ledgerwise_print">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_ledgerwise_print" data-title="Ledgerwise Expense" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                            </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">3. Expense Report - Clientwise</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="expense_report_clientwise_pdf">
                            <a href="javascript:void(0);" id="expense_report_clientwise_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="expense_report_clientwise_excel">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_clientwise_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="expense_report_clientwise_print">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_clientwise_print" data-title="Clientwise Expense" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">4. Expense Report - Reimbursement</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="expense_report_reimbursement_pdf">
                            <a href="javascript:void(0);" id="expense_report_reimbursement_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="expense_report_reimbursement_excel">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_reimbursement_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span><span data-tooltip="Print" data-value="expense_report_reimbursement_print">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_reimbursement_print" data-title="Reimbursement Expense" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">5. Expense Report - Client Ledgerwise
                        </h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="expense_report_client_ledgerwise_pdf">
                            <a href="javascript:void(0);" id="expense_report_client_ledgerwise_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="expense_report_client_ledgerwise_excel">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_client_ledgerwise_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="expense_report_client_ledgerwise_print">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_client_ledgerwise_print" data-title="Client Ledgerwise Expense" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">6. Expense Report- Staff Ledgerwise</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="expense_report_staff_ledgerwise_pdf">
                            <a href="javascript:void(0);" id="expense_report_staff_ledgerwise_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="expense_report_staff_ledgerwise_excel">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_staff_ledgerwise_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="expense_report_staff_ledgerwise_print">
                            <a href="javascript:void(0);" class="ml-1" id="expense_report_staff_ledgerwise_print" data-title="Staff Ledgerwise Expense" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9">
                        <h6 class="font_style">7. Daily Expense Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="daily_expense_report_pdf">
                            <a href="javascript:void(0);" id="daily_expense_report_pdf" onclick="download_pdf1(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="daily_expense_report_excel">
                            <a href="javascript:void(0);" class="ml-1" id="daily_expense_report_excel" onclick="download_excel1(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="daily_expense_report_print">
                            <a href="javascript:void(0);" class="ml-1" id="daily_expense_report_print" data-title="Daily Expense" onclick="print1(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/scripts/pages/get_report.js')}}"></script>