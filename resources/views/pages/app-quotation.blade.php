@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
<head></head>
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/ui/prism.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/file-uploaders/dropzone.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">

@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/file-uploaders/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection
@section('content')
<section id="add-row">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="alert alert-danger mb-2" role="alert" style="display: none" id="dateAlert">
            Selected Date should be ahead of todays date
           </div>
          <div class="card-header">
            <h4 class="card-title">Quotations</h4>
          </div>
          <div class="card-body"> 
              <!--login form Modal -->
              <div class="modal fade text-left w-100" id="inlineForm" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel33" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" id="myModalLabel33">Login Form </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                      </button>
                    </div>
                    <form action="#">
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-11">
                            <fieldset class="form-group">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01">Clients</label>
                                </div>
                                <select class="form-control" id="inputGroupSelect01">
                                  @foreach ($clients as $item)
                                    <option value={{$item->id}}>{{$item->client_name}}</option>
                                  @endforeach  
                                </select>
                              </div>
                            </fieldset>
                          </div>                          
                          <div class="col-1">
                              <button type="button" class="btn btn-icon btn-outline-primary mr-1 mb-1"><i class="bx bx-search"></i></button>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <fieldset class="form-group">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01">Services</label>
                                </div>
                                <select class="form-control" id="inputGroupSelect01">
                                  @foreach ($service as $item)
                                    <option value={{$item->id}}>{{$item->name}}</option>
                                  @endforeach  
                                </select>
                              </div>
                            </fieldset>
                          </div>
                        </div>
                        <div class="row">                          
                          <div class="col-md-4 col-sm-4 mt-1 ">
                            <fieldset class="form-label-group">
                              <input type="number" class="form-control" value="1" min="1" max="20" >
                              <label for="floating-label1">Enter Number of Units</label>
                            </fieldset>
                          </div>
                          <div class="col-md-4 col-sm-4 mt-1">
                            <fieldset class="form-label-group">
                              <input type="number" class="form-control" id="floating-label1" placeholder="Amount of Service">
                              <label for="floating-label1">Amount of Service</label>
                            </fieldset>
                          </div>
                          <div class="col-md-4 col-sm-4 mt-1">
                            <fieldset class="form-label-group">
                              <input type="number" class="form-control" id="floating-label1" placeholder="Total Amount">
                              <label for="floating-label1">Total Amount</label>
                            </fieldset>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                          <i class="bx bx-x d-block d-sm-none"></i>
                          <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                          <i class="bx bx-check d-block d-sm-none"></i>
                          <span class="d-none d-sm-block">login</span>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <fieldset class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Clients</label>
                    </div>
                    <select class="form-control" id="client">
                      <option>-----</option>
                      @foreach ($clients as $item)
                        <option value={{$item->id}}>{{$item->client_name}}</option>
                      @endforeach  
                    </select>
                  </div>
                </fieldset>
              </div>            
            </div>
            <div class="action-dropdown-btn d-none">
              <div class="dropdown invoice-filter-action">
                <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  Filter Invoice
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-filter-btn">
                  <a class="dropdown-item" href="javascript:;">Partial Payment</a>
                  <a class="dropdown-item" href="javascript:;">Unpaid</a>
                  <a class="dropdown-item" href="javascript:;">Paid</a>
                </div>
              </div>
              <div class="dropdown invoice-options">
                <button class="btn border dropdown-toggle mr-2" type="button" id="invoice-options-btn" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  Options
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoice-options-btn">
                <a class="dropdown-item" href="javascript:;">Edit</a>
                  <a class="dropdown-item" href="javascript:;">Delete</a>
                  <a class="dropdown-item" href="javascript:;">Send</a>
                </div>
          
                <a href="{{asset('app/invoice/add')}}" class="btn btn-icon btn-outline-primary mr-1" role="button" aria-pressed="true" data-toggle="modal" data-target="#inlineForm">
                <i class="bx bx-plus"></i>Add Invoice</a>
          
              </div>
            </div>
            <div class="table-responsive">
              <table class="table invoice-data-table currentDate-responsive wrap" style="wicurrentDateh:100%">
                <thead>
                  <tr>
                    <th></th>
                    <th>#</th>
                    <th>Clients</th>
                    <th>Service</th>
                    <th>Amount Per Unit</th>
                    <th>No Of Units</th>
                    <th>Total Amount</th>
                    <th>Send Date</th>
                    <th>Finalized</th>
                    <th>Action</th>   
                  </tr>
                </thead>
                <tbody id="quotation_table">
                  @foreach ($quotation_list as $item)
                    <tr>
                      <td>{{$item->quotation_details_id}}</td>
                      <td>{{$item->client_name}}</td>
                      <td>{{$item->task_name}}</td>
                      <td><?php echo number_format($item->amount); ?></td>
                      <td><?php echo number_format($item->amount); ?></td>
                      <td><?php echo number_format($item->total_amt); ?></td>
                      <td><?php echo date("d-m-Y",strtotime($item->send_date)); ?></td>
                      <td>
                        @if ($item->finalize == 'no')
                          <span class="badge badge-light-danger badge-pill">{{ $item->finalize }}</span>
                        @else
                          <span class="badge badge-light-success badge-pill">{{ $item->finalize }}</span>
                        @endif
                      </td>
                      <td>
                        <div class="row">
                          <div class="fonticon-wrap"><button type="button" id="edit" data-toggle="modal" data-target="#editModal" class=" btn btn-icon btn-outline-primary round mr-1 mb-1" data-quotation_details_id={{$item->quotation_details_id}} data-quotation_id={{$item->id}}><i class="bx bxs-edit-alt" ></i></button></div>
                          <div class="fonticon-wrap"><button type="button" id="delete" class=" btn btn-icon btn-light-danger round mr-1 mb-1" data-quotation_details_id={{$item->quotation_details_id}} data-quotation_id={{$item->id}}><i class="bx bx-x"></i></button></div>                          
                          <div class="fonticon-wrap"><button type="button" id="print" class=" btn btn-icon btn-warning round mr-1 mb-1" data-quotation_details_id={{$item->quotation_details_id}} data-quotation_id={{$item->id}}><i class="bx bx-printer"></i></button></div>
                          <div class="fonticon-wrap"><button type="button" id="finalize" data-toggle="modal" data-target="#exampleModalLong" class=" btn btn-icon bg-success bg-lighten-5 round mr-1 mb-1" data-quotation_details_id={{$item->quotation_details_id}} data-quotation_id={{$item->id}}><i class="bx bx-check"></i></button></div>
                          <div class="fonticon-wrap"><button type="button" id="send" class=" btn btn-icon bg-info round mr-1 mb-1" data-quotation_details_id={{$item->quotation_details_id}} data-quotation_id={{$item->id}}><i class="bx bx-send"></i></button></div>
                        </div>
                      </td>                      
                    </tr>   
                  @endforeach                  
                </tbody>
              </table>
            </div>
            <!-- Modal for date pick-->
            <!--scrollbar Modal -->
          <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Scrolling Modal</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">                  
                  <fieldset class="form-group position-relative has-icon-left">
                    <input type="text" class="form-control pickadate" placeholder="Select Date" id="finalize_date">
                    <div class="form-control-position">
                      <i class='bx bx-calendar'></i>
                    </div>
                  </fieldset>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                  </button>

                  <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block" id="finalizeBtn">Finalize</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal for Edit-->
          <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Edit Details</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">                  
                  <fieldset class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Services</label>
                      </div>
                      <select class="form-control" id="serviceEdit">
                        @foreach ($service as $item)
                          <option value={{$item->id}}>{{$item->name}}</option>
                        @endforeach  
                      </select>
                    </div>
                  </fieldset>
                  <fieldset class="form-label-group">
                    <input type="number" class="form-control" id="floating-label1 editServiceAmt" placeholder="Amount of Service" disabled>
                    <label for="floating-label1">Amount of Service</label>
                  </fieldset>
                  <fieldset class="form-label-group">
                    <input type="number" class="form-control" id="floating-label1 editUnits" placeholder="Enter Units">
                    <label for="floating-label1">Enter Units</label>
                  </fieldset>
                  <fieldset class="form-group position-relative has-icon-left">
                    <input type="text" class="form-control pickadate" placeholder="Select Date" id="update_quotation_date">
                    <div class="form-control-position">
                      <i class='bx bx-calendar'></i>
                    </div>
                  </fieldset>
                  <fieldset class="form-label-group">
                    <input type="number" class="form-control" id="floating-label1 editTotalAmt" placeholder="Total Amount" disabled>
                    <label for="floating-label1">Total Amount</label>
                  </fieldset>
                  <form action="#" class="dropzone dropzone-area" id="editFile" name="eFile">
                    <div class="dz-message">Drop Files Here To Upload</div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                  </button>

                  <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" id="editDetails">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block" id="editDetails">Save</span>
                  </button>
                
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>    
</section>

