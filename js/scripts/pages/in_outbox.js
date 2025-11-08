$.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

function get_inbox() {
   $.ajax({
      type: "post",
      url: "task_comment_inbox",
      datatype: "text/html",
      data: {
        request_from:'web',
        
      },
  
      success: function (data) {
        console.log(data);
        var res=data;
        if (res.status=='success') {
          $(".inoutbox").empty().html(res.body);
         
        }
      },
      error: function (data) {
        $(".loader").css("display", "none");
       console.log(data);
      },
    });
  }
function get_outbox() {
    $.ajax({
       type: "post",
       url: "task_comment_outbox",
       datatype: "text/html",
       data: {
         request_from:'web',
         
       },
   
       success: function (data) {
         console.log(data);
         var res=data;
         if (res.status=='success') {
           $(".inoutbox").empty().html(res.body);
          
         }
       },
       error: function (data) {
         $(".loader").css("display", "none");
        console.log(data);
       },
     });
   }