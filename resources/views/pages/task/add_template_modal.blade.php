<!-- New Template modal -->
<div class="modal fade text-left" id="NewTemplateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header-template">
                <h3 class="modal-title modal_template_title" id="myModalLabel1">Create Template</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
                <div class="modal-body-template">
                <div class="row">
                        <div class="col-12 modal-template_TForm">
                          <input type="hidden" id="templateid">
                          <input type="text" class="form-control mr-2 mb-50 mb-sm-0 template_name" placeholder="Template Name">
                            <span class="valid_err template_name_err"></span>
                        </div>
                          <div class="col-12 modal-template_TForm">
                         <textarea class="form-control mr-2 mb-50 mb-sm-0 template_description" placeholder="Description"></textarea> 
                         </div>
                        <div class="col-12 modal-template_TForm" id="form_template_status">
                            <select class="form-control" id="main_template_status">
                              <option value="">Status</option>
                              <option value="active" selected>Active</option>
                              <option value="inactive">Inactive</option>
                            </select>
                            <span class="valid_err main_template_status_err"></span>
                          </div>

                        
                    </div>

                    <button type="button" id="submit_template_btn" class="btn btn-primary px-5 template_btn">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block template_btn_name">Create</span>
                    </button>
                   <button type="button" class="btn btn-light-secondary px-5" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button> 

                </div>
               <!--  <div class="modal-footer">
                    <button type="button" id="submit_template_btn" class="btn btn-primary ml-1 px-5 template_btn">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block template_btn_name">Create</span>
                    </button>
                   <button type="button" class="btn btn-light-secondary px-5" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button> 
                </div> -->
        </div>
    </div>
</div>
<!-- End New Template modal -->
@section('vendor-scripts')
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>

@endsection
@section('page-scripts')
<script>
     $(document).ready(function(){
    $(".task_assignee").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Assignee",

  }).trigger('change');
  });
</script>
@endsection