@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
  <script src="{{asset('vendors/js/file-uploaders/dropzone.min.js')}}"></script>  
  <script src="{{asset('vendors/js/ui/prism.min.js')}}"></script>
  <script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
  <script src="{{asset('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
  <script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap4.min.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
  <script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/extensions/dropzone.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
<script src="{{asset('js/scripts/forms/select/form-select2.js')}}"></script>
<script src="{{asset('js/scripts/forms/number-input.js')}}"></script>
<script type="text/javascript">
    $( document ).ready(function() {
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('#client').on('change',function(){
        var client_id= $('#client').val();
        $.ajax({
          type:'get',
          url:'test',
          data:{client_id:client_id},
          success:function(data){
            var res = JSON.parse(data); 
            $('#quotation_table').empty().html(res.out);  
          },
          error:function(data){
            console.log(data);
          },
        });
      });
      $(document).on('click','#delete',function(){
        var quotation_details_id=$(this).data('quotation_details_id');
        var quotation_id=$(this).data('quotation_id');
        var client_id= $('#client').val();
        $.ajax({
          type:'get',
          url:'delete',
          data:{quotation_details_id:quotation_details_id,quotation_id:quotation_id,client_id:client_id},
          success:function(data){
            console.log(data);
            // var res = JSON.parse(data); 
            $('#quotation_table').empty().html(data.out); 
          },
          error:function(data){
            console.log(data);
          },
        });
      });
      $(document).on('click','#finalizeBtn',function(){
        var quotation_details_id=$(this).data('quotation_details_id');
        var quotation_id=$(this).data('quotation_id');
        var client_id= $('#client').val();
        var currentDate = new Date();
        currentDate.setHours(0,0,0,0);
        var date = $('#finalize_date').val();
        var selectedDate = new Date(date);
        if(currentDate < selectedDate ){
          $.ajax({
            type:'get',
            url:'finalize_quotation',
            data:{quotation_details_id:quotation_details_id,client_id:client_id,date:selectedDate,quotation_id:quotation_id},
            success:function(data){
              console.log(data);
              var res = JSON.parse(data); 
              $('#quotation_table').empty().html(res.out); 
            },
            error:function(data){
              console.log(data);
            },
          });
        }else{
          alertShow();
          alertTimer();          
        }
        
      });
      function alertShow(){
        $('#dateAlert').show()
      }
      function alertTimer(){
        var counter = 0;
        var interval = setInterval(function() {
            counter++;
            // Display 'counter' wherever you want to display it.
            if (counter == 5) {
                $('#dateAlert').hide()
                clearInterval(interval);
            }
        }, 1000);
      }
      $(document).on('click','#editDetails',function(){
        var quotation_details_id=$(this).data('quotation_details_id');
        var quotation_id=$(this).data('quotation_id');
        var update_quotation_date = $('#update_quotation_date').val();
        var client_id= $('#client').val();
        var serviceEdit = $('#serviceEdit').val();
        var editUnits = $('#editUnits').val();
        var editServiceAmt = $('#editServiceAmt').val();
        var editTotalAmt = $('#editTotalAmt').val();
        var editFile = $('#editFile').val();
        alert(editFile);
        // $.ajax({
        //   type:'get',
        //   url:'delete',
        //   data:{quotation_details_id:quotation_details_id,quotation_id:quotation_id,client_id:client_id},
        //   success:function(data){
        //     console.log(data);
        //     // var res = JSON.parse(data); 
        //     $('#quotation_table').empty().html(data.out); 
        //   },
        //   error:function(data){
        //     console.log(data);
        //   },
        // });
      });

    });
</script>
@endsection

