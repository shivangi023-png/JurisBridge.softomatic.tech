<div class="table-responsive">
    <table class="table company-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>Company Name</th>
                <th>Company Email</th>
                <th>Company Contact</th>
                <th>Company Address</th>
                <th>Short Code</th>
                <th>GST Number</th>
                <th>Pan Number</th>
                <th>Tax Applicable</th>
                <th>TDS Applicable</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($company as $val)
            <tr>
                <td><a href="company_edit-{{$val->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                </td>
                <td>{{$val->company_name}}</td>
                <td>{{$val->company_email}}</td>
                <td>{{$val->company_contact}}</td>
                <td>{{$val->company_address}}</td>
                <td>{{$val->short_code}}</td>
                <td>{{$val->gst_no}}</td>
                <td>{{$val->pan_no}}</td>
                <td>
                    @if($val->tax_applicable=='yes')
                    <span class="badge badge-pill badge-light-success">{{$val->tax_applicable}}</span>
                    @else
                    <span class="badge badge-pill badge-light-danger">{{$val->tax_applicable}}</span>
                    @endif

                </td>
                <td>
                    @if($val->tds_applicable=='yes')
                    <span class="badge badge-pill badge-light-success">{{$val->tds_applicable}}</span>
                    @else
                    <span class="badge badge-pill badge-light-danger">{{$val->tds_applicable}}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script script src="{{asset('js/scripts/pages/company.js')}}"></script>