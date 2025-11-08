@extends('layouts.contentLayoutMaster')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- page title --}}
@section('title','Chat Communication')
{{-- vendor style --}}

{{-- page style --}}
<style>
  .disable-click{
    pointer-events:none;
    
}
</style>
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/chat.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css">
@endsection
@section('content')
 <div id="alert">
 </div>
<section class="client-list-wrapper">
  <!-- <div class="card">
        <div class="card-body">  -->
           <div class="row no-gutters">
    <div class="col-md-4 border-right">
    <!--   <div class="settings-tray">
        <img class="profile-image" src="https://randomuser.me/api/portraits/men/4.jpg" alt="">
        <span class="settings-tray--right">
          <i class="material-icons">message</i>
        </span>
      </div> -->
      <div class="search-box">
        <div class="input-wrapper">
          <i class="material-icons">search</i>
          <input placeholder="Search here" type="text">
        </div>
      </div>
      <div class="chat_list">
         <div id="accordion" class="client_list"></div>
      </div>
    </div>
    <div class="col-md-8 chat_div" style="background: #fff;"></div>
  </div>
   <!--   </div>
    </div>  -->
</section>
@endsection
{{-- vendor scripts --}}
@section('vendor-scripts')
@include('scripts.datatable_scripts')
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
 $(document).ready(function(){
    function scrollToBottom() {
    const scrollContainer = document.getElementById('messages');
    scrollContainer.scrollTo({
        top: scrollContainer.scrollHeight,
        left: 0,
        behavior: 'smooth'
    });
}

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } });
    get_mycases_list();
  
  
 function get_mycases_list() {
    $.ajax({
              type:'get',
              url:'get_mycases_list',
              data:{},
              success:function(data){
                  $('.client_list').empty().html(data);
              },
              error:function(data)
              {
                  console.log(data);
              }
          });
 }
 $(document).on('keyup', '#chat_enter', function() {
      if($('#chat_enter').val()!='')
      {
        $("#send").removeClass("disable-click");
        
      }
      else
      {
        $("#send").addClass("disable-click");
      }
   });
 $(document).on('click','.click_case_no',function(){
    var case_no = $(this).data('case_no');
    var client_id = $(this).data('client_id');
    var description = $(this).data('description');
    var case_id = $(this).data('case_id');
    $.ajax({
        type:'get',
        url:'get_chat_list',
        data:{case_no:case_no,case_id:case_id},
        success:function(data){
            $('.chat_div').empty().html(data);
            scrollToBottom()
        },
        error:function(data)
        {
            console.log(data);
        }
    });
 });
