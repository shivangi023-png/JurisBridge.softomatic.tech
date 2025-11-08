var mentioned_StaffIds = [];
$('#btn_comment_save').hide();
$(".model_task_comment").on("keyup", function (e) {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  var content1 = $(this).html();
  if(content1 != ''){
   $('#btn_comment_save').show();
  }else{
   $('#btn_comment_save').hide();
  }
  var matches1 = content1.match(/@([^\s@]+)$/);
  if (matches1) {
    // Fetch suggestions
    var searchTerm1 = matches1[1];
    var offset = $(this).offset();
    var topOffset = offset.top - $("#suggestionPopupComment").outerHeight() - 10;
    var leftOffset = offset.left;
    console.log(searchTerm1);
    $.ajax({
      url: "get_mention_assignee",
      type: "POST",
      data: { searchTerm: searchTerm1 },
      success: function (response) {
        $("#suggestionPopupComment").html(response).show();
      },
    });
  } else {
    $("#suggestionPopupComment").hide();
  }
});

$(document).on("click", "#suggestionPopupComment li", function () {
  var username2 = $(this).text();
  var id2 = $(this).attr("id");
  var content2 = $(".model_task_comment").html();
  var matches2 = content2.match(/@([^\s@]+)$/);
  if (matches2) {
    var beforeText2 = content2.substring(0, content2.length - matches2[0].length);
    var newText2 =
      beforeText2 +
      '<span class="mentionedComment" data-id="' +
      id2 +
      '">@' +
      username2 +
      "</span>,";
    $(".model_task_comment").html(newText2).focus();
    $("#suggestionPopupComment").hide();
    // Save mentioned staff ID
    mentioned_StaffIds.push(id2);
  }
});

$(document).on("click", "#btn_comment_save", function () {
  console.log('task Comment popup save');
  var commentTaskId = $('#commentTaskId').val();
  var comments_popup_text = $(".model_task_comment").text();
  // console.log(mentioned_StaffIds);
  // console.log(commentTaskId);
  // console.log(comments_popup_text);
    if (comments_popup_text != "") {
        $.ajaxSetup({
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
        });
        $.ajax({
          type: "post",
          url: "save_task_comment",
          data: {
            task_id: commentTaskId,
            tag_staff_id: mentioned_StaffIds,
            comment: comments_popup_text,
            request_from: "web"
          },
          success: function (comment_data) {
            console.log(comment_data);
            //clear 
            mentioned_StaffIds = [];
            $('#btn_comment_save').hide();
            $(".model_task_comment").html('');
            get_task_comment(commentTaskId);
            $('.ModalBody').animate({ scrollTop: $('.ModalBody').prop("scrollHeight")}, 'slow');
          },
          error: function (comment_data) {
            console.log(comment_data);
          },
        });
    }
});