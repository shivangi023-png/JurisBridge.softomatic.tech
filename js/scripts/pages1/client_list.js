$(document).ready(function () {
  $("#client").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Clients",
  });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  get_client("client", "active", "client", "Active");
  $(document).on("change", "#client", function () {
    $(".data_div").empty();
   
    var client_id = new Array();
    $("#client :selected").each(function () {
      client_id.push($(this).val());
    });
    $(".loader").css("display", "block");
    $.ajax({
      type: "post",
      url: "get_leads",
      datatype: "text",
      data: {
        status: "active",
        client_leads: "client",
        page: "client",
        selection_val: "Active",
        client_id: client_id,
      },

      success: function (data) {
        console.log(data);
        
        $(".loader").css("display", "none");
        $(".data_div").empty().html(data);
      },
      error: function (data) {
        
        $(".data_div").empty();
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
  $(document).on("click", ".assign_btn", function () {
    var staff_id = $(this).data("assign_id");
    var staff_name = $(this).data("assign_val");
    var page = "client";
    var client_id = new Array();
    $(".dt-checkboxes:checked").each(function () {
      client_id.push($(this).closest("tr").find(".clientID").val());
    });

    if (client_id == "") {
      $("#alert").animate(
        {
          scrollTop: $(window).scrollTop(0),
        },
        "slow"
      );
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-danger"></i><span>Checkbox is not selected!</span></div></div>'
      );
      $(".alert")
        .fadeTo(2000, 500)
        .slideUp(500, function () {
          $(".alert").slideUp(500);
        });
    } else {
      $(".loader").css("display", "block");
      $.ajax({
        type: "post",
        url: "assign_leads_to_staff",
        data: {
          staff_id: staff_id,
          client_id: client_id,
        },

        success: function (data) {
          var res = JSON.parse(data);

          if (res.status == "success") {
            $(".loader").css("display", "none");
            $("#alert").animate(
              {
                scrollTop: $(window).scrollTop(0),
              },
              "slow"
            );
            $("#alert").html(
              '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            );
            $(".alert")
              .fadeTo(2000, 500)
              .slideUp(500, function () {
                $(".alert").slideUp(500);
              });

            get_client_by_id(
              page,
              "client",
              client_id,
              "",
              "assign_leads_to_staff"
            );
          }
        },
        error: function (data) {
          console.log(data);
        },
      });
    }
  });
  $(document).on("click", ".lead_history", function () {
    var client_id = $(this).data("client_id");
    get_lead_history(client_id);
  });
  $(document).on("click", ".filter_btn", function () {
    $(".data_div").empty();
    var value = $(this).data("value");
    var client_leads = "client";
    var page = "client";
    var selection_val = $(this).data("selection_val");
    get_client(page, value, client_leads, selection_val);
  });

  $(document).on("click", ".delete_client", function () {
    var id = $(this).data("id");
    var selection_val = $(".filter_btn").data("selection_val");
    var page = "client";
    Swal.fire({
      title: "Are you sure?",
      text: "You want to delete this client",
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
          url: "delete_client",
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
                text: "Client has been deleted.",
                confirmButtonClass: "btn btn-success",
              });
              get_client(page, "active", "client", selection_val);
            } else {
              Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Client can`t be deleted.",
                confirmButtonClass: "btn btn-danger",
              });
            }
          },
        });
      }
    });
  });

  $(document).on("click", ".detailBtn", function () {
    var client_id = $(this).data("client_id");
    var detail = $(this).data("detail");
    get_quo_appo_foll(client_id, detail);
  });
  $(document).on("click", ".add_appointment_btn", function () {
    $(".appointment_client_name").val($(this).data("client_name"));
    $(".appointment_client_id").val($(this).data("id"));
  });
  $(document).on("click", "#save_appointment", function () {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $(".valid_err").html("");
    var client = $(".followup_client_id").val();
    var meeting_with = $(".meeting_with").val();
    var schedule_by = $(".schedule_by").val();
    var meeting_date = $(".meeting_date").val();
    var time = $(".time").val();
    var meeting_place = $(".meeting_place").val();

    var arr = [];

    if (client == "") {
      arr.push("client_err");
      arr.push("Please select client");
    }

    if (meeting_with == "") {
      arr.push("meeting_with_err");
      arr.push("Please select meeting with");
    }
    if (schedule_by == "") {
      arr.push("schedule_by_err");
      arr.push("Please select schedule by");
    }

    if (meeting_date == "") {
      arr.push("meeting_date_err");
      arr.push("Meeting date required");
    }
    if (time == "") {
      arr.push("time_err");
      arr.push("time required");
    }
    if (meeting_place == "") {
      arr.push("meeting_place_err");
      arr.push("Please select meeting place");
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
        url: "submit_appointment",

        data: {
          client: client,
          meeting_with: meeting_with,
          schedule_by: schedule_by,
          meeting_date: meeting_date,
          time: time,
          meeting_place: meeting_place,
        },

        success: function (data) {
          console.log(data);
          $("#appointmentModal").modal("hide");
          $("#alert").animate(
            {
              scrollTop: $(window).scrollTop(0),
            },
            "slow"
          );
          var res = JSON.parse(data);
          if (res.status == "success") {
            console.log("success");
            $("#alert").html(
              '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            );
            $("#form").trigger("reset");
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
          $(".alert")
            .fadeTo(2000, 500)
            .slideUp(500, function () {
              $(".alert").slideUp(500);
            });
        },
      });
    }
  });
});

function get_client_by_id(
  page,
  client_leads,
  client_id,
  mythis,
  calling_function
) {
  if ($.isArray(client_id)) {
    client = client_id;
  } else {
    var client = [];
    client.push(client_id);
  }

  $.ajax({
    type: "post",
    url: "get_leads",
    dataType: "json",
    data: {
      client_leads: client_leads,
      page: page,
      client_id: client,
    },

    success: function (data) {
      console.log(data);
      if (data.status == "success") {
        if (calling_function == "assign_leads_to_staff") {
          $(".loader").css("display", "none");

          if (data.response.length > 0) {
            var assign_staff_name = data.response[0]["assign_staff_name"];
            var assigned_at = data.response[0]["assigned_at"];
            console.log(assign_staff_name);
            $(".dt-checkboxes-select-all input").prop("checked", false);
            $(".dt-checkboxes:checked").each(function () {
              $(this).prop("checked", false);
              $(this)
                .closest("tr")
                .find(".assigned_at")
                .empty()
                .text(assigned_at);
              $(this)
                .closest("tr")
                .find(".assign_staff")
                .empty()
                .text(assign_staff_name);
              $(this).closest("tr").removeClass("selected-row-bg");
            });
          }
        }
      } else {
        $(".loader").css("display", "none");
        $("#alert")
          .html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span></span></div>Something went wrong while get lead type</div>'
          )
          .focus();
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

function get_client(page, status, client_leads, selection_val) {
  $(".data_div").empty().html("");
  $(".loader").css("display", "block");
  $.ajax({
    type: "post",
    url: "get_leads",
    datatype: "text/html",
    data: {
      client_leads: client_leads,
      page: page,
      status: status,
      selection_val: selection_val,
    },

    success: function (data) {
      console.log(data);
      if (data) {
        $(".data_div").empty().html(data);
        $(".loader").delay(1000).fadeOut();
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
