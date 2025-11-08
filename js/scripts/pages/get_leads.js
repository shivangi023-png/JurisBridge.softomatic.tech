function get_leads(page, client_leads) {
  $(".data_div").empty();
  $(".loader").css("display", "block");
  $.ajax({
    type: "post",
    url: "get_leads",
    datatype: "text/html",
    data: {
      client_leads: client_leads,
      page: page,
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

function get_statistics(page, status, client_leads, selection_val) {
  $(".data_div").empty();
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

function get_leads_by_id(
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

        if (calling_function == "save_lead_type") {
          mythis.closest("tr").find(".save_lead_div").css("display", "none");
          mythis
            .closest("tr")
            .find(".change_lead_type")
            .css("display", "block");
          mythis.closest("tr").find(".select_lead_type").css("display", "none");
          mythis.closest("tr").find(".lead_type_view").css("display", "block");

          var type = data.response[0]["type"];
          var html="";
          if (type == "New") {
             html =
              "<span class='badge badge-light-success badge-pill'>" +
              type +
              "</span>";
          }
          if (type == "Cold") {
             html =
              "<span class='badge badge-light-danger badge-pill'>" +
              type +
              "</span>";
          }
          if (type == "Potential") {
             html =
              "<span class='badge badge-light-primary badge-pill'>" +
              type +
              "</span>";
          }
          if (type == "Hot") {
             html =
              "<span class='badge badge-light-warning badge-pill'>" +
              type +
              "</span>";
          }
          if (type == "Closed") {
             html =
              "<span class='badge badge-light-danger badge-pill'>" +
              type +
              "</span>";
          }
          if (type == "Reopen") {
             html =
              "<span class='badge badge-light-brown badge-pill'>" +
              type +
              "</span>";
          }
          if (type == "Not Interested") {
             html =
              "<span class='badge badge-light-secondary badge-pill'>" +
              type +
              "</span>";
          }
          if (type == "NCT") {
             html =
              "<span class='badge badge-light-gray badge-pill'>" +
              type +
              "</span>";
          }

          mythis.closest("tr").find(".lead_type_view").empty().html(html);
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

function get_lead_history(client_id) {
  $(".detailModal-body").html("");
  $.ajax({
    type: "get",
    url: "lead_history",
    data: {
      client_id: client_id,
    },

    success: function (data) {
      console.log(data);
      $("#detailModal").modal("toggle");
      $(".detailModal-body").html(data);
    },
    error: function (data) {
      var res = data;
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

function get_daily_leads_by_sales(staff_id, date) {
  $(".data_div").empty();
  $(".loader").css("display", "block");
  $.ajax({
    type: "get",
    url: "get_daily_leads_by_sales",
    data: {
      staff_id: staff_id,
      date: date,
    },

    success: function (data) {
      console.log(data);
      $(".loader").css("display", "none");
      $(".data_div").empty().html(data);
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

function get_client_ledger() {
  $(".data_div").empty();
  $(".loader").css("display", "block");
  $.ajax({
    type: "get",
    url: "get_client_ledger",
    datatype: "text",

    success: function (data) {
      console.log(data);
      $(".loader").css("display", "none");
      $(".data_div").empty().html(data);
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

function get_quo_appo_foll(client_id, detail) {
  $(".loader1").css("display", "block");
  $.ajax({
    type: "post",
    url: "get_quo_appo_foll",
    data: {
      client_id: client_id,
      detail: detail,
    },

    success: function (data) {
      $(".loader1").css("display", "none");
      console.log(data);
      $(".detailModal-title").empty().html(detail);
      $(".detailModal-body").empty().html(data);
    },
    error: function (data) {
      $(".loader1").css("display", "none");
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
