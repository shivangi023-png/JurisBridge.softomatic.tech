$(document).ready(function () {
  if ($(".client-data-table").length) {
    var dataListView = $(".client-data-table").DataTable({
      scrollX: true,
      scrollCollapse: true,
      autoWidth: true,
      columnDefs: [
        { width: "240px", targets: [3] },
        { width: "100px", targets: [5] },
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
        searchPlaceholder: "Search Leads",
      },

      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row",
      },
    });
  }
  // To append actions dropdown inside action-btn div
  var clientFilterAction = $(".client-filter-action");
  var clientOptions = $(".client-options");
  $(".action-btns").append(clientFilterAction, clientOptions);
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
});
