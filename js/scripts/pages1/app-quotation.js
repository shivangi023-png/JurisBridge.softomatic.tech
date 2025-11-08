$(document).ready(function () {
  if ($(".pickadate").length) {
    $(".pickadate").pickadate({
      format: "dd/mm/yyyy",
      onStart: function () {
        this.set({ select: new Date() });
      },
    });
  }

  /********quotation List ********/
  // ---------------------------
  $("#client").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Clients",
  });

  $(".service").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Service",
  });
  $("#up_service").select2({
    dropdownParent: $("#updatequotation"),
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Services",
  });
  // init data table
  if ($(".quotation-data-table").length) {
    var dataListView = $(".quotation-data-table").DataTable({
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
      dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Quotation",
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
  var quotationFilterAction = $(".quotation-filter-action");
  var quotationOptions = $(".quotation-options");
  var addButton = $(".add_button");
  $(".action-btns").append(quotationFilterAction, quotationOptions, addButton);

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

  // ********quotation Edit***********//
  // --------------------------------
  // form repeater jquery
  if ($(".quotation-item-repeater").length) {
    $(".quotation-item-repeater").repeater({
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
  if ($(".quotation-print").length > 0) {
    $(".quotation-print").on("click", function () {
      window.print();
    });
  }
  $(document).on("change", "#client", function () {
    var client = $('#client').val();
   
    $.ajax({
      type: "get",
      url: "get_quotation",
      data: {
        client: client,
      },

      success: function (data) {
        console.log(data);
        var res = JSON.parse(data);
        console.log(res);

        if (res.status == "success") {
          $(".data_div").empty().html(res.out);
          var dataListView = $(".quotation-data-table").DataTable({
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
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
            language: {
              search: "",
              searchPlaceholder: "Search Quotation",
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
          var quotationFilterAction = $(".quotation-filter-action");
          var quotationOptions = $(".quotation-options");
          var addButton = $(".add_button");
          $(".action-btns").append(
            quotationFilterAction,
            quotationOptions,
            addButton
          );

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
        } else {
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  });
  $(document).on("click", ".all_finalize", function () {
    var status = $("#quotation_filter").val();
    $(".status").val(status);
    var client = new Array();
    $("#client :selected").each(function () {
      client.push($(this).val());
    });

    var quotation_details_id = new Array();
    $(".dt-checkboxes:checked").each(function () {
      quotation_details_id.push(
        $(this).closest("tr").find(".quotation_details_id").val()
      );
    });

    if (client == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Client is not selected!</span></div></div>'
      );
      return false;
    }

    if (quotation_details_id == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
      );
      return false;
    }
    $(".client_id").val(client);
    $(".quotation_details_id").val(quotation_details_id);
    $("#finalizeModal").modal("show");
  });

  $(document).on("click", ".finalize_btn", function () {
    var client = $(".client_id").val();
    var quotation_details_id = $(".quotation_details_id").val();
    var finalize_date = $(".quotation_date").val();
    var status = $(".status").val();

    $.ajax({
      type: "get",
      url: "finalize_quotation",
      data: {
        quotation_details_id: quotation_details_id,
        client: client,
        finalize_date: finalize_date,
        status: status,
      },

      success: function (data) {
        $("#finalizeModal").modal("toggle");
        console.log(data);
        var res = JSON.parse(data);
        $("#alert").animate(
          {
            scrollTop: $(window).scrollTop(0),
          },
          "slow"
        );
        if (res.status == "success") {
          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
          if(res.case_no != ''){
            Swal.fire({
            text: res.case_no+" has been created for further client reference",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Copy Case No.'
            }).then((result) => {
            if (result.isConfirmed) {
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(res.case_no).select();
            document.execCommand("copy");
            temp.remove();
              Swal.fire(
                'Copied',
               'Case No. '+res.case_no,
                'success'
              )
            }
           })
          }

          $(".data_div").empty().html(res.out);
          var dataListView = $(".quotation-data-table").DataTable({
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
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
            language: {
              search: "",
              searchPlaceholder: "Search Quotation",
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
          var quotationFilterAction = $(".quotation-filter-action");
          var quotationOptions = $(".quotation-options");
          var addButton = $(".add_button");
          $(".action-btns").append(
            quotationFilterAction,
            quotationOptions,
            addButton
          );

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
        } else {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        $("#alert").animate(
          {
            scrollTop: $(window).scrollTop(0),
          },
          "slow"
        );
        $("#alert").html(
          '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>somthing went wrong</span></div></div>'
        );
      },
    });
  });

  $(document).on("click", ".finalize", function () {
    var status = $("#quotation_filter").val();
    $(".status").val(status);
    var client = new Array();
    $("#client :selected").each(function () {
      client.push($(this).val());
    });

    var quotation_details_id = new Array();
    quotation_details_id.push(
      $(this).closest("tr").find(".quotation_details_id").val()
    );

    if (client == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Client is not selected!</span></div></div>'
      );
      return false;
    }

    if (quotation_details_id == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
      );
      return false;
    }

    $(".client_id").val(client);
    $(".quotation_details_id").val(quotation_details_id);
    $("#finalizeModal").modal("show");
  });

  $(document).on("click", ".unfinalize", function () {
    var status = $("#quotation_filter").val();
    var client = new Array();
    $("#client :selected").each(function () {
      client.push($(this).val());
    });

    var quotation_details_id = new Array();
    quotation_details_id.push(
      $(this).closest("tr").find(".quotation_details_id").val()
    );

    if (client == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Client is not selected!</span></div></div>'
      );
      return false;
    }

    if (quotation_details_id == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
      );
      return false;
    }

    Swal.fire({
      title: "Are you sure?",
      text: "You want to unfinalize this quotation?",
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
          url: "unfinalize_quotation",
          data: {
            quotation_details_id: quotation_details_id,
            client: client,
            status: status,
          },

          success: function (data) {
            console.log(data);
            var res = JSON.parse(data);
            $("#alert").animate(
              {
                scrollTop: $(window).scrollTop(0),
              },
              "slow"
            );
            if (res.status == "success") {
              $("#alert").html(
                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              );

              if(res.case_no != ''){
                Swal.fire({
                  icon: 'success',
                  title: "Quotation Unfinalize",
                  text: "Case No :"+res.case_no+" associated to quotation no has been deactivated"
               })
              }

              $(".data_div").empty().html(res.out);
              var dataListView = $(".quotation-data-table").DataTable({
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
                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
                language: {
                  search: "",
                  searchPlaceholder: "Search Quotation",
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
              var quotationFilterAction = $(".quotation-filter-action");
              var quotationOptions = $(".quotation-options");
              var addButton = $(".add_button");
              $(".action-btns").append(
                quotationFilterAction,
                quotationOptions,
                addButton
              );

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
            } else {
              $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              );
            }
          },
          error: function (data) {
            $("#alert").animate(
              {
                scrollTop: $(window).scrollTop(0),
              },
              "slow"
            );
            $("#alert").html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>somthing went wrong</span></div></div>'
            );
          },
        });
      }
    });
  });

  $(document).on("click", ".all_unfinalize", function () {
    var status = $("#quotation_filter").val();
    var client = new Array();
    $("#client :selected").each(function () {
      client.push($(this).val());
    });

    var quotation_details_id = new Array();
    $(".dt-checkboxes:checked").each(function () {
      quotation_details_id.push(
        $(this).closest("tr").find(".quotation_details_id").val()
      );
    });

    if (client == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Client is not selected!</span></div></div>'
      );
      return false;
    }

    if (quotation_details_id == "") {
      $("#alert").html(
        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Checkbox is not selected!</span></div></div>'
      );
      return false;
    }

    Swal.fire({
      title: "Are you sure?",
      text: "You want to unfinalize this quotation?",
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
          url: "unfinalize_quotation",
          data: {
            quotation_details_id: quotation_details_id,
            client: client,
            status: status,
          },

          success: function (data) {
            console.log(data);
            var res = JSON.parse(data);
            $("#alert").animate(
              {
                scrollTop: $(window).scrollTop(0),
              },
              "slow"
            );
            if (res.status == "success") {
              $("#alert").html(
                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              );

              $(".data_div").empty().html(res.out);
              var dataListView = $(".quotation-data-table").DataTable({
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
                dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
                language: {
                  search: "",
                  searchPlaceholder: "Search Quotation",
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
              var quotationFilterAction = $(".quotation-filter-action");
              var quotationOptions = $(".quotation-options");
              var addButton = $(".add_button");
              $(".action-btns").append(
                quotationFilterAction,
                quotationOptions,
                addButton
              );

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
            } else {
              $("#alert").html(
                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                  res.msg +
                  "</span></div></div>"
              );
            }
          },
          error: function (data) {
            $("#alert").animate(
              {
                scrollTop: $(window).scrollTop(0),
              },
              "slow"
            );
            $("#alert").html(
              '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>somthing went wrong</span></div></div>'
            );
          },
        });
      }
    });
  });

  $(document).on("click", ".update_modal", function () {
    $("#up_id").val($(this).data("id"));
    $("#up_detid").val($(this).data("quotation_detail_id"));
    $("#up_service").val($(this).data("service"));
    $("#up_service").trigger("change");
    $("#up_client").html("(" + $(this).data("client_name") + ")");

    $("#up_send_date").val($(this).data("send_date"));
    $("#up_amount").val($(this).data("amount"));
    $("#prev_amt").val($(this).data("amount"));
    $("#up_total_amt").val($(this).data("total_amt"));

    $("#up_no_of_units").val($(this).data("no_of_units"));

    $("#up_per_unit_amount").val($(this).data("per_unit_amount"));
    $("#file_label").html($(this).data("file"));
    var files = $(this).data("file");
    var href = "http://dearsociety.in/karyarat/";
    $("#a_link").attr("href", files);
    $("#updatequotation").modal("show");
  });
  $(document).on("click", "#edit_doc", function () {
    $(".up_file_div").css("display", "block");
    $(".link_div").css("display", "none");
  });
  $(document).on("click", "#cancel_doc", function () {
    $(".up_file_div").css("display", "none");
    $(".link_div").css("display", "block");
  });
});
