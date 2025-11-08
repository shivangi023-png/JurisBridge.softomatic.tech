@extends('layouts.contentLayoutMaster')
{{-- page title --}}
@section('title','File Manager Application')
{{-- page styles --}}
@section('page-styles') 
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-file-manager.css')}}">
@endsection
{{-- sidebar included --}}
@include('pages.app-file-manager-sidebar')

@section('content')
<!-- File Manager app overlay -->
<div class="app-file-overlay"></div>
<div class="app-file-area">
  <!-- File App Content Area -->
  <!-- App File Header Starts -->
  <div class="app-file-header">
    <!-- Header search bar starts -->
    <div class="app-file-header-search flex-grow-1">
      <div class="sidebar-toggle d-block d-lg-none">
        <i class="bx bx-menu"></i>
      </div>
      <fieldset class="form-group position-relative has-icon-left m-0">
        <input type="text" class="form-control border-0 shadow-none" id="file-search" placeholder="Search file">
        <div class="form-control-position">
          <i class="bx bx-search"></i>
        </div>
      </fieldset>
    </div>
    <!-- Header search bar Ends -->
    <!-- Header Icons Starts -->
    <div class="app-file-header-icons">
      <div class="fonticon-wrap d-inline mx-sm-1 align-middle">
        <i class="livicon-evo cursor-pointer"
          data-options="name: user.svg; size: 24px; style: lines; strokeColor:#596778; duration:0.85;"></i>
      </div>
      <div class="fonticon-wrap d-inline mr-sm-50 align-middle">
        <i class="livicon-evo cursor-pointer"
          data-options="name: trash.svg; size: 24px; style: lines; strokeColor:#596778; duration:0.85;"></i>
      </div>
      <i class="bx bx-dots-vertical-rounded font-medium-3 align-middle cursor-pointer"></i>
    </div>
    <!-- Header Icons Ends -->
  </div>
  <!-- App File Header Ends -->

  <!-- App File Content Starts -->
  <div class="app-file-content p-2">
    <h5>All Files</h5>

    <!-- App File - Recent Accessed Files Section Starts -->
      {{-- <label class="app-file-label">Recently Accessed Files</label>
      <div class="row app-file-recent-access">
        <div class="col-md-3 col-6">
          <div class="card border shadow-none mb-1 app-file-info">
            <div class="app-file-content-logo card-img-top">
              <img class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></img>
              <img class="d-block mx-auto" src="{{asset('images/icon/pdf.png')}}" height="38" width="30"
                alt="Card image cap">
            </div>
            <div class="card-body p-50">
              <div class="app-file-recent-details">
                <div class="app-file-name font-size-small font-weight-bold">Felecia Resume.pdf</div>
                <div class="app-file-size font-size-small text-muted mb-25">12.85kb</div>
                <div class="app-file-last-access font-size-small text-muted">Last accessed : 3 hours ago</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="card border shadow-none mb-1 app-file-info">
            <div class="app-file-content-logo card-img-top">
              <img class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></img>
              <img class="d-block mx-auto" src="{{asset('images/icon/psd.png')}}" height="38" width="30"
                alt="Card image cap">
            </div>
            <div class="card-body p-50">
              <div class="app-file-content-details">
                <div class="app-file-name font-size-small font-weight-bold">Logo_design.psd</div>
                <div class="app-file-size font-size-small text-muted mb-25">15.60mb</div>
                <div class="app-file-last-access font-size-small text-muted">Last accessed : 3 hours ago</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="card border shadow-none mb-1 app-file-info">
            <div class="app-file-content-logo card-img-top">
              <img class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></img>
              <img class="d-block mx-auto" src="{{asset('images/icon/doc.png')}}" height="38" width="30"
                alt="Card image cap">
            </div>
            <div class="card-body p-50">
              <div class="app-file-content-details">
                <div class="app-file-name font-size-small font-weight-bold">Music_Two.xyz</div>
                <div class="app-file-size font-size-small text-muted mb-25">1.2gb</div>
                <div class="app-file-last-access font-size-small text-muted">Last accessed : 3 hours ago</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="card border shadow-none mb-1 app-file-info">
            <div class="app-file-content-logo card-img-top">
              <i class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></i>
              <img class="d-block mx-auto" src="{{asset('images/icon/sketch.png')}}" height="38" width="30"
                alt="Card image cap">
            </div>
            <div class="card-body p-50">
              <div class="app-file-content-details">
                <div class="app-file-name font-size-small font-weight-bold">Application.sketch</div>
                <div class="app-file-size font-size-small text-muted mb-25">92.83kb</div>
                <div class="app-file-last-access font-size-small text-muted">Last accessed : 3 hours ago</div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
    <!-- App File - Recent Accessed Files Section Ends -->

    <!-- App File - Folder Section Starts -->
      
    <!-- App File - Folder Section Ends -->

    <!-- App File - Files Section Starts -->
    <label class="app-file-label">Files</label>
    <div class="row app-file-files">
      
    </div>
    {{-- <div class="row">
      <div class="col-md-3 col-6">
        <div class="card border shadow-none mb-1 app-file-info">
          <div class="app-file-content-logo card-img-top">
            <img class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></img>
            <img class="d-block mx-auto" src="{{asset('images/icon/psd.png')}}" height="38" width="30"
              alt="Card image cap">
          </div>
          <div class="card-body p-50">
            <div class="app-file-details">
              <div class="app-file-name font-size-small font-weight-bold">Logo.psd</div>
              <div class="app-file-size font-size-small text-muted mb-25">10.6kb</div>
              <div class="app-file-type font-size-small text-muted">Photoshop File</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="card border shadow-none mb-1 app-file-info">
          <div class="app-file-content-logo card-img-top">
            <i class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></i>
            <img class="d-block mx-auto" src="{{asset('images/icon/sketch.png')}}" height="38" width="30"
              alt="Card image cap">
          </div>
          <div class="card-body p-50">
            <div class="app-file-details">
              <div class="app-file-name font-size-small font-weight-bold">Logo_Design.sketch</div>
              <div class="app-file-size font-size-small text-muted mb-25">256.5kb</div>
              <div class="app-file-type font-size-small text-muted">Sketch File</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="card border shadow-none mb-1 app-file-info">
          <div class="app-file-content-logo card-img-top">
            <img class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></img>
            <img class="d-block mx-auto" src="{{asset('images/icon/doc.png')}}" height="38" width="30"
              alt="Card image cap">
          </div>
          <div class="card-body p-50">
            <div class="app-file-details">
              <div class="app-file-name font-size-small font-weight-bold">Bootstrap.xyz</div>
              <div class="app-file-size font-size-small text-muted mb-25">0.0kb</div>
              <div class="app-file-type font-size-small text-muted">Unknown File</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="card border shadow-none mb-1 app-file-info">
          <div class="app-file-content-logo card-img-top">
            <img class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></img>
            <img class="d-block mx-auto" src="{{asset('images/icon/pdf.png')}}" height="38" width="30"
              alt="Card image cap">
          </div>
          <div class="card-body p-50">
            <div class="app-file-details">
              <div class="app-file-name font-size-small font-weight-bold">Read_Me.pdf</div>
              <div class="app-file-size font-size-small text-muted mb-25">10.5kb</div>
              <div class="app-file-type font-size-small text-muted">PDF File</div>
            </div>
          </div>
        </div>
      </div>
    </div> --}}
    <!-- App File - Files Section Ends -->
  </div>
</div>
<!-- Modal For Generatinf INput-->
<div class="modal fade text-left w-100" id="inputModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel20" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full">
    <form action="generate_pdf" method="post" id="pdfGen"> 
    @csrf
    <div class="modal-content inputModal">
      {{-- <div class="modal-header inputModal">
        <h4 class="modal-title" id="myModalLabel20">Input Modal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="bx bx-x"></i>
        </button>
      </div> --}}
      
      {{-- <div class="row modal-body" id="modalInput">
        
      </div> --}}
      
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Close</span>
        </button>
        <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
          <i class="bx bx-check d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Accept</span>
        </button>
      </div> --}}
    </div>
  </form>
  </div>
