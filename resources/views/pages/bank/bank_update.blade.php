<div class="table-responsive">
    <table class="table bank-data-table wrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>id</th>
                <th>Bank Name</th>
                <th>Branch</th>
                <th>Account Number</th>
                <th>IFSC Code</th>
                <th>Bank Address</th>
                <th>Default Bank Account</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach($bank_details as $val)
            <tr>
                <td><a href="#" class="updateModal btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-toggle="modal" data-target="#updateModal" data-bank_id="{{$val->id}}" data-bankname="{{$val->bankname}}" data-branchname="{{$val->branchname}}" data-accnumber="{{$val->accnumber}}" data-ifsccode="{{$val->ifsccode}}" data-bankaddress="{{$val->bankaddress}}" data-company="{{$val->company}}" data-default_bank_account="{{$val->default_bank_account}}" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                </td>
                <td>{{$i++}}</td>
                <td>{{$val->bankname}}</td>
                <td>{{$val->branchname}}</td>
                <td>{{$val->accnumber}}</td>
                <td>{{$val->ifsccode}}</td>
                <td>{{$val->bankaddress}}</td>
                <td>
                    @if($val->default_bank_account=='yes')
                    <span class="badge badge-pill badge-light-success">{{$val->default_bank_account}}</span>
                    @else
                    <span class="badge badge-pill badge-light-danger">{{$val->default_bank_account}}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>