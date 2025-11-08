$(document).ready(function () {
  /********Invoice View ********/
  // ---------------------------
  // init date picker
  // if ($(".pickadate").length) {
  //   $(".pickadate").pickadate({
  //     format: "mm/dd/yyyy",
  //   });
  // }

  $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });

  /********Invoice List ********/
  // ---------------------------

  // init data table
  if ($(".payment-data-table").length) {
    var dataListView = $(".payment-data-table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control",
        },
        {
          orderable: false,
          targets: 0,
          //checkboxes: { selectRow: true },
        },
        {
          targets: [0, 1],
          orderable: false,
        },
      ],
      order: [2, "asc"],
      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      language: {
        search: "",
        searchPlaceholder: "Search Payment",
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
  var paymentFilterAction = $(".payment-filter-action");
  //var paymentOption = $(".body1");
  $(".action-btns").append(paymentFilterAction);
  // $(".dt-buttons").append(paymentOption);
});

$(document).on("click", ".create_deposit_btn", function () {
  $(this).closest("tr").find(".depo_dt_ui").css("display", "block");
  $(this).closest("tr").find(".depo_dt_data").css("display", "none");
  $(this).closest("tr").find(".depo_bank_ui").css("display", "block");
  $(this).closest("tr").find(".depo_bank_data").css("display", "none");
  $(this).closest("tr").find(".depo_by_ui").css("display", "block");
  $(this).closest("tr").find(".depo_by_data").css("display", "none");
  $(this).closest("tr").find(".deposit_btn").css("display", "block");
  $(this).closest("tr").find(".close_deposit_btn").css("display", "block");
  $(this).closest("tr").find(".create_deposit_btn").css("display", "none");
});
$(document).on("click", ".close_deposit_btn", function () {
  $(this).closest("tr").find(".depo_dt_ui").css("display", "none");
  $(this).closest("tr").find(".depo_dt_data").css("display", "block");
  $(this).closest("tr").find(".depo_bank_ui").css("display", "none");
  $(this).closest("tr").find(".depo_bank_data").css("display", "block");
  $(this).closest("tr").find(".depo_by_ui").css("display", "none");
  $(this).closest("tr").find(".depo_by_data").css("display", "block");
  $(this).closest("tr").find(".deposit_btn").css("display", "none");
  $(this).closest("tr").find(".close_deposit_btn").css("display", "none");
  $(this).closest("tr").find(".create_deposit_btn").css("display", "block");
});
$(document).on("click", ".deposit_btn", function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  var status = "deposited";
  var deposit_date = $(this).closest("tr").find(".deposit_date").val();
  var deposit_bank = $(this).closest("tr").find(".deposit_bank").val();
  var deposit_by = $(this).closest("tr").find(".deposit_by").val();
  var id = $(this).data("id");

  var arr = [];
  if (deposit_date == "") {
    arr.push("deposit_date_err");
    arr.push("Please select deposite date");
  }
  if (deposit_bank == "") {
    arr.push("deposit_bank_err");
    arr.push("Please select deposite bank");
  }
  if (deposit_by == "") {
    arr.push("deposit_by_err");
    arr.push("Please select deposite by");
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
    Swal.fire({
      title: "Are you sure?",
      text: "You want to deposite payment?",
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
          url: "deposite_payment",
          data: {
            status: status,
            id: id,
            deposit_date: deposit_date,
            deposit_bank: deposit_bank,
            deposit_by: deposit_by,
          },

          success: function (data) {
            console.log(data);
            var res = JSON.parse(data);

            if (res.status == "success") {
              Swal.fire({
                icon: "success",
                title: "Deposited!",
                text: "Payment has been deposited.",
                confirmButtonClass: "btn btn-success",
              });
              $(".receive_body").empty().html(res.rc_out);
              $(".deposite_body").empty().html(res.dp_out);
              $(".approve_body").empty().html(res.ap_out);
              // $(".total_rc_h4").empty().html(res.total_received);
              // $(".total_dp_h4").empty().html(res.total_deposited);
              // $(".total_ap_h4").empty().html(res.total_approved);

              var dataListView = $(".payment-data-table").DataTable({
                columnDefs: [
                  {
                    targets: 0,
                    className: "control",
                  },
                  {
                    orderable: false,
                    targets: 0,
                    //checkboxes: { selectRow: true },
                  },
                  {
                    targets: [0, 1],
                    orderable: false,
                  },
                ],
                order: [2, "asc"],
                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
                language: {
                  search: "",
                  searchPlaceholder: "Search Payment",
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
              var paymentFilterAction = $(".payment-filter-action");
              $(".action-btns").append(paymentFilterAction);
              $(".datepicker")
                .datepicker()
                .on("changeDate", function (ev) {
                  $(".datepicker.dropdown-menu").hide();
                });
            } else {
              Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Some error while deposite payment",
                confirmButtonClass: "btn btn-danger",
              });
            }
          },
          error: function (data) {
            console.log(data);
          },
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Your payment has not been deposited",
          confirmButtonClass: "btn btn-danger",
        });
      }
    });
  }
});
$(document).on("click", ".create_approve_btn", function () {
  $(this).closest("tr").find(".apr_dt_ui").css("display", "block");
  $(this).closest("tr").find(".apr_dt_data").css("display", "none");
  $(this).closest("tr").find(".apr_by_ui").css("display", "block");
  $(this).closest("tr").find(".apr_by_data").css("display", "none");
  $(this).closest("tr").find(".approve_btn").css("display", "block");
  $(this).closest("tr").find(".close_approve_btn").css("display", "block");
  $(this).closest("tr").find(".create_approve_btn").css("display", "none");
});
$(document).on("click", ".close_approve_btn", function () {
  $(this).closest("tr").find(".apr_dt_ui").css("display", "none");
  $(this).closest("tr").find(".apr_dt_data").css("display", "block");
  $(this).closest("tr").find(".apr_by_ui").css("display", "none");
  $(this).closest("tr").find(".apr_by_data").css("display", "block");
  $(this).closest("tr").find(".approve_btn").css("display", "none");
  $(this).closest("tr").find(".close_approve_btn").css("display", "none");
  $(this).closest("tr").find(".create_approve_btn").css("display", "block");
});
$(document).on("click", ".approve_btn", function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  var status = "approved";
  var approve_date = $(this).closest("tr").find(".approve_date").val();
  var approve_by = $(this).closest("tr").find(".approve_by").val();

  var id = $(this).data("id");

  var arr = [];
  if (approve_date == "") {
    arr.push("approve_date_err");
    arr.push("Please select approvee date");
  }
  if (approve_by == "") {
    arr.push("approve_by_err");
    arr.push("Please select approvee by");
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
    var id = $(this).data("id");
    Swal.fire({
      title: "Are you sure?",
      text: "You want to approve payment?",
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
          url: "approve_payment",
          data: {
            status: status,
            id: id,
            approve_date: approve_date,
            approve_by: approve_by,
          },

          success: function (data) {
            console.log(data);
            var res = JSON.parse(data);

            if (res.status == "success") {
              Swal.fire({
                icon: "success",
                title: "Approved!",
                text: "Payment has been approved.",
                confirmButtonClass: "btn btn-success",
              });
              $(".receive_body").empty().html(res.rc_out);
              $(".deposite_body").empty().html(res.dp_out);
              $(".approve_body").empty().html(res.ap_out);
              // $(".total_rc_h4").empty().html(res.total_received);
              // $(".total_dp_h4").empty().html(res.total_deposited);
              // $(".total_ap_h4").empty().html(res.total_approved);

              var dataListView = $(".payment-data-table").DataTable({
                columnDefs: [
                  {
                    targets: 0,
                    className: "control",
                  },
                  {
                    orderable: false,
                    targets: 0,
                    //checkboxes: { selectRow: true },
                  },
                  {
                    targets: [0, 1],
                    orderable: false,
                  },
                ],
                order: [2, "asc"],
                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
                buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
                language: {
                  search: "",
                  searchPlaceholder: "Search Payment",
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
              var paymentFilterAction = $(".payment-filter-action");
              $(".action-btns").append(paymentFilterAction);
              $(".action-btns").append(paymentFilterAction);
              $(".datepicker")
                .datepicker()
                .on("changeDate", function (ev) {
                  $(".datepicker.dropdown-menu").hide();
                });
            } else {
              Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Some error while approve payment",
                confirmButtonClass: "btn btn-danger",
              });
            }
          },
          error: function (data) {
            console.log(data);
          },
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Your payment has not been approved",
          confirmButtonClass: "btn btn-danger",
        });
      }
    });
  }
});
$(document).on("click", ".delete_payment", function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  var id = $(this).data("id");

  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this payment?",
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
        url: "delete_payment",
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
              text: "Payment has been deleted.",
              confirmButtonClass: "btn btn-success",
            });
            $(".receive_body").empty().html(res.rc_out);
            $(".deposite_body").empty().html(res.dp_out);
            $(".approve_body").empty().html(res.ap_out);
            // $(".total_rc_h4").empty().html(res.total_received);
            // $(".total_dp_h4").empty().html(res.total_deposited);
            // $(".total_ap_h4").empty().html(res.total_approved);

            var dataListView = $(".payment-data-table").DataTable({
              columnDefs: [
                {
                  targets: 0,
                  className: "control",
                },
                {
                  orderable: false,
                  targets: 0,
                  //checkboxes: { selectRow: true },
                },
                {
                  targets: [0, 1],
                  orderable: false,
                },
              ],
              order: [2, "asc"],
              dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
              buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
              language: {
                search: "",
                searchPlaceholder: "Search Payment",
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
            var paymentFilterAction = $(".payment-filter-action");
            $(".action-btns").append(paymentFilterAction);
          } else {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text: "Some error while delete payment",
              confirmButtonClass: "btn btn-danger",
            });
          }
        },
        error: function (data) {
          console.log(data);
        },
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Error!",
        text: "Payment can`t be deleted.",
        confirmButtonClass: "btn btn-danger",
      });
    }
  });
});
$(document).on("click", ".filter_approve_btn", function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $(".loader").css("display", "block");
  var value = $(this).data("value");

  $.ajax({
    type: "post",
    url: "filter_approve_payment",
    data: {
      value: value,
    },

    success: function (data) {
      $(".loader").css("display", "none");
      console.log(data);
      var res = JSON.parse(data);
      $(".receive_body").empty().html(res.rc_out);
      $(".deposite_body").empty().html(res.dp_out);
      $(".approve_body").empty().html(res.ap_out);
      // $(".total_rc_h4").empty().html(res.total_received);
      // $(".total_dp_h4").empty().html(res.total_deposited);
      // $(".total_ap_h4").empty().html(res.total_approved);
      let str = value;

      $(".selection").html(str.toUpperCase());
      var dataListView = $(".payment-data-table").DataTable({
        columnDefs: [
          {
            targets: 0,
            className: "control",
          },
          {
            orderable: false,
            targets: 0,
            //checkboxes: { selectRow: true },
          },
          {
            targets: [0, 1],
            orderable: false,
          },
        ],
        order: [2, "asc"],
        dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        language: {
          search: "",
          searchPlaceholder: "Search Payment",
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
      var paymentFilterAction = $(".payment-filter-action");
      $(".action-btns").append(paymentFilterAction);
    },
  });
});
