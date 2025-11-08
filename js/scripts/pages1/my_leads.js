$(document).ready(function () {
  if ($(".client-data-table").length) {
    var dataListView = $(".client-data-table").DataTable({
      scrollX: true,
      scrollCollapse: true,
      autoWidth: true,
      columnDefs: [
        { width: "240px", targets: [0] },
        {
          targets: [0],
          orderable: false,
        },
      ],
      order: [1, "asc"],
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
});
