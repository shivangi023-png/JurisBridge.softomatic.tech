$(document).ready(function () {
  $("#client").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Search By Clients",
  });
  $("#search_by_staff").select2();
  $("#search_by_source").select2();
  $("#search_by_city").select2();

  $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $(document).on("click", ".delete_client", function () {
    var mythis = $(this);
    var id = $(this).data("id");
    var page = "my_leads";
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
              mythis.closest("tr").remove();
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
  $(document).on("click", ".convert_client", function () {
    $(".loader").css("display", "block");
    var mythis = $(this);
    var client_id = $(this).data("client_id");
    var page = "my_leads";
    $.ajax({
      type: "post",
      url: "convert_client",
      data: {
        client_id: client_id,
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
        if (res.status == "success") {
          $(".loader").css("display", "none");
          $("#alert")
            .html(
              '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            )
            .focus();

          mythis.closest("tr").remove();
        }
      },
      error: function (data) {
        $(".loader").css("display", "none");
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
  });
  $(document).on("click", ".detailBtn", function () {
    var client_id = $(this).data("client_id");
    var detail = $(this).data("detail");
    get_quo_appo_foll(client_id, detail);
  });

  $(document).on("click", ".assign_btn", function () {
    var staff_id = $(this).data("assign_id");
    var staff_name = $(this).data("assign_val");
    var page = "my_leads";
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

            get_leads_by_id(
              page,
              "leads",
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
  $(document).on("click", ".change_lead_type", function () {
    $(this).closest("tr").find(".change_lead_type").css("display", "none");
    $(this).closest("tr").find(".lead_type_view").css("display", "none");
    $(this).closest("tr").find(".select_lead_type").css("display", "block");
    $(this).closest("tr").find(".save_lead_div").css("display", "block");
  });

  $(document).on("click", ".close_lead_type", function () {
    $(this).closest("tr").find(".change_lead_type").css("display", "block");
    $(this).closest("tr").find(".lead_type_view").css("display", "block");
    $(this).closest("tr").find(".select_lead_type").css("display", "none");
    $(this).closest("tr").find(".save_lead_div").css("display", "none");
  });

  $(document).on("click", ".save_lead_type", function () {
    $(".loader").css("display", "block");
    var mythis = $(this);
    var lead_type = $(this).closest("tr").find(".lead_type").val();
    var client_id = $(this).data("client_id");
    var page = "my_leads";
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

          get_leads_by_id(page, "leads", client_id, mythis, "save_lead_type");
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  });

  $(document).on("click", ".lead_history", function () {
    var client_id = $(this).data("client_id");
    get_lead_history(client_id);
  });

  $(document).on("change", "#client", function () {
    var client_id = new Array();
    $("#client :selected").each(function () {
      client_id.push($(this).val());
    });

    if (client_id != "") {
      $(".loader").css("display", "block");
      $.ajax({
        type: "post",
        url: "get_leads",
        datatype: "text",
        data: {
          client_leads: "",
          page: "my_leads",
          client_id: client_id,
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
    } else {
      $(".loader").css("display", "none");
      $(".data_div").empty();
    }
  });

  $(document).on("click", ".search", function () {
    var from_date = $(".from_date").val();
    var to_date = $(".to_date").val();
    var staff_id = $("#search_by_staff").val();
    var source = $("#search_by_source").val();
    var address = $("#search_by_address").val();
    var city = $("#search_by_city").val();
    var status = $("#search_by_status").val();
    var lead_type = $("#search_by_leadtype").val();

    if (
      source != "" ||
      staff_id != "" ||
      address != "" ||
      city != "" ||
      status != "" ||
      lead_type != "" ||
      (from_date != "" && to_date != "")
    ) {
      $(".loader").css("display", "block");
      $(".from_date_err").text("");
      $(".to_date_err").text("");
      $.ajax({
        type: "post",
        url: "get_leads",
        datatype: "text",
        data: {
          client_leads: "",
          page: "my_leads",
          selection_val: "Active",
          from_date: from_date,
          to_date: to_date,
          address: address,
          staff_id: staff_id,
          source: source,
          city: city,
          status: status,
          lead_type: lead_type,
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
    } else {
      if (from_date == "" && to_date != "") {
        $(".from_date_err").text("Select From Date");
        return false;
      }
      if (from_date != "" && to_date == "") {
        $(".to_date_err").text("Select To Date");
        return false;
      }
      if (
        from_date == "" &&
        to_date == "" &&
        address == "" &&
        source == "" &&
        staff_id == "" &&
        city == "" &&
        status == "" &&
        lead_type == ""
      ) {
        $("#alert").animate(
          {
            scrollTop: $(window).scrollTop(0),
          },
          "slow"
        );
        $("#alert")
          .html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Please Select filter</span></div></div>'
          )
          .focus();
        return false;
      }
    }
  });
});
$("#reset").click(function () {
  location.reload();
});
