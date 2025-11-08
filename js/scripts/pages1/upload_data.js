$(document).ready(function() {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
  $(document).on("click", "#search", function() {
      $(".data_div").empty();
      var consumer_name=$('.consumer_name').val();
      var mobile_no=$('.mobile_no').val();
      var address=$('.address').val();
   
      var file_name=$('.file_name').val();

      if (consumer_name!='' || mobile_no!='' || address!='' || file_name!='') {
        
         
          $.ajax({
              type: "post",
              url: "search_lead_data",
              datatype: "text/html",
              data: {
                  consumer_name:consumer_name,
                  mobile_no:mobile_no,
                  address:address,
                  file_name:file_name,
              },
              beforeSend: function(){
			
                $("#cover-spin").show();
              
                },
                complete: function(){
                $("#cover-spin").hide();
                },
              success: function(data) {
                  console.log(data);
                 
                  $(".data_div").empty().html(data);
                  if ($(".client-data-table").length) {
                    console.log('data');
                    var dataListView = $(".client-data-table").DataTable({
                        iDisplayLength: 10,
                        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        language: {
                            search: "",
                            searchPlaceholder: "Search Follow-up",
                        },

                        select: {
                            style: "multi",
                            selector: "td:first-child",
                            items: "row",
                        },
                        responsive: {
                            details: {
                                type: "column",
                                target: 0,
                            },
                        },
                    });
                }
                
              },
              error: function(data) {
                  console.log(data);


                  var res=JSON.parse(data);
                  $(".loader").css("display", "none");
                  $("#alert")
                      .html(
                          '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                          res.msg +
                          "</span></div></div>"
                      )
                      .focus();
              },
          });

      } else {
       
         
              $("#alert").animate({
                      scrollTop: $(window).scrollTop(0),
                  },
                  "slow"
              );
              $("#alert")
                  .html(
                      '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please select any filter</span></div></div>'
                  )
                  .focus();
              $(".alert")
                  .fadeTo(2000, 500)
                  .slideUp(500, function() {
                      $(".alert").slideUp(500);
                  });
              return false;
          
      }
  });
  $('#reset').click(function() {
      location.reload();
  });
});