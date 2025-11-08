function getFinancialDate(financialYearStart, financialYearEnd) {
  var date1 = new Date(financialYearStart);
  // Extract year, month, and day
  var year = date1.getFullYear();
  var month = String(date1.getMonth() + 1).padStart(2, "0"); // Months are 0-indexed, so add 1
  var day = String(date1.getDate()).padStart(2, "0");
  // Format the date as yyyy-mm-dd
  var financialYearStartDate = day + "-" + month + "-" + year;

  var date2 = new Date(financialYearEnd);
  // Extract year, month, and day
  var year = date2.getFullYear();
  var month = String(date2.getMonth() + 1).padStart(2, "0"); // Months are 0-indexed, so add 1
  var day = String(date2.getDate()).padStart(2, "0");
  // Format the date as yyyy-mm-dd
  var financialYearEndDate = day + "-" + month + "-" + year;

  return {
    financialYearStartDate: financialYearStartDate,
    financialYearEndDate: financialYearEndDate,
  };
}

$(document).ready(function () {
  /********leave List ********/
  // if ($(".pickadate").length) {
  //   $(".pickadate").pickadate({
  //     format: "dd-mm-yyyy",
  //   });
  // }

  $("#search_by_staff").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Staff",
  });
  var today = new Date();
  var year = today.getFullYear();
  var month = today.getMonth(); // January is 0
 
  
  

  // ---------------------------
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  pending_leaves();
  $(".nav-link").click(function () {
    var hrefValue = $(this).attr("href");
    var cleanHref = hrefValue.replace(/^#/, "");
    if (cleanHref == "statistics") {
      show_statistics(month,year);
    }
    if (cleanHref == "approved") {
      approved_leaves();
    }
    if (cleanHref == "rejected") {
      rejected_leaves();
    }
  });

  $(document).on("click", ".search", function () {
    var month = $("#month").val();
    var year = $("#year").val();
    if(month=="")
    {
      $('.month_err').html('Please select month');
    }
    else
    {
     
      show_statistics(month, year);
    }
  

    
    
  });

  $(document).on("click", ".view_statistics_by_staff", function () {
    var staff_id = $("#search_by_staff").val();
    $.ajax({
      url: "show_staffwise_statistics",
      type: "post",
      data: {
       
        staff_id: staff_id,
      },
      success: function (response) {
        console.log(response);
        if (response.status == "success") {
          var innerHtml =
            '<div class="card border"><div class="card-body"><div class="LeaveTopBox"><center><h4>Leave Statistics</h4></center></div><div class="row TotalStaffLeaveBoxMain"><div class="col-sm-6"><div class="TotalStaffLeaveBox"><div class="TotalStaffLeaveContent"><h4>Total Earned Leaves</h4><h3 class="total_earned_leaves">' +
            response.total_earned_leaves +
            '</h3></div></div></div><div class="col-sm-6"><div class="AvailableLeavesBox"><div class="AvailableLeavesContent"><h4>Applied Earned Leaves</h4><h3 class="applied_earned_leaves">' +
            response.applied_earned_leaves +
            '</h3></div></div></div><div class="col-sm-6"><div class="TotalStaffLeaveBox"><div class="TotalStaffLeaveContent"><h4>Total Unpaid Leaves</h4><h3 class="total_unpaid_leaves">' +
            response.total_unpaid_leaves +
            '</h3></div></div></div><div class="col-sm-6"><div class="AvailableLeavesBox"><div class="AvailableLeavesContent"><h4>Applied Unpaid Leaves</h4><h3 class="applied_unpaid_leaves">' +
            response.applied_unpaid_leaves +
            '</h3></div></div></div><div class="col-sm-6"><div class="TotalStaffLeaveBox"><div class="TotalStaffLeaveContent"><h4>Total Sick Leaves</h4><h3 class="total_sick_leaves">' +
            response.total_sick_leaves +
            '</h3></div></div></div><div class="col-sm-6"><div class="AvailableLeavesBox"><div class="AvailableLeavesContent"><h4>Applied Sick Leaves</h4><h3 class="applied_sick_leaves">' +
            response.applied_sick_leaves +
            '</h3></div></div></div><div class="col-sm-6"><div class="TotalStaffLeaveBox"><div class="TotalStaffLeaveContent"><h4>Total Weekly Off</h4><h3 class="total_weekly_off">' +
            response.total_weekly_off +
            '</h3></div></div></div><div class="col-sm-6"><div class="AvailableLeavesBox"><div class="AvailableLeavesContent"><h4>Applied Weekly Off</h4><h3 class="applied_weekly_off">' +
            response.applied_weekly_off +
            "</h3></div></div></div></div></div></div>";
          $("#leaveTotal").empty().append(innerHtml);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching data:", error);
      },
    });
  });

  $(".view_statistics").click(function () {
    var staff_id = $(this).data("staff_id");
    var leave_id = $(this).data("leave_id");
    $.ajax({
      url: "show_staffwise_statistics",
      type: "post",
      data: {
        financialYearStart: financialDates.financialYearStartDate,
        financialYearEnd: financialDates.financialYearEndDate,
        staff_id: staff_id,
        leave_id: leave_id,
      },
      success: function (response) {
        console.log(response);
        if (response.status == "success") {
          $(".total_earned_leaves").text(response.total_earned_leaves);
          $(".applied_earned_leaves").text(response.applied_earned_leaves);
          $(".total_unpaid_leaves").text(response.total_unpaid_leaves);
          $(".applied_unpaid_leaves").text(response.applied_unpaid_leaves);
          $(".total_sick_leaves").text(response.total_sick_leaves);
          $(".applied_sick_leaves").text(response.applied_sick_leaves);
          $(".total_weekly_off").text(response.total_weekly_off);
          $(".applied_weekly_off").text(response.applied_weekly_off);
          $("#exampleModal").modal("show");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching data:", error);
      },
    });
  });

  $(document).on("click", ".app_rej_btn", function () {
    var value = $(this).val();
    var id = $(this).data("id");
    var response = $(this).data("response");

    $.ajax({
      type: "post",
      url: "approve_reject_leave",
      data: {
        value: value,
        id: id,
      },

      success: function (data) {
        console.log(data);
        var res = JSON.parse(data);
        $("#alert").animate(
          {
            scrollTop: $(window).scrollTop(0),
          },
          "slow"
        );
        if (res.status == "success") {
          $("#alert")
            .html(
              '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            )
            .focus();
          $(".alert")
            .fadeTo(2000, 500)
            .slideUp(500, function () {
              $(".alert").slideUp(500);
            });
          if (response == "pending") {
            pending_leaves();
          }
          if (response == "approved") {
            approved_leaves();
          }
          if (response == "rejected") {
            rejected_leaves();
          }
        } else {
          $("#alert")
            .html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            )
            .focus();
          $(".alert")
            .fadeTo(2000, 500)
            .slideUp(500, function () {
              $(".alert").slideUp(500);
            });
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

        $("#alert")
          .html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
          )
          .focus();
        $(".alert")
          .fadeTo(2000, 500)
          .slideUp(500, function () {
            $(".alert").slideUp(500);
          });
      },
    });
  });

  $(document).on("click", ".edit_btn", function () {
    $(this).closest("tr").find(".edit_btn").css("display", "none");
    $(this).closest("tr").find(".done_edit_div").css("display", "block");
    $(this).closest("tr").find(".leave_type_input").css("display", "block");
    $(this).closest("tr").find(".leave_type_val").css("display", "none");
    $(this).closest("tr").find(".start_date_input").css("display", "block");
    $(this).closest("tr").find(".start_date_val").css("display", "none");
    $(this).closest("tr").find(".end_date_input").css("display", "block");
    $(this).closest("tr").find(".end_date_val").css("display", "none");
  });

  $(document).on("click", ".close_edit_btn", function () {
    $(this).closest("tr").find(".edit_btn").css("display", "block");
    $(this).closest("tr").find(".done_edit_div").css("display", "none");
    $(this).closest("tr").find(".leave_type_input").css("display", "none");
    $(this).closest("tr").find(".leave_type_val").css("display", "block");
    $(this).closest("tr").find(".start_date_input").css("display", "none");
    $(this).closest("tr").find(".start_date_val").css("display", "block");
    $(this).closest("tr").find(".end_date_input").css("display", "none");
    $(this).closest("tr").find(".end_date_val").css("display", "block");
  });

  $(document).on("click", ".done_edit_btn", function () {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    var leave_type = $(this).closest("tr").find(".leave_type").val();
    var start_date = $(this).closest("tr").find(".start_date").val();
    var end_date = $(this).closest("tr").find(".end_date").val();

    var id = $(this).data("id");

    var arr = [];

    if (start_date == "") {
      arr.push("start_date_err");
      arr.push("Please select start date");
    }

    if (end_date == "") {
      arr.push("end_date_err");
      arr.push("Please select end date");
    }

    if (arr != "") {
      for (var i = 0; i < arr.length; i++) {
        var j = i + 1;

        $(this)
          .closest("tr")
          .find("." + arr[i])
          .html(arr[j])
          .css("color", "red");

        i = j;
      }
    } else {
      $.ajax({
        type: "post",
        url: "edit_staff_leave",
        data: {
          id: id,
          leave_type: leave_type,
          start_date: start_date,
          end_date: end_date,
        },

        success: function (data) {
          console.log(data);
          var res = JSON.parse(data);
          $("#alert").animate(
            {
              scrollTop: $(window).scrollTop(0),
            },
            "slow"
          );
          if (res.status == "success") {
            $("#alert")
              .html(
                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              )
              .focus();
            $(".alert")
              .fadeTo(2000, 500)
              .slideUp(500, function () {
                $(".alert").slideUp(500);
              });
            pending_leaves();
          } else {
            $("#alert")
              .html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              )
              .focus();
            $(".alert")
              .fadeTo(2000, 500)
              .slideUp(500, function () {
                $(".alert").slideUp(500);
              });
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

          $("#alert")
            .html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
            )
            .focus();
          $(".alert")
            .fadeTo(2000, 500)
            .slideUp(500, function () {
              $(".alert").slideUp(500);
            });
        },
      });
    }
  });

  $(document).on("click", ".delete_btn", function () {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });

    var id = $(this).data("id");
    var response = $(this).data("response");

    Swal.fire({
      title: "Are you sure?",
      text: "You want to delete this staff leave?",
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
          url: "delete_staff_leave",
          data: {
            id: id,
          },

          success: function (data) {
            console.log(data);
            var res = JSON.parse(data);
            $("#alert").animate(
              {
                scrollTop: $(window).scrollTop(0),
              },
              "slow"
            );
            if (res.status == "success") {
              $("#alert")
                .html(
                  '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                    res.msg +
                    "</span></div></div>"
                )
                .focus();
              $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function () {
                  $(".alert").slideUp(500);
                });
              if (response == "pending") {
                pending_leaves();
              }
              if (response == "approved") {
                approved_leaves();
              }
              if (response == "rejected") {
                rejected_leaves();
              }
            } else {
              $("#alert")
                .html(
                  '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                    res.msg +
                    "</span></div></div>"
                )
                .focus();
              $(".alert")
                .fadeTo(2000, 500)
                .slideUp(500, function () {
                  $(".alert").slideUp(500);
                });
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

            $("#alert")
              .html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something wend wrong!</span></div></div>'
              )
              .focus();
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
});

function pending_leaves() {
    
  $.ajax({
    url: "pending_leaves",
    type: "get",
    data: {},
    success: function (response) {
        console.log(response);
      $(".pending_body").html(response);
      if ($(".pending-leave-data-table").length) {
        var dataListView = $(".pending-leave-data-table").DataTable({
          sorting: false,
          dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
          buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
          select: {
            style: "multi",
            selector: "td:first-child",
            items: "row",
          },
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
}

function approved_leaves() {
  $.ajax({
    url: "approved_leaves",
    type: "get",
    data: {},
    success: function (response) {
      $(".approved_body").html(response);
      if ($(".approved-leave-data-table").length) {
        var dataListView = $(".approved-leave-data-table").DataTable({
          sorting: false,
          dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
          buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
          language: {
            search: "",
            searchPlaceholder: "Search Approved Leave",
          },
          select: {
            style: "multi",
            selector: "td:first-child",
            items: "row",
          },
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
}

function rejected_leaves() {
  $.ajax({
    url: "rejected_leaves",
    type: "get",
    data: {},
    success: function (response) {
      $(".rejected_body").html(response);
      if ($(".rejected-leave-data-table").length) {
        var dataListView = $(".rejected-leave-data-table").DataTable({
          sorting: false,
          dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"B><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
          buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
          select: {
            style: "multi",
            selector: "td:first-child",
            items: "row",
          },
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
}

function show_statistics(month,year) {
  $.ajax({
    url: "show_statistics",
    type: "post",
    data: {
      month: month,
      year: year,
    },
    success: function (response) {
      console.log(response);
      $(".statistics_body").html(response);
      if ($(".statistics-leave-data-table").length) {
        var dataListView = $(".statistics-leave-data-table").DataTable({
          sorting: false,
          dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
          buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
          language: {
            search: "",
            searchPlaceholder: "Search Leave",
          },
          select: {
            style: "multi",
            selector: "td:first-child",
            items: "row",
          },
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
}
