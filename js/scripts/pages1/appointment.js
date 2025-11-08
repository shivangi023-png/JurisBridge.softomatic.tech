$(document).ready(function () {
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
      $('input[type="radio"]').prop("checked", false);
    });

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
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      language: {
        search: "",
        searchPlaceholder: "Search Appointment",
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
});

$(document).on("click", ".consulting_pay_btn", function () {
  $(".fees").val($(this).data("fees") + " INR");
  $(".appointment_id").val($(this).data("appointment_id"));
});

$(document).on("click", ".view_consulting_btn", function () {
  $(".consulting_body").empty();
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  var appointment_id = $(this).data("appointment_id");
  view_consulting_fee(appointment_id);
});

$(document).on("change", ".up_payment_mode", function () {
  if ($(this).val() == "cheque") {
    $(this).closest("tr").find(".up_online_div").css("display", "none");
    $(this).closest("tr").find(".up_cheque_div").css("display", "block");
    $(this).closest("tr").find(".nup_cheque_div").css("display", "none");
    $(this).closest("tr").find(".up_reference").val("");
    $(this).closest("tr").find(".up_remark").val("");
  }
  if ($(this).val() == "online") {
    $(this).closest("tr").find(".up_cheque_div").css("display", "none");
    $(this).closest("tr").find(".up_online_div").css("display", "block");
    $(this).closest("tr").find(".nup_online_div").css("display", "none");
    $(this).closest("tr").find(".up_cheque_no").val("");
    $(this).closest("tr").find(".up_cheque_date").val("");
    $(this).closest("tr").find(".up_bank").val("");
  }
  if ($(this).val() == "cash") {
    $(this).closest("tr").find(".up_cheque_div").css("display", "none");
    $(this).closest("tr").find(".up_online_div").css("display", "none");
    $(this).closest("tr").find(".up_reference").val("");
    $(this).closest("tr").find(".up_remark").val("");
    $(this).closest("tr").find(".up_cheque_no").val("");
    $(this).closest("tr").find(".up_cheque_date").val("");
    $(this).closest("tr").find(".up_bank").val("");
  }
});

$(document).on("click", ".edit_consulting_btn", function () {
  $(this).closest("tr").find(".up_con").css("display", "block");
  $(this).closest("tr").find(".edit_con").css("display", "none");
  $(this).closest("tr").find(".up_div").css("display", "block");
  $(this).closest("tr").find(".nup_div").css("display", "none");
});

$(document).on("change", ".payment_mode", function () {
  if ($(this).val() == "cheque") {
    $(".ref_div").css("display", "none");
    $(".cheque_div").css("display", "block");
    $(".bank_div").css("display", "block");
    $(".reference").val("");
    $(".remark").val("");
  }
  if ($(this).val() == "online") {
    $(".cheque_div").css("display", "none");
    $(".ref_div").css("display", "block");
    $(".bank_div").css("display", "block");
    $(".cheque_no").val("");
    $(".cheque_date").val("");
  }
  if ($(this).val() == "cash") {
    $(".cheque_div").css("display", "none");
    $(".ref_div").css("display", "none");
    $(".bank_div").css("display", "none");
    $(".reference").val("");
    $(".remark").val("");
    $(".cheque_no").val("");
    $(".cheque_date").val("");
    $(".bank").val("");
  }
});

