$(document).ready(function () {
  /********expense View ********/
  // ---------------------------
  // init date picker
  // if ($(".pickadate").length) {
  //   $(".pickadate").pickadate({
  //     format: "dd/mm/yyyy",
  //     onStart: function () {
  //       this.set({ select: new Date() });
  //     },
  //   });
  // }

  $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });

  $("#by_whom").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Entry By",
  });

  /********expense List ********/
  // ---------------------------

  // init data table
  if ($(".allowance-data-table").length) {
    var dataListView = $(".allowance-data-table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control",
        },
        {
          orderable: true,
          targets: 1,
          checkboxes: { selectRow: true },
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
        searchPlaceholder: "Search",
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
  var allowanceFilterAction = $(".allowance-filter-action");
  var allowanceOptions = $(".allowance-options");
  $(".action-btns").append(allowanceFilterAction, allowanceOptions);

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

  $.ajax({
    type: "get",
    url: "autocomplete_destination",

    success: function (data) {
      console.log(data);
      $(".destination").autocomplete({
        source: data,
      });
    },
  });

  $.ajax({
    type: "get",
    url: "autocomplete_destination",
    success: function (data) {
      console.log(data);
      $(".modal_destination").autocomplete({
        source: data,
      });
    },
  });
});
