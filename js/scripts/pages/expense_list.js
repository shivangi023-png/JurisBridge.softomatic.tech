$(document).ready(function () {
  /********expense View ********/
  // ---------------------------
  // init date picker
  $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });

  /********expense List ********/
  // ---------------------------

  // init data table
  if ($(".expense-data-table").length) {
    var dataListView = $(".expense-data-table").DataTable({
      scrollX: true,
      scrollCollapse: true,
      autoWidth: true,
      columnDefs: [
        { width: "240px", targets: [3] },
        {
          targets: 0,
          className: "control",
        },
        {
          orderable: false,
          targets: 1,
          checkboxes: { selectRow: true },
        },
        {
          targets: [0, 1, 2, 3],
          orderable: false,
        },
      ],
      order: [4, "asc"],
      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      language: {
        search: "",
        searchPlaceholder: "Search Expense",
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row",
      },
    });
  }

  // To append actions dropdown inside action-btn div
  var expenseFilterAction = $(".expense-filter-action");
  var expenseOptions = $(".expense-options");
  $(".action-btns").append(expenseFilterAction, expenseOptions);

  // add class in row if checkbox checked
  $(".dt-checkboxes-cell")
    .find("input")
    .on("change", function () {
      var $this = $(this);
      if ($this.is(":checked")) {
        $this.closest("tr").addClass("selected-row-bg");
      } else {
        $this.closest("tr").removeClass("selected-row-bg");
      }
    });
  // Select all checkbox
  $(document).on("change", ".dt-checkboxes-select-all input", function () {
    if ($(this).is(":checked")) {
      $(".dt-checkboxes-cell")
        .find("input")
        .prop("checked", this.checked)
        .closest("tr")
        .addClass("selected-row-bg");
    } else {
      $(".dt-checkboxes-cell")
        .find("input")
        .prop("checked", "")
        .closest("tr")
        .removeClass("selected-row-bg");
    }
  });

  // ********expense Edit***********//
  // --------------------------------
  // form repeater jquery
  if ($(".expense-item-repeater").length) {
    $(".expense-item-repeater").repeater({
      show: function () {
        $(this).slideDown();
      },
      hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
      },
    });
  }
  // dropdown form's prevent parent action

  // // on product change also change product description

  // print button
  if ($(".expense-print").length > 0) {
    $(".expense-print").on("click", function () {
      window.print();
    });
  }
});
$(document).on("click", ".filter_approve_btn", function () {
  $(".data_div").empty();
  $(".loader").css("display", "block");

  var selecteddate = $(this).data("value");

  $.ajax({
    type: "post",
    url: "filter_approve_expense",
    data: {
      selecteddate: selecteddate,
    },

    success: function (data) {
      $(".loader").css("display", "none");
      console.log(data);
      var res = JSON.parse(data);
      if (res.status == "success") {
        $(".data_div").empty().html(res.out);
        let str = selecteddate;

        $(".selection1").html(str.toUpperCase());
        var dataListView = $(".expense-data-table").DataTable({
          scrollX: true,
          scrollCollapse: true,
          autoWidth: true,
          columnDefs: [
            {
              width: "240px",
              targets: [3],
            },
            {
              targets: 0,
              className: "control",
            },
            {
              orderable: false,
              targets: 1,
              checkboxes: {
                selectRow: true,
              },
            },
            {
              targets: [0, 1, 2, 3, 4],
              orderable: false,
            },
          ],
          order: [5, "asc"],
          dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
          buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
          language: {
            search: "",
            searchPlaceholder: "Search Expense",
          },

          select: {
            style: "multi",
            selector: "td:first-child",
            items: "row",
          },
        });

        // To append actions dropdown inside action-btn div
        var expenseFilterAction = $(".expense-filter-action");
        var expenseOptions = $(".expense-options");
        $(".action-btns").append(expenseFilterAction, expenseOptions);
        // add class in row if checkbox checked
        $(".dt-checkboxes-cell")
          .find("input")
          .on("change", function () {
            var $this = $(this);
            if ($this.is(":checked")) {
              $this.closest("tr").addClass("selected-row-bg");
            } else {
              $this.closest("tr").removeClass("selected-row-bg");
            }
          });
        // Select all checkbox
        $(document).on(
          "change",
          ".dt-checkboxes-select-all input",
          function () {
            if ($(this).is(":checked")) {
              $(".dt-checkboxes-cell")
                .find("input")
                .prop("checked", this.checked)
                .closest("tr")
                .addClass("selected-row-bg");
            } else {
              $(".dt-checkboxes-cell")
                .find("input")
                .prop("checked", "")
                .closest("tr")
                .removeClass("selected-row-bg");
            }
          }
        );
      } else {
        $("#alert").animate(
          {
            scrollTop: $(window).scrollTop(0),
          },
          "slow"
        );

        $("#alert")
          .html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
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
      $("#alert").animate(
        {
          scrollTop: $(window).scrollTop(0),
        },
        "slow"
      );

      $("#alert")
        .html(
          '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Something went wrong!</span></div></div>'
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