$(document).on("click", ".fees_btn", function () {
  $(".data_div").empty();
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $(".valid_err").html("");
  var appointment_id = $(".appointment_id").val();
  var fees = $(".fees").val();
  var payment_date = $(".payment_date").val();
  var payment_mode = $(".payment_mode").val();
  var cheque_no = $(".cheque_no").val();
  var cheque_date = $(".cheque_date").val();
  var bank = $(".bank").val();
  var reference = $(".reference").val();
  var remark = $(".remark").val();
  var filter_status = $(".appointment_filter").val();
  if (filter_status) {
    var filter_status = filter_status;
  } else {
    var filter_status = "";
  }
  var arr = [];

  if (fees == "") {
    arr.push("fees_err");
    arr.push("consulting fee required");
  }
  if (payment_date == "") {
    arr.push("payment_date_err");
    arr.push("payment date required");
  }
  if (payment_mode == "") {
    arr.push("payment_mode_err");
    arr.push("Please select meeting with");
  }
  if (payment_mode == "cheque") {
    if (cheque_no == "") {
      arr.push("cheque_no_err");
      arr.push("Please insert cheque no");
    }
    if (cheque_date == "") {
      arr.push("cheque_date_err");
      arr.push("Cheque date required");
    }
    if (bank == "") {
      arr.push("bank_err");
      arr.push("Bank name required");
    }
  }
  if (payment_mode == "online") {
    if (reference == "") {
      arr.push("reference_err");
      arr.push("Please insert reference no");
    }
    if (remark == "") {
      arr.push("remark_err");
      arr.push("Remark is required");
    }
    if (bank == "") {
      arr.push("bank_err");
      arr.push("Bank name required");
    }
  }

  if (arr != "") {
    for (var i = 0; i < arr.length; i++) {
      var j = i + 1;

      $("." + arr[i])
        .html(arr[j])
        .css("color", "red");

      i = j;
    }
  } else {
    $.ajax({
      type: "post",
      url: "submit_consulting_fee",

      data: {
        fees: fees,
        payment_date: payment_date,
        payment_mode: payment_mode,
        cheque_no: cheque_no,
        cheque_date: cheque_date,
        bank: bank,
        reference: reference,
        remark: remark,
        appointment_id: appointment_id,
      },

      success: function (data) {
        console.log(data);
        var res = JSON.parse(data);
        if (res.status == "success") {
          $("#modalconsultingfee").modal("toggle");

          Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Consulting fee payment done successfully!",
            confirmButtonClass: "btn btn-success",
          });

          var pass_data = {
            from_date: "",
            to_date: "",
            meeting_with: "",
            schedule_by: "",
            meeting_type: "",
            status: filter_status,
            all_appointment: "",
          };
          get_appointment_by_status(pass_data);
        } else {
          Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Consulting fee payment can't done",
            confirmButtonClass: "btn btn-danger",
          });
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".cancle_btn", function () {
  $(this).closest("tr").find(".up_con").css("display", "none");
  $(this).closest("tr").find(".edit_con").css("display", "block");
  $(this).closest("tr").find(".up_div").css("display", "none");
  $(this).closest("tr").find(".nup_div").css("display", "block");
  $(this).closest("tr").find(".up_cheque_div").css("display", "none");
  $(this).closest("tr").find(".up_online_div").css("display", "none");
  $(this).closest("tr").find(".nup_cheque_div").css("display", "block");
  $(this).closest("tr").find(".nup_online_div").css("display", "block");
});

$(document).on("click", ".up_consulting_btn", function () {
  $(".data_div").empty();
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $(".valid_err").html("");
  var payment_mode = $(this).closest("tr").find(".up_payment_mode").val();
  var cheque_no = $(this).closest("tr").find(".up_cheque_no").val();
  var cheque_date = $(this).closest("tr").find(".up_cheque_date").val();
  var bank = $(this).closest("tr").find(".up_bank").val();
  var reference = $(this).closest("tr").find(".up_reference").val();
  var remark = $(this).closest("tr").find(".up_remark").val();
  var consulting_id = $(this).data("id");

  var arr = [];

  if (payment_mode == "") {
    arr.push("up_payment_mode_err");
    arr.push("Please select mode of payment");
  }
  if (payment_mode == "cheque") {
    if (cheque_no == "") {
      arr.push("up_cheque_no_err");
      arr.push("Please insert cheque no");
    }
    if (cheque_date == "") {
      arr.push("up_cheque_date_err");
      arr.push("Cheque date required");
    }
    if (bank == "") {
      arr.push("up_bank_err");
      arr.push("Bank name required");
    }
  }
  if (payment_mode == "online") {
    if (reference == "") {
      arr.push("up_reference_err");
      arr.push("Please insert reference no");
    }
    if (remark == "") {
      arr.push("up_remark_err");
      arr.push("Remark is required");
    }
  }

  if (arr != "") {
    for (var i = 0; i < arr.length; i++) {
      var j = i + 1;

      $("." + arr[i])
        .html(arr[j])
        .css("color", "red");

      i = j;
    }
  } else {
    $.ajax({
      type: "post",
      url: "update_consulting_fee",

      data: {
        payment_mode: payment_mode,
        cheque_no: cheque_no,
        cheque_date: cheque_date,
        bank: bank,
        reference: reference,
        remark: remark,
        consulting_id: consulting_id,
      },

      success: function (data) {
        console.log(data);
        var res = JSON.parse(data);
        if (res.status == "success") {
          $("#viewConsultingFee").modal("toggle");

          Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Consulting fee payment updated successfully!",
            confirmButtonClass: "btn btn-success",
          });

          var pass_data = {
            from_date: "",
            to_date: "",
            meeting_with: "",
            schedule_by: "",
            meeting_type: "",
            status: "",
            all_appointment: "",
          };
          get_appointment_by_status(pass_data);
        } else {
          Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Consulting fee payment can't updated",
            confirmButtonClass: "btn btn-danger",
          });
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".delete_consulting_btn", function () {
  var id = $(this).data("id");
  var appointment_id = $(this).data("appointment_id");

  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this consulting fee payment?",
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
        url: "delete_consulting_fee",
        data: {
          id: id,
          appointment_id: appointment_id,
        },

        success: function (data) {
          console.log(data);
          var res = JSON.parse(data);
          if (res.status == "success") {
            $("#viewConsultingFee").modal("toggle");
            Swal.fire({
              icon: "success",
              title: "Deleted!",
              text: "Consulting fee payment has been deleted.",
              confirmButtonClass: "btn btn-success",
            });
            var pass_data = {
              from_date: "",
              to_date: "",
              meeting_with: "",
              schedule_by: "",
              meeting_type: "",
              status: "",
              all_appointment: "",
            };
            get_appointment_by_status(pass_data);
          } else {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text: "Consulting fee payment can`t be deleted.",
              confirmButtonClass: "btn btn-danger",
            });
          }
        },
        error: function (data) {
          console.log(data);
        },
      });
    }
  });
});