</div>
<!-- End Modal For Input-->
@endsection
{{-- page styles --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/app-file-manager.js')}}"></script>
<script>
  $(document).ready(function(){
    
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $('#csv').click(function(){
        $('#csvFile').trigger('click');
    });
    $('#html').click(function(){
      $('#htmlFile').trigger('click');
    });
    $('#css').click(function(){
      $('#cssFile').trigger('click');
    });
    $(document).on('click','.list-group-item',function(){
      var type = $(this).data('type');
      $.ajax({
          type:'get',
          url:'file-manager',
          data:{type:type},
          success:function(data){
            var res = JSON.parse(data);
            $('.app-file-files').empty().html(res.out);
            
          },
          error:function(data){
            console.log(data);
          },
          });
    });
    $('#templateSave').submit(function(e){ 
      e.preventDefault();
      var formdata = new FormData(this);
      $.ajax({
          type:'post',
          url:'template_create',           
          data:formdata,            
          datatype:'text',
          contentType: false,
          cache: false,
          processData:false,
          success:function(data){
              $('#templateSave').trigger('reset');
              console.log(data);
          },
          error:function(data){
              console.log(data);
          },
      });
    });
    $(document).on('click','.app-file-info', function () {
      var temp_name = $(this).data('name');
      var temp_desc = $(this).data('description');
      var temp_id = $(this).data('id');
      
      $.ajax({
        type:'post',
        url:'template_generation_index',           
        data:{temp_id:temp_id,temp_name:temp_name,temp_desc:temp_desc},
        success:function(data){
          var res = JSON.parse(data);
          $('.app-file-sidebar-info').empty().html(res.out);
          var sideBarInfo = $(".app-file-sidebar-info");
          var appContentOverlay = $(".app-file-overlay");
          sideBarInfo.addClass('show');
          appContentOverlay.addClass('show');
        },
        error:function(data){
          console.log(data);
        },

      });
      
    });
    $(document).on('change','.clients',function(){
      var client_id = $('.clients').val();
      var temp_id = $('#temp_id_gen').val();
      $.ajax({
        type:'post',
        url:'template_list_gen',           
        data:{client_id:client_id,temp_id:temp_id},
        success:function(data){
          var res = JSON.parse(data);
          $('#templates_generated').empty().html(res.out);
        },
        error:function(data){
          console.log(data);
        },
      });
    });
    $(document).on('click','#btnInput',function(){
      var temp_id = $('#temp_id').val();
      var client_id = $('.clients').val();
      var template_get_id = $('.tempList').val();
      var radio = document.getElementsByName('bsradio');
      var task = '';
      for(i = 0; i < radio.length; i++) {
          if(radio[i].checked)
            task=radio[i].value;
      }
      $.ajax({
        type:'post',
        url:'generate_input',           
        data:{client_id:client_id,temp_id:temp_id,template_get_id:template_get_id,task:task},
        success:function(data){
          console.log(data);
          var res = JSON.parse(data);
          $('.inputModal').empty().html(res.out);
        },
        error:function(data){
          console.log(data);
        },
      });
    });
  });
  </script>
@endsection
  