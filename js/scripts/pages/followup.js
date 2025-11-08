function get_followup(page) {
  $(".loader").css("display", "block");
  $.ajax({
    type: "post",
    url: "get_follow_up_details",
    datatype: "text/html",
    data: {
      page: page,
    },

    success: function (data) {
      console.log(data);
      if (data) {
        $(".data_div").empty().html(data);
        $(".loader").css("display", "none");
      }
    },
    error: function (data) {
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
}
$(document).on('click','.whatsapp_follow',function(){
  var client_id=$(this).data('client_id');
    $.ajax({
    type: "post",
    url: "get_whatsapp_no",
    datatype: "text/html",
    data: {
      client_id: client_id,
    },

    success: function (res) {
      console.log(res);
      if (res) {
          var val=JSON.parse(res);
          $('.whatsapp_body').html(val.data);
          $("#whatsapp_modal").modal("toggle");
        $(".loader").css("display", "none");
      }
    },
    error: function (res) {
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
});
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
  $(".staff").select2();

  $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
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

  $.ajax({
    type: "get",
    url: "autocomplete_followup_disc",

    success: function (data) {
      $(".discussion").autocomplete({
        source: data,
      });
    },
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

  $(document).on("click", ".saveFollowupBtn", function () {
    $("#client_name").html("(" + $(this).data("client_name") + ")");
    $(".client_id").empty().val($(this).data("client_id"));
    var id = $(this).data("client_id");
    $.ajax({
      type: "post",
      url: "get_contacts_followup",

      data: {
        id: id,
      },

      success: function (data) {
        console.log(data);
        $(".contact_div").empty().html(data);
      },
      error: function (data) {
        console.log(data);
      },
    });
  });

  $(document).on("click", "#save_follow_up_btn", function () {
    var status = $(".selection").html();
    var client_id = $(".client_id").val();

    var followup_date = $(".followup_date").val();
    var next_followup_date = $(".next_followup_date").val();
    var contact_by = $(".contact_by").val();

    var company = $(".company").val();
    var discussion = $(".discussion").val();
    var next_radio = $("#next_radio").val();

    var arr = [];
    if ($("#finalized_radio").prop("checked") == true) {
      var finalized = "yes";
    } else {
      var finalized = "no";
    }
    if ($("#leadclosed_radio").prop("checked") == true) {
      var lead_closed = "yes";
    } else {
      var lead_closed = "no";
    }
    if ($("#next_radio").prop("checked") == true) {
      if (next_radio == "next_follow_up_date") {
        if (next_followup_date == "") {
          arr.push("next_date_err");
          arr.push("Next Follow-up date required");
        } else {
          var next_date = next_followup_date;
        }
      }
    } else {
      var next_date = "";
    }

    var method = $("#method").val();

   

    var contact_to ='';
            $.each($(".contact_to:checked"), function() {
                contact_to=$(this).val();
            });

    if (followup_date == "") {
      arr.push("followup_date_err");
      arr.push("Follow-up date required");
    }
    if (company == "") {
      arr.push("company_err");
      arr.push("company required");
    }

    if (contact_by == "") {
      arr.push("contact_by_err");
      arr.push("Please select contact by");
    }
    if (contact_to == "") {
      arr.push("contact_to_err");
      arr.push("Please check any contact person name");
    }
    if (method == "") {
      arr.push("method_err");
      arr.push("Please select ant method");
    }
    if (discussion == "") {
      arr.push("discussion_err");
      arr.push("Discussion required");
    }
    if (arr != "") {
      for (var i = 0; i < arr.length; i++) {
        var j = i + 1;

        $("." + arr[i])
          .html(arr[j])
          .css("color", "red");

        i = j;
      }
    } else {
      $.ajax({
        type: "post",
        url: "save_follow_up",
        data: {
          client_id: client_id,
          followup_date: followup_date,
          contact_by: contact_by,
          next_followup_date: next_date,
          contact_to: contact_to,
          method: method,
          finalized: finalized,
          lead_closed: lead_closed,
          discussion: discussion,
          company: company,
        },
        success: function (data) {
          console.log(data);
          var res = data;
          $("#alert").animate(
            {
              scrollTop: $(window).scrollTop(0),
            },
            "slow"
          );
          $("#saveFollowup").modal("hide");
          if (res.status == "success") {
            $("#alert").html(
              '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            );
            let str = status;

            $(".selection").html(str.toUpperCase());
          } else {
            $("#alert").html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                res.msg +
                "</span></div></div>"
            );
          }
        },
        error: function (data) {
          console.log(data);
          $("#alert").animate(
            {
              scrollTop: $(window).scrollTop(0),
            },
            "slow"
          );
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something wend wrong!</span></div></div>'
          );
        },
      });
    }
  });

  $(document).on("click", ".radio_btn", function () {
    if ($(".radio_btn").is(":checked")) {
      if ($(this).val() == "next_follow_up_date") {
        $(".next_date_div").css("display", "block");
      } else {
        $(".next_date_div").css("display", "none");
      }
    }
  });

  $(document).on("click", ".delete_follow_up", function () {
    var mythis = $(this);
    var id = $(this).data("client_id");

    Swal.fire({
      title: "Are you sure?",
      text: "You want to delete this follow up",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
      confirmButtonClass: "btn btn-warning",
      cancelButtonClass: "btn btn-danger ml-1",
      buttonsStyling: false,
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          type: "post",
          url: "delete_follow_up",
          data: {
            id: id,
          },

          success: function (data) {
            console.log(data);
            var res = JSON.parse(data);
            if (res.status == "success") {
              Swal.fire({
                icon: "success",
                title: "Deleted!",
                text: res.msg,
                confirmButtonClass: "btn btn-success",
              });
              mythis.closest("tr").remove();
            } else {
              Swal.fire({
                icon: "error",
                title: "Error!",
                text: res.msg,
                confirmButtonClass: "btn btn-danger",
              });
            }
          },
          error: function (data) {
            console.log(data);
            Swal.fire({
              icon: "error",
              title: "Error!",
              text: res.msg,
              confirmButtonClass: "btn btn-danger",
            });
          },
        });
      }
    });
  });

  $(document).on("click", ".active_btn", function () {
    var value = $(this).data("value");
    var staff = $(".staff").val();
    $(".loader").css("display", "block");
    var value = $(this).data("value");
    $.ajax({
      type: "post",
      url: "filter_on_followupdate",
      data: {
        value: value,
        staff: staff,
      },

      success: function (data) {
        console.log(data);
        $(".loader").css("display", "none");

        $(".data_div").empty().html(data);
        let str = value;

        $(".selection").html(str.toUpperCase());
        if ($(".client-data-table").length) {
          var dataListView = $(".client-data-table").DataTable({
            columnDefs: [
              {
                targets: 0,
                className: "control",
              },
              {
                // orderable: true,
                // targets: 0,
                // checkboxes: { selectRow: true }
              },
              {
                targets: [0, 1],
                orderable: false,
              },
            ],

            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
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

        // To append actions dropdown inside action-btn div
        var clientFilterAction = $(".client-filter-action");
        var clientOptions = $(".client-options");
        var staffFilter = $(".staff_filter");
        $(".action-btns").append(
          staffFilter,
          clientFilterAction,
          clientOptions
        );
        $(".staff").select2({
          dropdownAutoWidth: true,
          width: "100%",
          placeholder: "Search staff wise follow-up",
        });
      },
      error: function (data) {
        console.log(data);
      },
    });
  });
});
