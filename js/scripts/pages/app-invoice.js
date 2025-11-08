
$(document).ready(function () {
  /********Invoice View ********/
  // ---------------------------
  // init date picker
  if ($(".pickadate").length) {
    $(".pickadate").pickadate({
      format: "dd/mm/yyyy",
      onStart: function() { this.set({ select: new Date()}); }
    });
  }
  if ($(".pickadate1").length) {
    $(".pickadate1").pickadate({
         format: "dd/mm/yyyy",
    });
  }
  if ($("#bill_date").length) {
    $("#bill_date").pickadate({
         format: "dd/mm/yyyy",
         min:new Date(),
         onStart: function() { this.set({ select: new Date()}); }
    });
  }
   $("#client").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Clients",
  });
  $(".service").select2({
     
    dropdownAutoWidth: true,
    width: '100%',
    placeholder: "Select Services"
  });
  /********Invoice List ********/
  // ---------------------------

  // init data table
  if ($(".invoice-data-table").length) {
    var dataListView = $(".invoice-data-table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1,
          checkboxes: { selectRow: true }
        },
        {
          targets: [0, 1],
          orderable: false
        },
      ],
     
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
        buttons: [
          'copyHtml5',
          'excelHtml5',
          'csvHtml5',
          'pdfHtml5'
      ],
      
        language: {
        search: "",
        searchPlaceholder: "Search Invoice"
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row"
      },
      responsive: {
        details: {
          type: "column",
          target: 0
        }
      }
    });
  }

  // To append actions dropdown inside action-btn div
  var invoiceFilterAction = $(".invoice-filter-action");
  var invoiceOptions = $(".invoice-options");
  $(".action-btns").append(invoiceFilterAction, invoiceOptions);

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

  // ********Invoice Edit***********//
  // --------------------------------
  // form repeater jquery
  if ($(".invoice-item-repeater").length) {
    $(".invoice-item-repeater").repeater({
      show: function () {
        $(this).slideDown();
      },
      hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
      }
    });
  }
  // dropdown form's prevent parent action
  
 
  // // on product change also change product description
 
  // print button
  if ($(".invoice-print").length > 0) {
    $(".invoice-print").on("click", function () {
      window.print();
    })
  }
});