$(document).on('click','.modal_add_participate',function(){
    $("#add_participate").modal("show");
    $(".contacts_err").html('');
    var client_id = $(this).data('client_id');
    $.ajax({
        type:'get',
        url:'get_contacts',
        data:{client_id:client_id},
        success:function(data){
            var contacts = '';
            $.each( data.contacts, function( i, val ) {
                contacts += '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input modal_contact_no" name="modal_contact_no" id="modal_contact_no'+i+'" value="'+val.contact+'"><label class="custom-control-label" for="modal_contact_no'+i+'">'+val.name+'('+val.contact+')</label></div>';
            });
            $('.contacts').empty().html(contacts);
        },
        error:function(data)
        {
            console.log(data);
        }
    });
 });
 $(document).on('click','.save_participate',function(){
    $(".contacts_err").html('');
    var phone_nos = [];
    var modal_mycases_id = $('.modal_mycases_id').val();
    var modal_client_id = $('.modal_client_id').val();
    $('.modal_contact_no').each(function(){
        if($(this).is(":checked")){
            phone_nos.push($(this).val());
        }
    });
    if(phone_nos.length == 0){
       $(".contacts_err").html('Please select Participate');
       return false;
    }
    $.ajax({
        type:'post',
        url:'add_participate',
        data:{
             case_id:modal_mycases_id,
             client_id:modal_client_id,
             phone_no:phone_nos
         },
        success:function(res){
            if (res.status == 'success') {
            $('#add_participate').modal('toggle');
            $('#alert').empty().html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +res.msg + '</span></div></div>').focus();
            }else {
                $('#add_participate').modal('toggle');
                $('#alert').empty().html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +res.msg + '</span></div></div>').focus();
            }
        },
        error:function(res)
        {
            console.log(res);
        }
    });
    
 });

 $(document).on('click','.modal_remove_participate',function(){
    $(".contacts_err").html('');
    $("#modal_remove_participate").modal("show");
    var client_id = $(this).data('client_id');
    var mycases_id = $(this).data('mycases_id');
    $.ajax({
        type:'get',
        url:'get_contacts',
        data:{client_id:client_id},
        success:function(data){
            var contacts = '';
            $.each(data.assign_cases,function( i, val ) {
                contacts += '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input modal_remove_asses_cases" name="modal_contact_no" id="modal_contact_no'+i+'" value="'+val.id+'"><label class="custom-control-label" for="modal_contact_no'+i+'">'+val.name+'('+val.phone_no+')</label></div>';
            });
            $('.remove_contacts').empty().html(contacts);
        },
        error:function(data)
        {
            console.log(data);
        }
    });
 });

 $(document).on('click','.remove_participate',function(){
    $(".contacts_err").html('');
    var assign_cases_ids = [];
    var modal_mycases_id = $('.modal_remove_mycases_id').val();
    var modal_client_id = $('.modal_remove_client_id').val();
    $('.modal_remove_asses_cases').each(function(){
        if($(this).is(":checked")){
            assign_cases_ids.push($(this).val());
        }
    });
    if(assign_cases_ids.length == 0){
       $(".contacts_err").html('Please select Participate');
       return false;
    }
    $.ajax({
        type:'post',
        url:'remove_participate',
        data:{
             case_id:modal_mycases_id,
             client_id:modal_client_id,
             assign_id:assign_cases_ids
         },
        success:function(res){
            if (res.status == 'success') {
            $('#modal_remove_participate').modal('toggle');
            $('#alert').empty().html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +res.msg + '</span></div></div>').focus();
            }else {
                $('#add_participate').modal('toggle');
                $('#alert').empty().html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +res.msg + '</span></div></div>').focus();
            }
        },
        error:function(res)
        {
            console.log(res);
        }
    });
    
 });

 $(document).on('click','.upload',function(){
     $(".file_err").html('');
    $("#modal_upload_document").modal("show");
 });

 $(document).on('click','.upload_document',function(e){
    e.preventDefault();
    $(".file_err").html('');
    var modal_mycases_id = $('.modal_upload_mycases_id').val();
    var modal_client_id = $('.modal_upload_client_id').val();
    var modal_description = $('.modal_upload_description').val();
    var fd = new FormData();
    if($('#file_name').val() == ''){
       $(".file_err").html('Please select a Document.');
       return false;
    }
    var files = $('#file_name')[0].files[0];
    fd.append('fileName',files);
    fd.append('case_id',modal_mycases_id);
    fd.append('description',modal_description);
    $.ajax({
        type:'post',
        url:'upload_mycases_doc',
        data:fd,
        contentType: false,
        cache: false,
        processData:false,
        success:function(res){
            if (res.status == 'success') {
            $('#modal_upload_document').modal('toggle');
            $('#alert').empty().html('<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +res.msg + '</span></div></div>').focus();
            }else {
                $('#modal_upload_document').modal('toggle');
                $('#alert').empty().html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +res.msg + '</span></div></div>').focus();
            }
        },
        error:function(res)
        {
            console.log(res);
        }
    });
 });
 $(document).on('click', '#send', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var chat=$('#chat_enter').val();
        var case_id=$(this).data('case_id');
        var client_id=$(this).data('client_id');
       
        $('#chat_enter').val('');
        $.ajax({
                type:'post',
                url:'send_chats',
                data:{chat:chat,case_id:case_id},
                success:function(data){
                    $('.chat_div').empty().html(data);
                    scrollToBottom()
                },
                error:function(data)
                {
                    console.log(data);
                }
            });
      });
    });
   
</script>

@endsection