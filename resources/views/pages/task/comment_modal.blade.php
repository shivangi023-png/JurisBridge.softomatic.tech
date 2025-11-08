<style>
  #suggestionPopupComment {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 70%;
  /*max-height: 150px;
    overflow-y: auto;
    z-index: 1000000;
    position: absolute;
    bottom: -150px;
    top: -142.8px;
    left: 14.75px;
    */
    display: none;
    padding:12px;
    list-style-type: none;
    cursor: pointer;
    }
    .mentionedComment
    {
      list-style-type: none;
    }
    .mentionedComment {
        color: blue; /* Change the color as desired */
    }
    #model_myDiv:empty::before {
      content: "Write Comments..."; 
      color: gray; 
      opacity: 0.5; 
   }
</style>
<div class="modal fade" id="commentBox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header ModalHeader">
        <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <div class="modal-body ModalBody">

          <div class="TaskModalBox">
            <div class="row">
              <!-- task_id -->
              <input type="hidden" id="commentTaskId">
              <div class="col-sm-9 ProjectName">
              
              </div>
              <div class="col-sm-3 ProjectType type">
               
              </div>
            </div>
            <div class="row CMCBox">
              <div class="col-sm-12 CMContent">
                <h4 class="TaskTitle"></h4>
                <p class="TaskDesc"></p>
              </div>
            </div>
            <div class="row DateDurationBox">
              <div class="col-sm-7 TMAssign">
               
              </div>
              <div class="col-sm-5 DateStartEnd">
                
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="PriorityStatusBox">
                  <a class="PriorityBtn" href="#">Low</a>
                  <a class="StatusBtn" href="#">Completed</a>
                </div>  
              </div>
            </div>
          </div>
          <!---------------------------------comment----------------------------->
          <div class="CommentDiv">

          </div>
          <!---------------------------------end comment----------------------------->
   

        </div>

        <div class="footer-bot">
           <div id="suggestionPopupComment"></div>
          <div class="searchbox-wrap">
            <div class="model_task_comment" id="model_myDiv" contenteditable="true"></div> 
            <button id="btn_comment_save"><i class="bx bx-send"></i></button>
          </div>
        </div>

    </div>
  </div>
</div>
<script>
  
    
</script>