$(document).ready(function () {
    $("#client").select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Select Clients",
    });
  
    $("#contact_by").select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Contact By",
    });
  
    if ($(".pickadate").length) {
      $(".pickadate").pickadate({
        format: "dd/mm/yyyy",
        onStart: function () {
          this.set({ select: new Date() });
        },
      });
    }
  
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
  
    $(".loader").css("display", "block");
    $.ajax({
      type: "post",
      url: "get_mynextfollow_up_details",
      success: function (data) {
        console.log(data);
        $(".loader").css("display", "none");
        $(".data_div").empty().html(data);
  
        var dataListView = $(".client-data-table").DataTable({
          sorting: false,
          dom:'<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
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
  
        // To append actions dropdown inside action-btn div
        var clientFilterAction = $(".client-filter-action");
        var clientOptions = $(".client-options");
        var staffFilter=$('.staff_filter');
        $(".action-btns").append(staffFilter,clientFilterAction, clientOptions);
        $(".staff").select2({
       
          dropdownAutoWidth: true,
          width: '100%',
          placeholder: "Search staff wise follow-up"
        });
      },
      error: function (data) {
        console.log(data);
      },
    });
  });
  
  $(document).on("click", ".followup_call_detail_btn", function () {
    var client_id = $(this).data("client_id");
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $.ajax({
      type: "post",
      url: "get_followup_call_detail",
      data: {
        client_id: client_id,
      },
  
      success: function (data) {
        console.log(data);
        $(".call_detail_body").empty().html(data);
      },
      error: function (data) {
        console.log(data);
      },
    });
  });
  