$(document).on("click", ".delete_appointment_btn", function () {
  var appointment_id = $(this).data("appointment_id");
  var filter_status = $(".appointment_filter").val();

  if (filter_status) {
    var filter_status = filter_status;
  } else {
    var filter_status = "";
  }

  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this appointment?",
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
        url: "delete_appointment",
        data: {
          appointment_id: appointment_id,
        },

        success: function (data) {
          console.log(data);
          var res = JSON.parse(data);
          if (res.status == "success") {
            Swal.fire({
              icon: "success",
              title: "Deleted!",
              text: "Appointment has been deleted.",
              confirmButtonClass: "btn btn-success",
            });
            var pass_data = {
              from_date: "",
              to_date: "",
              meeting_with: "",
              schedule_by: "",
              meeting_type: "",
              status: filter_status,
              all_appointment: "",
            };
            get_appointment_by_status(pass_data);
          } else {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text: "Appointment can`t be deleted.",
              confirmButtonClass: "btn btn-danger",
            });
          }
        },
      });
    }
  });
});

$(document).on("click", ".remodalbtn", function () {
  $(".re_appointment_id").val($(this).data("appointment_id"));
  $(".re_time").val($(this).data("time"));
  $(".re_date").val($(this).data("date"));
  $(".re_meeting_with").val($(this).data("meeting_with"));
  $("#meeting_type").val($(this).data("place"));
  if ($(this).data("place") == 2) {
    $("#online_meeting").show();
    $("#online_meeting").val($(this).data("online_meeting"));
  } else {
    $("#online_meeting").val("");
    $("#online_meeting").hide();
  }
});

$(document).on("click", ".reschedule_btn", function () {
  var today = new Date();
  var todayDate =
    today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
  var time = $(".re_time").val();
  var appointment_id = $(".re_appointment_id").val();
  var date = $(".re_date").val();
  var meeting_with = $(".re_meeting_with").val();
  var meeting_place = $("#meeting_type").val();
  var filter_status = $(".appointment_filter").val();
  var online_meeting = $("#online_meeting").val();

  if (filter_status) {
    var filter_status = filter_status;
  } else {
    var filter_status = "";
  }
  var arr = [];
  $(".valid_err").html("");
  if (time == "") {
    arr.push("time_err");
    arr.push("time required");
  }
  if (date == "") {
    arr.push("date_err");
    arr.push("Date required");
  }
  if (meeting_with == "") {
    arr.push("meeting_with_err");
    arr.push("Please select meeting with");
  }

  if (meeting_place == "") {
    arr.push("meeting_type_err");
    arr.push("Please select Meeting Type");
  }
  if (meeting_place == 2 && online_meeting == "") {
    arr.push("online_meeting_err");
    arr.push("Please enter online meeting");
  }
  todayDate =
    todayDate.split("/")[2] +
    "-" +
    todayDate.split("/")[1] +
    "-" +
    todayDate.split("/")[0];

  if (date.indexOf("/") > 0) {
    date =
      date.split("/")[2] + "-" + date.split("/")[1] + "-" + date.split("/")[0];
  }
  if (date != "" && Date.parse(todayDate) > Date.parse(date)) {
    arr.push("date_err");
    arr.push("Meeting can`t be reschedule on this date");
  }
  if (arr != "") {
    for (var i = 0; i < arr.length; i++) {
      var j = i + 1;

      $("." + arr[i])
        .html(arr[j])
        .css("color", "red");

      i = j;
    }
  } else {
    $.ajax({
      type: "post",
      url: "reschedule_meeting",

      data: {
        time: time,
        appointment_id: appointment_id,
        date: date,
        meeting_with: meeting_with,
        meeting_place: meeting_place,
        status: status,
        online_meeting: online_meeting,
      },

      success: function (data) {
        console.log(data);
        var res = JSON.parse(data);
        if (res.status == "success") {
          $("#reshcheduleModal").modal("toggle");
          Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Meeting reschedule successfully",
            confirmButtonClass: "btn btn-success",
          });

          var pass_data = {
            from_date: "",
            to_date: "",
            meeting_with: "",
            schedule_by: "",
            meeting_type: "",
            status: filter_status,
            all_appointment: "",
          };
          get_appointment_by_status(pass_data);
        } else {
          Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Meeting can`t be reschedule",
            confirmButtonClass: "btn btn-danger",
          });
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});
$(document).on("change", "#meeting_type", function () {
  var meeting_title_id = $(this).val();
  $(".online_meeting_err").html("");
  if (meeting_title_id == 2) {
    $("#online_meeting").show();
  } else {
    $("#online_meeting").hide();
    $("#online_meeting").val("");
  }
});
