$(document).ready(function () {
  /********Refund List ********/
  // ---------------------------

  // init data table
  if ($(".refund-data-table").length) {
    var dataListView = $(".refund-data-table").DataTable({
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
      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Refund",
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
  var refundFilterAction = $(".refund-filter-action");
  var refundOptions = $(".refund-options");
  $(".action-btns").append(refundFilterAction, refundOptions);
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
      $(".total_rc_h4").empty().html(res.total_received);
      $(".total_dp_h4").empty().html(res.total_deposited);
      $(".total_ap_h4").empty().html(res.total_approved);
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
