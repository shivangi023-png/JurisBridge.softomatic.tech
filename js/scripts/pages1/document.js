$(document).ready(function () {
  if ($(".upload-data-table").length) {
    var dataListView = $(".upload-data-table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control",
        },
        {
          orderable: true,
          targets: 0,
          // checkboxes: { selectRow: true }
        },
        {
          targets: [0, 1],
          orderable: false,
        },
      ],

      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

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

  if ($(".client-data-table").length) {
    var dataListView = $(".client-data-table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control",
        },
        {
          orderable: true,
          targets: 0,
          // checkboxes: { selectRow: true }
        },
        {
          targets: [0, 1],
          orderable: false,
        },
      ],

      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

      language: {
        search: "",
        searchPlaceholder: "Search Document",
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
  var clientFilterAction = $(".client-filter-action");
  var clientOptions = $(".client-options");
  $(".action-btns").append(clientFilterAction, clientOptions);
  $.ajax({
    type: "get",
    url: "autocomplete_tags",

    success: function (data) {
      console.log(data);
      $(".search_tag").autocomplete({
        source: data,
      });
      $(".bootstrap-tagsinput input").autocomplete({
        source: data,
      });
    },
  });
  $.ajax({
    type: "get",
    url: "autocomplete_title",

    success: function (data) {
      console.log(data);
      $(".title").autocomplete({
        source: data,
      });
    },
  });
});
$(document).on("click", "#search_btn", function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  var tags = $(".search_tag").val();
  var title = $(".title").val();
  var type = $(".search_cat").val();
  var date = $(".search_date").val();
  if (tags == "" && title == "" && type == "" && date == "") {
    alert("Please enter any value for searching");
  } else {
    $.ajax({
      type: "post",
      url: "search_tags",
      data: {
        tags: tags,
        title: title,
        type: type,
        date: date,
      },

      success: function (data) {
        console.log(data);
        $(".table-responsive").empty().html(data);
        if ($(".client-data-table").length) {
          var dataListView = $(".client-data-table").DataTable({
            columnDefs: [
              {
                targets: 0,
                className: "control",
              },
              {
                orderable: true,
                targets: 0,
                // checkboxes: { selectRow: true }
              },
              {
                targets: [0, 1],
                orderable: false,
              },
            ],

            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

            language: {
              search: "",
              searchPlaceholder: "Search Document",
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
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});
