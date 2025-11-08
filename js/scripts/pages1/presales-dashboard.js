$(document).ready(function () {
  if ($(".pickadate").length) {
    $(".pickadate").pickadate({
      format: "dd/mm/yyyy",
      onStart: function () {
        this.set({ select: new Date() });
      },
    });
  }
  if ($(".leave-data-table").length) {
    var dataListView = $(".leave-data-table").DataTable({
      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

      language: {
        search: "",
        searchPlaceholder: "Search",
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
