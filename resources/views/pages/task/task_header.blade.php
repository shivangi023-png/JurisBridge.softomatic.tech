<div class="" style="top: 71px;position: relative;">
<!-- alert -->
  <!-- <div id="alert">
 <div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Project Updated successfully</span></div></div> 
</div> -->

<input type="hidden" value="{{url('/')}}" id="base_url">
<!-- end alert -->

  <div class="row">
 <div class="col-2">
    </div>

 <div class="col-8">
     <div id="alert">

   <!-- <div class="alert bg-rgba-success alert-dismissible ml-3" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Project Updated successfully</span></div></div> -->

</div>
    </div> 

    <div class="col-2 justify-content-end">


      <div class="btn-group" style="float: right;margin-right: 20px;">
        <button type="button" class="btn btn-link " data-toggle="dropdown" aria-expanded="false" style="color: #000;">
          Create <i class="bx bx-plus-circle"></i>
        </button>
        <div class="dropdown-menu" style="position: absolute;will-change: transform;z-index: 1;top: 0px;left: 0px;transform: translate3d(-55px, 38px, 0px);">
          <a class="dropdown-item new_modal_template text-capitalize font-weight-normal" href="javascript:void(0);">Template</a>
           <a data-toggle="modal" data-target="#NewProjectModal" href="javascript:void(0);" class="dropdown-item text-capitalize font-weight-normal" href="#">Project</a> 
           <!--<a href="javascript:void(0);" class="dropdown-item new_modal_project1 text-capitalize font-weight-normal">Project</a>-->
           <a href="javascript:void(0);" class="dropdown-item new_modal_task text-capitalize font-weight-normal">Task</a>
        </div>
      </div>
    </div>
  </div>
</div>
