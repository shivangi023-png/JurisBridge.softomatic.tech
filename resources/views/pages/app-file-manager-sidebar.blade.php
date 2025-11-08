@section('sidebar-content')
<div class="app-file-sidebar sidebar-content d-flex">
  <!-- App File sidebar - Left section Starts -->
  <div class="app-file-sidebar-left">
    <!-- sidebar close icon starts -->
    <span class="app-file-sidebar-close"><i class="bx bx-x"></i></span>
    <!-- sidebar close icon ends -->
    <div class="form-group add-new-file text-center">
      <!-- Add File Button -->
      <label data-toggle="modal" data-target="#full-scrn" class="btn btn-primary btn-block glow my-2 add-file-btn text-capitalize"><i
          class="bx bx-plus" ></i>Add Template</label>      
    </div>
    <div class="app-file-sidebar-content">
      <!-- App File Left Sidebar - Drive Content Starts -->
        <label class="app-file-label">Template Categories</label>
        {{-- <div class="list-group list-group-messages my-50"> 
          <a href="javascript:void(0);" class="list-group-item list-group-item-action pt-0 active">
            <div class="fonticon-wrap d-inline mr-25">
              <i class="livicon-evo"
                data-options="name: morph-folder.svg; size: 24px; style: lines; strokeColor:#5A8DEE; eventOn:grandparent; duration:0.85;"></i>
            </div>
            All Files
            <span class="badge badge-light-danger badge-pill badge-round float-right mt-50">2</span>
          </a>
          <a href="javascript:void(0);" class="list-group-item list-group-item-action">
            <div class="fonticon-wrap d-inline mr-25">
              <i class="livicon-evo"
                data-options="name: morph-desktop-smartphone.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;"></i>
            </div>
            My Devices
          </a>
          <a href="javascript:void(0);" class="list-group-item list-group-item-action">
            <div class="fonticon-wrap d-inline mr-25">
              <i class="livicon-evo"
                data-options="name: clock.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;"></i>
            </div> Recents
          </a>
          <a href="javascript:void(0);" class="list-group-item list-group-item-action">
            <div class="fonticon-wrap d-inline mr-25">
              <i class="livicon-evo"
                data-options="name: morph-star.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;"></i>
            </div>
            Important
          </a>
          <a href="javascript:void(0);" class="list-group-item list-group-item-action">
            <div class="fonticon-wrap d-inline mr-25">
              <i class="livicon-evo"
                data-options="name: trash.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;"></i>
            </div>
            Deleted Files
          </a>
        </div> --}}
      <!-- App File Left Sidebar - Drive Content Ends -->

      <!-- App File Left Sidebar - Labels Content Starts -->
        <label class="app-file-label"></label>
        <div class="list-group list-group-labels my-50">
          @foreach ($type as $row)
            <a href="javascript:void(0);" class="list-group-item list-group-item-action" data-type=<?php echo str_replace(' ', '_', $row->type); ?>>
              <div class="fonticon-wrap d-inline mr-25">
                <i class="livicon-evo"
                  data-options="name: servers.svg; size: 24px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;"></i>
              </div>            
              {{$row->type}}
            </a>   
          @endforeach  
        </div>
      <!-- App File Left Sidebar - Labels Content Ends -->

      <!-- App File Left Sidebar - Storage Content Starts -->
        {{-- <label class="app-file-label mb-75">Storage Status</label>
        <div class="d-flex mb-1">
          <div class="fonticon-wrap mr-1">
            <i class="livicon-evo cursor-pointer"
              data-options="name: save.svg; size: 30px; style: lines; strokeColor:#475f7b; eventOn:grandparent; duration:0.85;">
            </i>
          </div>
          <div class="file-manager-progress">
            <span class="text-muted font-size-small">19.5GB used of 25GB</span>
            <div class="progress progress-bar-primary progress-sm mb-0">
              <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="80" aria-valuemax="100"
                style="width:80%;"></div>
            </div>
          </div>
        </div>
        <a href="javascript:void(0);" class="font-weight-bold">Upgrade Storage</a> --}}
      <!-- App File Left Sidebar - Storage Content Ends -->
    </div>
  </div>
