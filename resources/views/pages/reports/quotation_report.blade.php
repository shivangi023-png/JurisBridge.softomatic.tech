<div class="row">
    <div class="col-3 pr-2">
        <div class="card reportMenu">
            @if ( session('role_id') == 1 || session('role_id') == 3 || session('role_id') == 5 ||session ('role_id')==8 || session('role_id' == 10))
            <a href="#quotationTab">
                <div class=" card-header border bg-primary text-white RMenuBox" id="quotation_report" onclick="get_report(this.id)">
                    <i class="bx bx-file bx_icon mr-2"></i> <strong class="ml-2">Quotation
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
            @endif
            @if ( session('role_id') == 1 || session('role_id') == 3 || session('role_id') == 5 ||session ('role_id')==8 || session('role_id' == 10))
            <a href="#clientTab">
                <div class="card-header border mt-1 text-dark RMenuBox" id="client_report" onclick="get_report(this.id)">
                    <i class="bx bx-user bx_icon"></i> <strong class="ml-2">Client
                        Reports</strong>
                </div>
            </a>
            @endif
            @if (session ('role_id')!=8)
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
        <div class="card border_primary quotation_card" id="quotationTab">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">1. Quotation Sent Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="quotation_sent_pdf">
                            <a href="javascript:void(0);" id="quotation_sent_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="quotation_sent_excel">
                            <a href="javascript:void(0);" class="ml-1" id="quotation_sent_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="quotation_sent_print">
                            <a href="javascript:void(0);" data-title="Quotation Sent" class="ml-1" id="quotation_sent_print" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">2. Quotations Finalized Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="quotation_finalized_pdf">
                            <a href="javascript:void(0);" id="quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="quotation_finalized_excel">
                            <a href="javascript:void(0);" class="ml-1" id="quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="quotation_finalized_print">
                            <a href="javascript:void(0);" class="ml-1" id="quotation_finalized_print" data-title="Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">3. Quotation by Sales Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="quotation_by_sales_pdf">
                            <a href="javascript:void(0);" id="quotation_by_sales_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="quotation_by_sales_excel">
                            <a href="javascript:void(0);" class="ml-1" id="quotation_by_sales_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="quotation_by_sales_print">
                            <a href="javascript:void(0);" class="ml-1" id="quotation_by_sales_print" data-title="Quotation By Sales" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-9">
                        <h6 class="font_style">4. Quotation by Office Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="quotation_by_office_pdf">
                            <a href="javascript:void(0);" id="quotation_by_office_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="quotation_by_office_excel">
                            <a href="javascript:void(0);" class="ml-1" id="quotation_by_office_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="quotation_by_office_print">
                            <a href="javascript:void(0);" class="ml-1" id="quotation_by_office_print" data-title="Quotation By Office" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">5. Servicewise Quotations Sent Report
                        </h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="servicewise_quotation_sent_pdf">
                            <a href="javascript:void(0);" id="servicewise_quotation_sent_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="servicewise_quotation_sent_excel">
                            <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_sent_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="servicewise_quotation_sent_print">
                            <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_sent_print" data-title="Servicewise Quotation Sent" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-9">
                        <h6 class="font_style">6. Servicewise Quotations Finalized
                            Report
                        </h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="servicewise_quotation_finalized_pdf">
                            <a href="javascript:void(0);" id="servicewise_quotation_finalized_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="servicewise_quotation_finalized_excel">
                            <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_finalized_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="servicewise_quotation_finalized_print">
                            <a href="javascript:void(0);" class="ml-1" id="servicewise_quotation_finalized_print" data-title="Servicewise Quotation Finalized" onclick="print(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9">
                        <h6 class="font_style">7. Clientwise Quotations Finalized
                            Report
                        </h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="clientwise_quotation_finalized_pdf">
                            <a href="javascript:void(0);" id="clientwise_quotation_finalized_pdf" onclick="download_pdf1(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="clientwise_quotation_finalized_excel">
                            <a href="javascript:void(0);" class="ml-1" id="clientwise_quotation_finalized_excel" onclick="download_excel1(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="clientwise_quotation_finalized_print">
                            <a href="javascript:void(0);" class="ml-1" id="clientwise_quotation_finalized_print" data-title="Clientwise Quotation Finalized" onclick="print1(this.id)"><img src="{{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/scripts/pages/get_report.js')}}"></script>