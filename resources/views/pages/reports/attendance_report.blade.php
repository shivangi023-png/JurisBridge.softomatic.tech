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
                <div class="card-header border mt-1 bg-primary attendance_report text-white RMenuBox" id="attendance_report" onclick="get_report(this.id)">
                    <!-- <i class="bx bx-receipt bx_icon"></i> -->
                    <span>
                        <img src="{{asset('images/icon/white_attendance-report.svg')}}" width=16px; height="17px;" class="bx_icon attendance_img2">
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
        <div class="card border_primary attendance_card" id="attendanceTab">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <h6 class="font_style">1. Attendance Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="staff_attendance_pdf">
                            <a href="javascript:void(0);" id="staff_attendance_pdf" class="staff_attendance_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="staff_attendance_excel">
                            <a href="javascript:void(0);" class="ml-1" id="staff_attendance_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="staff_attendance_print">
                            <a href="javascript:void(0);" class="ml-1" id="staff_attendance_print" onclick="print(this.id)"><img src=" {{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9">
                        <h6 class="font_style">2. Salary attendance Report</h6>
                    </div>
                    <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="staff_attendance_pdf">
                            <a href="javascript:void(0);" id="salary_attendance_pdf" class="salary_attendance_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="salary_attendance_excel">
                            <a href="javascript:void(0);" class="ml-1" id="salary_attendance_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="salary_attendance_print">
                            <a href="javascript:void(0);" class="ml-1" id="salary_attendance_print" onclick="print(this.id)"><img src=" {{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9">
                        <h6 class="font_style">3. Staff Work Report</h6>
                    </div>
                  <div class="col-3">
                        <span data-tooltip="Export to PDF" data-value="staff_work_pdf">
                            <a href="javascript:void(0);" id="staff_work_pdf" class="staff_work_pdf" onclick="download_pdf(this.id)"><img src="{{asset('images/icon/pdfICon.svg')}}" alt="PDF" height="35" width="25">
                            </a>
                        </span>
                        <span data-tooltip="Export to Excel" data-value="staff_work_excel">
                            <a href="javascript:void(0);" class="ml-1" id="staff_work_excel" onclick="download_excel(this.id)"><img src="{{asset('images/icon/exelIcon.svg')}}" alt="Excel" height="35" width="25"></a>
                        </span>
                        <span data-tooltip="Print" data-value="staff_work_print">
                            <a href="javascript:void(0);" class="ml-1" id="staff_work_print" onclick="print(this.id)"><img src=" {{asset('images/icon/printIcon.svg')}}" alt="Print" height="35" width="25"></a>
                        </span>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/scripts/pages/get_report.js')}}"></script>