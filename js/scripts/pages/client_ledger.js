$(document).ready(function () {
  if ($(".client-data-table").length) {
    var dataListView = $(".client-data-table").DataTable({
      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
      language: {
        search: "",
        searchPlaceholder: "Search Client",
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
});