</div>
<!-- App File sidebar - Right section Starts -->
<div class="app-file-sidebar-info">
  {{-- <div class="card shadow-none mb-0 p-0 pb-1">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
      <h6 class="mb-0">Document.pdf</h6>
      <div class="app-file-action-icons d-flex align-items-center">
        <i class="bx bx-trash cursor-pointer mr-50"></i>
        <i class="bx bx-x close-icon cursor-pointer"></i>
      </div>
    </div>
    <ul class="nav nav-tabs justify-content-center" role="tablist">
      <li class="nav-item mr-1 pt-50 pr-1 border-right">
        <a class=" nav-link active d-flex align-items-center" id="details-tab" data-toggle="tab" href="#details"
          aria-controls="details" role="tab" aria-selected="true">
          <i class="bx bx-file mr-50"></i>Generate PDF</a>
      </li>
      <li class="nav-item pt-50 ">
        <a class=" nav-link d-flex align-items-center" id="activity-tab" data-toggle="tab" href="#activity"
          aria-controls="activity" role="tab" aria-selected="false">
          <i class="bx bx-pulse mr-50"></i>Preview</a>
      </li>
    </ul>
    <div class="tab-content pl-0">
      <div class="tab-pane active" id="details" aria-labelledby="details-tab" role="tabpanel">
        <div class="border-bottom d-flex align-items-center flex-column pb-1">
          <img src="{{asset('images/icon/pdf.png')}}" alt="PDF" height="42" width="35" class="my-1">
          <p class="mt-2">15.3mb</p>
        </div>
        <div class="card-body pt-2">
           
        </div>
      </div>
      <div class="tab-pane pl-0" id="activity" aria-labelledby="activity-tab" role="tabpanel">
        
      </div>
    </div>
  </div> --}}
</div>

<!-- App File sidebar - Right section Ends -->
<!-- Modal For Template save-->
  <div class="modal fade text-left w-100" id="full-scrn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel20" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel20">Save Template</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <form id="templateSave" method="POST" enctype="multipart/form-data" action="template_create">
          <div class="modal-body">        
            <div class="row">
              <div class="col-md-4">
                <fieldset class="form-group">
                  <div class="input-group ">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Category</label>
                    </div>
                    <select class="form-control" id="inputGroupSelect01 type" name="type">
                      @foreach ($type as $row)
                      <option value=<?php echo str_replace(' ', '_', $row->type); ?>>{{$row->type}}</option>
                      @endforeach
                    </select>
                  </div>
                </fieldset>
              </div>
              <fieldset class="form-label-group col-md-4">
                <input type="text" class="form-control" id="floating-label1 template_name" name="template_name" placeholder="Template Name">
                <label for="floating-label1">Template Name</label>
              </fieldset>
              <fieldset class="form-label-group col-md-4">
                <input type="text" class="form-control" id="floating-label1 description" name="description" placeholder="Description">
                <label for="floating-label1">Description</label>
              </fieldset>
            </div>
            <div class=" container row">
              <input type="button" class="btn btn-primary mb-1 col-3" id="csv" value="Click me to select CSV File">
              <input type="file" id="csvFile" name="csvFile" style="display:none"/> 
              <div class="col-1"> </div>
              <input type="button" class="btn btn-primary mb-1 col-3" id="html" value="Click me to select HTML File"> 
              <input type="file" id="htmlFile" name="htmlFile" style="display:none"/> 
              <div class="col-1"> </div>
              <input type="button" class="btn btn-primary mb-1 col-3" id="css" value="Click me to select CSS File"> 
              <input type="file" id="cssFile" name="cssFile" style="display:none"/> 
            </div> 
                
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Close</span>
            </button>
            <input type="submit" class="btn btn-primary ml-1" id="saveTemplate" value="Save">
          </div>
        </form>
      </div>
    </div>
  </div>
<!-- Template save Modal End-->
@endsection

