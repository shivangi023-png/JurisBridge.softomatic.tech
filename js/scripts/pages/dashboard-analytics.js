/*=========================================================================================
    File Name: dashboard-analytics.js
    Description: dashboard analytics page content with Apexchart Examples
    ----------------------------------------------------------------------------------------
    Item Name: Frest HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(window).on("load", function () {
  $(document).on("click", ".app_rej_btn", function () {
    var value = $(this).val();
    var id = $(this).data("id");

    $.ajax({
      type: "post",
      url: "approve_reject_leave",
      data: {
        value: value,
        id: id,
      },

      success: function (data) {
        $("#alert").animate(
          {
            scrollTop: $(window).scrollTop(0),
          },
          "slow"
        );
        if (data.status == "success") {
          $(".leave_div").empty().html(data.out);
          var table = $(".mytable").DataTable({});
          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              data.msg +
              "</span></div></div>"
          );
        } else {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
              data.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        $("#alert").html(
          '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
            data.msg +
            "</span></div></div>"
        );
      },
    });
  });
});

$(document).ready(function () {
  $("#staff_id").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Staff",
  });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  filter_today_attendance();
  filter_today_client();
  filter_today_followup();
  filter_today_nextfollowup();
  filter_today_appointment();
  filter_today_leave();
  filter_today_quotation();
  filter_today_invoice();
  filter_today_payment();
  filter_today_sales_lead();
  filter_today_office_lead();
  filter_leads_table();
  raise_attendance();
  office_visit_table();
assign_client_due();
daily_report_table();
  $(document).on("click", ".detailBtn", function () {
    var client_id = $(".clientID").val();
    var detail = $(this).data("detail");

    get_quo_appo_foll(client_id, detail);
  });

  $(document).on("click", ".dropdown-item", function () {
    $(this)
      .closest("ul")
      .find("span.cursor-pointer")
      .html($(this).data("display"));
  });

  // $(document).on("click", ".search_client", function () {
  //   if ($(this).is(":checked")) {
  //     var value = $(this).val();
  //   }
  //   $(".client_info_loading").css("display", "block");
  //   $.ajax({
  //     type: "get",
  //     url: "search_client",
  //     data: {
  //       value: value,
  //     },

  //     success: function (data) {
  //       $(".client_info_loading").css("display", "none");

  //       $(".client_info").val("");
  //       $(".client_info").autocomplete({
  //         source: data,
  //       });
  //     },
  //   });
  // });

  $(".client_info").autocomplete({
    source: function (request, response) {
      if ($(".search_client").is(":checked")) {
        var type = $(".search_client:checked").val();
      }
      $.ajax({
        type: "get",
        url: "search_client",

        data: {
          value: request.term,
          type: type,
        },
        success: function (data) {
          console.log(data);
          response(data);
        },
      });
    },
    minLength: 3,
    select: function (event, ui) {
      var value = ui.item.id;
      if ($(".search_client").is(":checked")) {
        var type = $(".search_client:checked").val();
      }
      $(".client_info_loading").css("display", "block");
      $.ajax({
        type: "get",
        url: "search_exist_client",
        data: {
          value: value,
          type: type,
        },

        success: function (data) {
          console.log(data);
          $(".client_info_loading").css("display", "none");
          jQuery.each(data, function (i, val) {
            type = val.lead_type_name;
            var cases_href = "get_cases-" + val.client_id;
            console.log(cases_href);
            if (val.lead_change == "yes") {
              if (type == "New") {
                var html =
                  "<a href='javascript:void(0);' class='change_lead_type badge badge-light-success badge-pill'>" +
                  type +
                  "</a>";
              }
              if (type == "Cold") {
                var html =
                  "<a href='javascript:void(0);' class='change_lead_type badge badge-light-danger badge-pill'>" +
                  type +
                  "</a>";
              }
              if (type == "Potential") {
                var html =
                  "<a href='javascript:void(0);' class='change_lead_type badge badge-light-primary badge-pill'>" +
                  type +
                  "</a>";
              }
              if (type == "Hot") {
                var html =
                  "<a href='javascript:void(0);' class='change_lead_type badge badge-light-warning badge-pill'>" +
                  type +
                  "</a>";
              }
              if (type == "Closed") {
                var html =
                  "<a href='javascript:void(0);' class='change_lead_type badge badge-light-danger badge-pill'>" +
                  type +
                  "</a>";
              }
              if (type == "Reopen") {
                var html =
                  "<a href='javascript:void(0);' class='change_lead_type badge badge-light-brown badge-pill'>" +
                  type +
                  "</a>";
              }
              if (type == "Not Interested") {
                var html =
                  "<a href='javascript:void(0);' class='change_lead_type badge badge-light-secondary badge-pill'>" +
                  type +
                  "</a>";
              }
               if (type == "NCT") {
              var html =
                "<a href='javascript:void(0);' class='change_lead_type badge badge-light-gray badge-pill'>" + 
                type +
                "</a>";
            }

              $(".lead_type_span").empty().html(html);
            } else {
              if (type == "New") {
                var html =
                  "<span class='badge badge-light-success badge-pill'>" +
                  type +
                  "</span>";
              }
              if (type == "Cold") {
                var html =
                  "<span class='badge badge-light-danger badge-pill'>" +
                  type +
                  "</span>";
              }
              if (type == "Potential") {
                var html =
                  "<span class='badge badge-light-primary badge-pill'>" +
                  type +
                  "</span>";
              }
              if (type == "Hot") {
                var html =
                  "<span class='badge badge-light-warning badge-pill'>" +
                  type +
                  "</span>";
              }
              if (type == "Closed") {
                var html =
                  "<span class='badge badge-light-danger badge-pill'>" +
                  type +
                  "</span>";
              }
              if (type == "Reopen") {
                var html =
                  "<span class='badge badge-light-brown badge-pill'>" +
                  type +
                  "</span>";
              }
              if (type == "Not Interested") {
                var html =
                  "<span class='badge badge-light-secondary badge-pill'>" +
                  type +
                  "</span>";
              }
              if (type == "NCT") {
                var html =
                  "<span class='badge badge-light-gray badge-pill'>" +
                  type +
                  "</span>";
              }
              $(".lead_type_span").empty().html(html);
            }
            $(".client_detail_div").css("display", "block");
            var href1 = "follow-up-add-" + val.client_id;
            var href2 = "client_edit-" + val.client_id;
            $(".follow_up_add").attr("href", href1);
            $(".property_type_span").text(val.type_name);
            $(".address_span").text(val.address);
            $(".city_span").text(val.city_name);
            $(".client_name_span").text(val.client_name);
            $("#select_lead").val(val.lead_type);
            $(".clientID").val(val.client_id);
            $(".case_no_span").text(val.case_no);
            $("#case_no_href").attr("href", href2);
            $(".area_span").text(val.area);
            $(".unit_span").text(val.no_of_units);
            $(".source_span").text(val.source_name);
            $(".assign_name_span").text(val.assign_name);
            $(".assigned_date_span").text(val.assigned_date);
            $(".appointments_span").text(val.appointments);
            $(".followups_span").text(val.followups);
            $(".quotations_span").text(val.quotations);
            $(".cases_span").text(val.cases);
            $(".cases_span").attr("href", cases_href);
            var contacts = val.contacts;
            get_contact_details(val.client_id);
            $(".clientID").val(val.client_id);
            $(".client_info_div").css("display", "block");
          });
        },
      });
    },
  }).data("ui-autocomplete")._renderItem = function(ul, item) {
    if(item.address!="")
    {
       return $("<li>")
        .append("<div>" + item.value + "<br><small style='color:gray'>(" + item.address + ")</small></div>")
        .appendTo(ul);
    }
    else
    {
      return $("<li>")
        .append("<div>" + item.value +"</div>")
        .appendTo(ul);
    }
   
};

  $(".client_query").autocomplete({
    source: function (request, response) {
      $.ajax({
        type: "get",
        url: "search_client_by_name",
        data: {
          term: request.term,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    minLength: 2,
    select: function (event, ui) {
      console.log(ui.item);
      var url = "leads_timeline/" + ui.item.id;
      window.open(url, "_blank");
    },
  });
});

function get_contact_details(id) {
  $.ajax({
    type: "post",
    url: "get_contact_details",
    data: {
      id: id,
    },

    success: function (data) {
      if (data) {
        $(".client_contacts").empty().html(data);
      }
    },
    error: function (data) {},
  });
}
$(document).on("click", ".appointment_add", function (e) {
  e.preventDefault();
  var client_id = $("#clientID").val();
  var url = "appointment-add?id=" + client_id;
  window.open(url, "_blank");
});

$(document).on("click", ".follow_up_add", function (e) {
  e.preventDefault();
  var client_id = $("#clientID").val();
  var url = "follow-up-add?id=" + client_id;
  window.open(url, "_blank");
});

$(document).on("click", ".quotation_add", function (e) {
  e.preventDefault();
  var client_id = $("#clientID").val();
  var url = "quotation_add?id=" + client_id;
  window.open(url, "_blank");
});
$(document).on("click", ".invoice_add", function (e) {
  e.preventDefault();
  var client_id = $("#clientID").val();
  var url = "invoice_add?id=" + client_id;
  window.open(url, "_blank");
});
$(document).on("click", ".expenses_add", function (e) {
  e.preventDefault();
  var client_id = $("#clientID").val();
  var url = "expenses_add?id=" + client_id;
  window.open(url, "_blank");
});
$(document).on("click", ".change_lead_type", function () {
  $(this).hide();
  $("#select_lead").show();
  $("#save_lead").show();
  $("#cancel_lead").show();
});

$(document).on("click", "#cancel_lead", function () {
  $(this).hide();
  $("#select_lead").hide();
  $("#save_lead").hide();
  $(".change_lead_type").show();
});

$(document).on("click", "#save_lead", function () {
  var lead_type = $("#select_lead").val();
  var client_id = $(".clientID").val();

  $.ajax({
    type: "post",
    url: "save_lead_type",
    data: {
      lead_type: lead_type,
      client_id: client_id,
    },

    success: function (data) {
      var res = JSON.parse(data);

      if (res.status == "success") {
        $("#type_err").hide();
        $("#type_err").html("");
        get_leads_details(client_id);
      }
    },
    error: function (data) {
      $("#type_err").show();
      $("#type_err").html(res.msg);
    },
  });
});
$(".dataTable").DataTable({
  ordering: false,
  lengthChange: false,
  bFilter: false,
  searching: false,
  pageLength: 5,
  info: false,
});
function get_leads_details(client_id) {
  $.ajax({
    type: "post",
    url: "get_leads_details",
    dataType: "json",
    data: {
      client_id: client_id,
    },

    success: function (data) {
      if (data.status == "success") {
        $("#select_lead").hide();
        $("#save_lead").hide();
        $("#type_err").hide();
        $("#cancel_lead").hide();
        $(".change_lead_type").show();

        var type = data.response[0]["type"];

        if (type == "New") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-success badge-pill'>" +
            type +
            "</a>";
        }
        if (type == "Cold") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-danger badge-pill'>" +
            type +
            "</a>";
        }
        if (type == "Potential") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-primary badge-pill'>" +
            type +
            "</a>";
        }
        if (type == "Hot") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-warning badge-pill'>" +
            type +
            "</a>";
        }
        if (type == "Closed") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-danger badge-pill'>" +
            type +
            "</a>";
        }
        if (type == "Reopen") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-brown badge-pill'>" +
            type +
            "</a>";
        }
        if (type == "Not Interested") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-secondary badge-pill'>" +
            type +
            "</a>";
        }
           if (type == "NCT") {
          var html =
            "<a href='javascript:void(0);' class='change_lead_type badge badge-light-gray badge-pill'>" +
            type +
            "</a>";
        }
        $(".lead_type_span").empty().html(html);
      } else {
        $("#type_err").show();
        $("#type_err").html(res.msg);
      }
    },
    error: function (data) {},
  });
}

$(document).on("click", ".filter_sales_lead", function () {
  var value = $(this).data("value");
  var staff_id = $("#staff_id").val();

  $.ajax({
    type: "post",
    url: "filter_today_sales_lead",
    data: {
      value: value,
      staff_id: staff_id,
    },

    success: function (data) {
      var res = JSON.parse(data);
      if (res.status == "success") {
        $(".sales_div").empty().html(res.out);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter leads by sales",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
});

$(document).on("change", "#staff_id", function () {
  var value = $(this)
    .closest("ul")
    .children("li")
    .children("div")
    .find(".cursor-pointer")
    .text();
  var staff_id = $(this).val();

  $.ajax({
    type: "post",
    url: "filter_today_sales_lead",
    data: {
      value: value,
      staff_id: staff_id,
    },

    success: function (data) {
      var res = JSON.parse(data);
      if (res.status == "success") {
        $(".sales_div").empty().html(res.out);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter leads by sales",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
});

$(document).on("click", ".filter_office_lead", function () {
  var value = $(this).data("value");

  $.ajax({
    type: "post",
    url: "filter_today_office_lead",
    data: {
      value: value,
    },

    success: function (data) {
      var res = JSON.parse(data);
      if (res.status == "success") {
        $(".office_div").empty().html(res.out);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter leads by sales",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
});
function filter_today_attendance() {
  $.ajax({
    type: "get",
    url: "filter_today_attendance",
    datatype: "text/html",
    data: {},

    success: function (data) {
      //
      if (data) {
        $(".attendance_table").empty().html(data);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter attendance",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      //
    },
  });
}

function raise_attendance() {
  $.ajax({
    type: "get",
    url: "raise_attendance",
    datatype: "text/html",
    data: {},

    success: function (data) {
      //
      if (data) {
        $(".raise_attendance").empty().html(data);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter attendance",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      //
    },
  });
}

function office_visit_table() {
  $.ajax({
    type: "get",
    url: "office_visit",
    data: {},
    success: function (data) {
      //
      if (data) {
        $(".office_visit").empty().html(data);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter office visit",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      //
    },
  });
}
function daily_report_table() {
  $.ajax({
    type: "get",
    url: "daily_report",
    data: {},
    success: function (data) {
      console.log(data);
      if (data) {
        $("#daily_report_table").empty().html(data);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter office visit",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      console.log(data);
    },
  });
}
function filter_today_client(value, display) {
  if (value == undefined) {
    value = "today_client";
  }
  $.ajax({
    type: "post",
    url: "filter_today_client",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      //
      if (data) {
        $(".client_table").empty().html(data);
        $(".client-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter client",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      //
    },
  });
}
function filter_today_followup(value, display) {
  if (value == undefined) {
    value = "today_followup";
  }
  $.ajax({
    type: "post",
    url: "filter_today_followup",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      //
      if (data) {
        $(".followup_table").empty().html(data);
        $(".followup-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter followup",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      //
    },
  });
}
function filter_today_nextfollowup(value, display) {
  if (value == undefined) {
    value = "today_followup";
  }
  $.ajax({
    type: "post",
    url: "filter_today_nextfollowup",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".next_followup_table").html(data);
        $(".next_followup-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter followup",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      //
    },
  });
}
function filter_today_appointment(value, display) {
  if (value == undefined) {
    value = "today_appointment";
  }
  $.ajax({
    type: "post",
    url: "filter_today_appointment",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".appointment_table").empty().html(data);
        $(".appointment-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter appointment",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
}
function assign_client_due() {
 
  $.ajax({
    type: "get",
    url: "assign_client_due",
    datatype: "text/html",
    data: {
      
    },

    success: function (data) {
      console.log(data);
      if (data) {
        $(".assign_due_table").empty().html(data);
        
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter appointment",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      console.log(data);
    },
  });
}
function filter_today_leave(value, display) {
  if (value == undefined) {
    value = "today_leave";
  }
  $.ajax({
    type: "post",
    url: "filter_today_leave",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".leave_table").empty().html(data);
        $(".leave-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter leave",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
}
function filter_today_quotation(value, display) {
  if (value == undefined) {
    value = "today_quotation";
  }
  $.ajax({
    type: "post",
    url: "filter_today_quotation",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".quotation_sent_table").empty().html(data);
        $(".quotation-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter quotation",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
}
function filter_today_invoice(value, display) {
  if (value == undefined) {
    value = "today_invoice";
  }
  $.ajax({
    type: "post",
    url: "filter_today_invoice",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".raised_invoice_table").empty().html(data);
        $(".invoice-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter invoice",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
}
function filter_today_payment(value, display) {
  if (value == undefined) {
    value = "today_payment";
  }
  $.ajax({
    type: "post",
    url: "filter_today_payment",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".received_payment_table").empty().html(data);
        $(".payment-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter payment",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
}
function filter_today_sales_lead(value, display) {
  if (value == undefined) {
    value = "today_sales_lead";
  }
  $.ajax({
    type: "post",
    url: "filter_today_sales_lead",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".leads_by_sales_table").empty().html(data);
        $(".sales-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter leads by sales",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
}

function filter_today_office_lead(value, display) {
  if (value == undefined) {
    value = "today_office_lead";
  }
  $.ajax({
    type: "post",
    url: "filter_today_office_lead",
    datatype: "text/html",
    data: {
      value: value,
    },

    success: function (data) {
      if (data) {
        $(".leads_by_office_table").empty().html(data);
        $(".office-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter leads by office",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {},
  });
}

function filter_leads_table(value, display) {
  if (value == undefined) {
    value = "today_leads";
  }
  $.ajax({
    type: "post",
    url: "filter_leads_table",
    datatype: "text/html",
    data: {
      value: value,
    },
    success: function (data) {
      if (data) {
        $(".leads_table").empty().html(data);
        $(".leads-text").text(display);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Some error while filter leads",
          confirmButtonClass: "btn btn-danger",
        });
      }
    },
    error: function (data) {
      //
    },
  });
}
$(document).on("click", ".leads_timeline", function (e) {
   e.preventDefault();
  var client_id = $("#clientID").val();
  var url = "leads_timeline/" + client_id;
  window.open(url, "_blank");
});