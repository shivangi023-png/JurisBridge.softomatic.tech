
<div class="chat_section">
      <div class="settings-tray">
          <div class="friend-drawer no-gutters friend-drawer--grey">
          <div class="text">
            <h6 id="case_no">{{$mycases->case_no}}</h6>
            <p class="text-muted" id="service_name">{{$mycases->description}}</p>
          </div>
         <span class="settings-tray--right">
            <!-- <i class="material-icons" title="Remove Participant">delete_forever</i> -->
            <!-- <i class="material-icons" title="Add Participant" data-toggle="modal" data-target="#add_participate" ata-tooltip="Add Participant">person_add</i>  -->
            <img src="{{asset('images/chat/add_member.svg')}}" class="modal_add_participate" title="Add Participate"  data-tooltip="Add Participate" data-client_id="{{$mycases->client_id}}" data-mycases_id="{{$mycases->id}}">
            <img src="{{asset('images/chat/remove_member.svg')}}" class="modal_remove_participate" title="Remove Participate" data-tooltip="Remove Participate" data-client_id="{{$mycases->client_id}}" data-mycases_id="{{$mycases->id}}">
          </span> 
        </div>
      </div>
      <div class="chat-panel">

        <!-- chat_list show -->
        <div class="msg" id="messages">
         
        <?php 
        if($out!='')
        echo $out; ?>
      </div>
    </div>
    <!-- chat_list show -->
  <div class="row">
    <div class="col-12">
      <div class="chat-box-tray" >
        <!-- <i class="material-icons">sentiment_very_satisfied</i> -->
        <i class="material-icons upload">attach_file</i>
        <input type="hidden" id="case_id" value="{{$mycases->id}}" placeholder="Type your message here...">
        <input type="text" id="chat_enter" placeholder="Type your message here...">
              <a type="button" class="disable-click" id="send"  data-case_id="{{$mycases->id}}" data-client_id="{{$mycases->client_id}}"><i class="material-icons">send</i></a>
      </div>
    </div>
  </div>
      </div>
    </div>

 <div class="modal fade text-left" id="add_participate" tabindex="-1" role="dialog" aria-labelledby="modal_title
modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Participate</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
      <!-- Modal body -->
      <div class="modal-body">
      <!-- <form> -->
        <input type="hidden" value="{{$mycases->client_id}}" class="modal_client_id">
        <input type="hidden" value="{{$mycases->id}}" class="modal_mycases_id">
        <span class="contacts_err text-danger"></span>
        <div class="contacts">
                 
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success save_participate">Add</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
        </div>
    </div>
</div>

 <div class="modal fade text-left" id="modal_remove_participate" tabindex="-1" role="dialog" aria-labelledby="modal_title
modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Remove Participate</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
      <!-- Modal body -->
      <div class="modal-body">
      <!-- <form> -->
        <input type="hidden" value="{{$mycases->client_id}}" class="modal_remove_client_id">
        <input type="hidden" value="{{$mycases->id}}" class="modal_remove_mycases_id">
        <span class="contacts_err text-danger"></span>
        <div class="remove_contacts">
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success remove_participate">Remove</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modal_upload_document" tabindex="-1" role="dialog" aria-labelledby="modal_title
modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Upload Document</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
      <!-- Modal body -->
      <div class="modal-body">
      <!-- <form> -->
        <input type="hidden" value="{{$mycases->client_id}}" class="modal_upload_client_id">
        <input type="hidden" value="{{$mycases->id}}" class="modal_upload_mycases_id">
        <input type="hidden" value="{{$mycases->description}}" class="modal_upload_description">
        <span class="file_err text-danger"></span>
        <div class="form-label-group">
          <div class="custom-file">
              <input type="file" class="custom-file-input" id="file_name">
              <span class="custom-file-label" for="expense_file">Upload Document</span>
          </div>
       </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success upload_document">Upload</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
        </div>
    </div>
</div>
<script>
function scrollToBottom() {
    const scrollContainer = document.getElementById('messages');
    scrollContainer.scrollTo({
        top: scrollContainer.scrollHeight,
        left: 0,
        behavior: 'smooth'
    });
}
$(document).ready(function(){
  window.setInterval(loadmsg, 10000);
  
  
 function loadmsg(){
  
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } });
  
  var case_id=$('#case_id').val();
  var date=$('.date').val();
  var doc_size=$('.doc_size').val();
  console.log(doc_size);
  $.ajax({
   
   type:'post',
   url:'get_next_msg',
   data:{doc_size:doc_size,case_id:case_id,date:date},
   success:function(data){
        console.log(data);
       if(data!='')
       {
           $('.doc_div').html('');
           
           $('#messages').append(data);
           scrollToBottom()
       }
      

   },
   error:function(data)
   {
       console.log(data);
   }
});
 }
});

</script>


