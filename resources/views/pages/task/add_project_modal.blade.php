
<!-- New Project modal -->
<div class="modal fade text-left" id="NewProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title modal_project_title" id="myModalLabel1">Create Project</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
                <div class="modal-body">
                <div class="row">
                <input type="hidden" id="proj_quotation_id">
                        <div class="col-12 TForm">
                          <input type="hidden" id="projectid">
                          <input type="text" class="form-control mr-2 mb-50 mb-sm-0 project_name" placeholder="Project Name">
                            <span class="valid_err project_name_err"></span>
                        </div>

                        <div class="col-6 TForm">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 datepicker project_start_date" placeholder="Start Date">
                            <span class="valid_err project_start_date_err"></span>
                        </div>
                        <div class="col-6 TForm">
                                <input type="text" class="form-control mr-2 mb-50 mb-sm-0 datepicker project_end_date" placeholder="End Date">
                            <span class="valid_err project_end_date_err"></span>
                        </div>

                          <div class="col-12 TForm">
                            <select class="form-control select2 staff_id">
                             
                              @foreach($staff_list as $staff)
                              <option value="{{$staff->sid}}">{{$staff->name}}</option>
                              @endforeach
                            </select>
                            <span class="valid_err staff_err"></span>
                          </div>
                         <div class="col-12 TForm">
                            <select class="form-control" id="project_status">
                             
                              @foreach($project_status_master as $row)
                              <option value="{{$row->id}}">{{$row->status}}</option>
                              @endforeach
                            </select>
                            <span class="valid_err project_status_err"></span>
                          </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit_project_btn" class="btn btn-primary ml-1 px-5 project_btn">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block project_btn_name">Create</span>
                    </button>
                   <button type="button" class="btn btn-light-secondary px-5" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button> 
                </div>
        </div>
    </div>
</div>
<!-- End Project modal -->
@section('vendor-scripts')
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('vendors/js/extensions/dragula.min.js') }}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
<script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
<script>
  $(document).ready(function(){
   
  });
   
</script>
@endsection
