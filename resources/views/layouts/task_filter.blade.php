<style>
  .datepicker
  {
    border:1 !important;
  }
</style>
<div class="todo-fixed-search d-flex justify-content-between align-items-center" style="margin-top:16px">
      
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/form_control.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<style>
    .staff-dropdown {
        height: 250px !important;
        overflow-y: auto !important;
    }
</style>
@endsection     
<fieldset class="form-group   flex-grow-1" style="margin-bottom:0px;padding-left:10px;padding-right:12px">
        <select class="form-control input_control task_filter_type" multiple="multiple">
            @foreach($task_type as $row)
            <option value="{{$row->id}}">{{$row->type}}</option>
            @endforeach
        </select>
               
      </fieldset>
      <fieldset class="form-group flex-grow-1" style="margin-bottom:0px;padding-right:12px">
      <select class="form-control task_filter_status" multiple="multiple">
              
             @foreach($task_status_master as $row)
            <option value="{{$row->id}}">{{$row->status}}</option>
            @endforeach
                    </select>
                       
      </fieldset>
    
      <fieldset class="form-group   flex-grow-1" style="margin-bottom:0px;padding-right:12px">
      <select class="form-control task_filter_priority" multiple="multiple">
              
      @foreach($task_priority as $row)
            <option value="{{$row->id}}">{{$row->priority}}</option>
            @endforeach
                    </select>
                       
      </fieldset>

      
      <fieldset class="form-group" style="margin-bottom:0px;padding-right:12px">
       <input type="text" style="top: 461.4px" class="form-control datepicker task_filter_from_date" placeholder="From Date">
                       
      </fieldset>
      <fieldset class="form-group" style="margin-bottom:0px;padding-right:12px">
       <input type="text" style="top: 461.4px" class="form-control datepicker task_filter_to_date" placeholder="To Date">
                       
      </fieldset>
      <fieldset class="form-group position-relative has-icon-left flex-grow-1" style="margin-bottom:0px">
      <button type="button" class="btn btn-outline-primary filter_task" id="filter_task"><i class="bx bx-filter-alt"></i><span class="align-middle ml-25">Filter</span></button>
      </fieldset>

      </div>
     
      <div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert" style="padding:5px;display:none">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" fdprocessedid="2gydd">
              <span aria-hidden="true">×</span>
            </button>
            <div class="d-flex align-items-center">
              <i class="bx bx-error"></i>
              <span>
                Please select any one field.
              </span>
            </div>
          </div>

