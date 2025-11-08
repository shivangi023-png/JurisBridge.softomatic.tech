$(document).ready(function () {
  $(".datepicker")
    .datepicker()
    .on("changeDate", function (ev) {
      $(".datepicker.dropdown-menu").hide();
    });
  const d = new Date(),
    m = d.getMonth() + 1,
    y = d.getFullYear();

  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  var curMonth = d.getMonth();

  var fiscalYr = "";
  if (curMonth >= 3) {
    var nextYr1 = (d.getFullYear() + 1).toString();
    fiscalYr = d.getFullYear().toString() + "-" + nextYr1;
  } else {
    var nextYr2 = d.getFullYear().toString();
    fiscalYr = (d.getFullYear() - 1).toString() + "-" + nextYr2;
  }

  var month = monthNames[m - 1];

  var year = fiscalYr;

  var quarter = Math.floor((d.getMonth() + 3) / 3);
  if (quarter == 1) {
    data_display = "Fourth Quarter";
    var qq = $(".quarter_filter")
      .closest(".quarter-input")
      .find(".quarter-text")
      .text(data_display);
  }

  if (quarter == 2) {
    data_display = "First Quarter";
    var qq = $(".quarter_filter")
      .closest(".quarter-input")
      .find(".quarter-text")
      .text(data_display);
  }

  if (quarter == 3) {
    data_display = "Second Quarter";
    var qq = $(".quarter_filter")
      .closest(".quarter-input")
      .find(".quarter-text")
      .text(data_display);
  }

  if (quarter == 4) {
    data_display = "Third Quarter";
    var qq = $(".quarter_filter")
      .closest(".quarter-input")
      .find(".quarter-text")
      .text(data_display);
  }
  var dd = $(".daily_filter")
    .closest(".daily-input")
    .find(".daily-text")
    .text(date);
  var mm = $(".month_filter")
    .closest(".month-input")
    .find(".month-text")
    .text(month);
  var yy = $(".year_filter")
    .closest(".year-input")
    .find(".year-text")
    .text(year);
});

$(document).on("click", ".show_filter", function () {
  if ($(this).is(":checked")) {
    var value = $(this).val();
  }

  if (value == "year_search") {
    $(".month-input").hide();
    $(".quarter-input").hide();
    $(".year-input").show();
    $(".daily-input").hide();
  }

  if (value == "month_year_search") {
    $(".month-input").show();
    $(".quarter-input").hide();
    $(".year-input").show();
    $(".daily-input").hide();
  }

  if (value == "quarter_year_search") {
    $(".month-input").hide();
    $(".quarter-input").show();
    $(".year-input").show();
    $(".daily-input").hide();
  }

  if (value == "daily_search") {
    $(".month-input").hide();
    $(".quarter-input").hide();
    $(".year-input").hide();
    $(".daily-input").show();
  }
});

$(document).on("click", ".month_filter", function () {
  $(this)
    .closest(".month-input")
    .find(".month-text")
    .text($(this).data("display"));
});

$(document).on("click", ".quarter_filter", function () {
  $(this)
    .closest(".quarter-input")
    .find(".quarter-text")
    .text($(this).data("display"));
});

$(document).on("click", ".year_filter", function () {
  $(this)
    .closest(".year-input")
    .find(".year-text")
    .text($(this).data("display"));
});

// $(document).on("click", ".daily_filter", function () {
//   $(this)
//     .closest(".daily-input")
//     .find(".daily-text")
//     .text($(this).data("display"));
// });

function get_report(id) {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    type: "post",
    url: "get_report",
    datatype: "text",
    data: {
      id: id,
    },

    success: function (data) {
      console.log(data);
      $(".data_div").empty().html(data);
    },
    error: function (data) {
      console.log(data);
    },
  });
}

function download_pdf(id) {
  if ($("input[name='bsradio']").is(":checked")) {
    var filter = $("input[name='bsradio']:checked").val();

    $(".radio_error_message").html("");
    if (filter == "year_search") {
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (year_filter != "Year") {
        $(".error_message").html("");
        year_filter = year_filter;
        daily_filter = "none";
        month_filter = "none";
        quarter_filter = "none";
        var overlay = $(
          "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function () {
          $("#loading").remove();
        }, 2000);

        var link = document.createElement("a");
        link.href =
          $("#base_url").val() +
          "/" +
          id +
          "/" +
          year_filter +
          "/" +
          quarter_filter +
          "/" +
          month_filter +
          "/" +
          daily_filter;
        link.target = "_blank";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } else {
        $(".error_message").html("Select year filter first!!");
        return false;
      }
    } else if (filter == "month_year_search") {
      var month_filter = $(".month_filter")
        .closest(".month-input")
        .find(".month-text")
        .text();
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();

      if (month_filter != "Monthly" && year_filter != "Year") {
        $(".error_message").html("");
        month_filter = month_filter;
        year_filter = year_filter;
        daily_filter = "none";
        quarter_filter = "none";
        var overlay = $(
          "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function () {
          $("#loading").remove();
        }, 2000);
        var link = document.createElement("a");
        link.href =
          $("#base_url").val() +
          "/" +
          id +
          "/" +
          year_filter +
          "/" +
          quarter_filter +
          "/" +
          month_filter +
          "/" +
          daily_filter;
        link.target = "_blank";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } else {
        $(".error_message").html("Select month and year filter first!!");
        return false;
      }
    } else if (filter == "quarter_year_search") {
      var quarter_filter = $(".quarter_filter")
        .closest(".quarter-input")
        .find(".quarter-text")
        .text();
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (quarter_filter != "Quarterly" && year_filter != "Year") {
        $(".error_message").html("");
        quarter_filter = quarter_filter;
        year_filter = year_filter;
        month_filter = "none";
        daily_filter = "none";
        var overlay = $(
          "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function () {
          $("#loading").remove();
        }, 2000);
        var link = document.createElement("a");
        link.href =
          $("#base_url").val() +
          "/" +
          id +
          "/" +
          year_filter +
          "/" +
          quarter_filter +
          "/" +
          month_filter +
          "/" +
          daily_filter;

        link.target = "_blank";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } else {
        $(".error_message").html("Select quarter and year filter first!!");
        return false;
      }
    } else if (filter == "daily_search") {
      var daily_filter = $(".daily_filter").val();
      $(".error_message").html("");
      quarter_filter = "none";
      year_filter = "none";
      month_filter = "none";
      daily_filter = daily_filter;
      var overlay = $(
        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
      );
      $("body").append(overlay);
      setTimeout(function () {
        $("#loading").remove();
      }, 2000);
      var link = document.createElement("a");
      link.href =
        $("#base_url").val() +
        "/" +
        id +
        "/" +
        year_filter +
        "/" +
        quarter_filter +
        "/" +
        month_filter +
        "/" +
        daily_filter;

      link.target = "_blank";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  } else {
    $(".radio_error_message").html("Select radio button first!!");
    return false;
  }
}

function download_excel(id) {
  if ($("input[name='bsradio']").is(":checked")) {
    var filter = $("input[name='bsradio']:checked").val();

    $(".radio_error_message").html("");
    if (filter == "year_search") {
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (year_filter != "Year") {
        $(".error_message").html("");
        year_filter = year_filter;
        month_filter = "none";
        quarter_filter = "none";
        daily_filter = "none";
        var overlay = $(
          "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function () {
          $("#loading").remove();
        }, 2000);

        var link = document.createElement("a");
        link.href =
          $("#base_url").val() +
          "/" +
          id +
          "/" +
          year_filter +
          "/" +
          quarter_filter +
          "/" +
          month_filter +
          "/" +
          daily_filter;
        link.click();
      } else {
        $(".error_message").html("Select year filter first!!");
        return false;
      }
    } else if (filter == "month_year_search") {
      var month_filter = $(".month_filter")
        .closest(".month-input")
        .find(".month-text")
        .text();
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (month_filter != "Monthly" && year_filter != "Year") {
        $(".error_message").html("");
        month_filter = month_filter;
        year_filter = year_filter;
        quarter_filter = "none";
        daily_filter = "none";
        var overlay = $(
          "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function () {
          $("#loading").remove();
        }, 2000);

        var link = document.createElement("a");
        link.href =
          $("#base_url").val() +
          "/" +
          id +
          "/" +
          year_filter +
          "/" +
          quarter_filter +
          "/" +
          month_filter +
          "/" +
          daily_filter;
        link.click();
      } else {
        $(".error_message").html("Select month and year filter first!!");
        return false;
      }
    } else if (filter == "quarter_year_search") {
      var quarter_filter = $(".quarter_filter")
        .closest(".quarter-input")
        .find(".quarter-text")
        .text();
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (quarter_filter != "Quarterly" && year_filter != "Year") {
        $(".error_message").html("");
        quarter_filter = quarter_filter;
        year_filter = year_filter;
        month_filter = "none";
        daily_filter = "none";
        var overlay = $(
          "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
        );
        $("body").append(overlay);
        setTimeout(function () {
          $("#loading").remove();
        }, 2000);

        var link = document.createElement("a");
        link.href =
          $("#base_url").val() +
          "/" +
          id +
          "/" +
          year_filter +
          "/" +
          quarter_filter +
          "/" +
          month_filter +
          "/" +
          daily_filter;
        link.click();
      } else {
        $(".error_message").html("Select quarter and year filter first!!");
        return false;
      }
    } else if (filter == "daily_search") {
      var daily_filter = $(".daily_filter").val();
      $(".error_message").html("");
      quarter_filter = "none";
      year_filter = "none";
      month_filter = "none";
      daily_filter = daily_filter;
      var overlay = $(
        "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
      );
      $("body").append(overlay);
      setTimeout(function () {
        $("#loading").remove();
      }, 2000);

      var link = document.createElement("a");
      link.href =
        $("#base_url").val() +
        "/" +
        id +
        "/" +
        year_filter +
        "/" +
        quarter_filter +
        "/" +
        month_filter +
        "/" +
        daily_filter;
      link.click();
    }
  } else {
    $(".radio_error_message").html("Select radio button first!!");
    return false;
  }
}

function print(url) {
  var title = $("#" + url).data("title");
  if ($("input[name='bsradio']").is(":checked")) {
    var filter = $("input[name='bsradio']:checked").val();

    $(".radio_error_message").html("");
    if (filter == "year_search") {
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (year_filter != "Year") {
        $(".error_message").html("");
        year_filter = year_filter;
        month_filter = "none";
        quarter_filter = "none";
        daily_filter = "none";
        $.ajax({
          type: "get",
          url: url,
          data: {
            month: month_filter,
            quarter: quarter_filter,
            year: year_filter,
            date: daily_filter,
          },

          success: function (data) {
            console.log(data);
            //var res = JSON.parse(data);
            var printWindow = window.open("", "", "height=800,width=1200");
            printWindow.document.write(
              "<html><head><title>" + title + " Report :</title>"
            );
            printWindow.document.write("</head><body>");
            printWindow.document.write(data);
            printWindow.document.write("</body></html>");
            printWindow.document.close();
            printWindow.print();
          },
          error: function (data) {
            console.log(data);
          },
        });
      } else {
        $(".error_message").html("Select year filter first!!");
        return false;
      }
    } else if (filter == "month_year_search") {
      var month_filter = $(".month_filter")
        .closest(".month-input")
        .find(".month-text")
        .text();
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (month_filter != "Monthly" && year_filter != "Year") {
        $(".error_message").html("");
        month_filter = month_filter;
        year_filter = year_filter;
        quarter_filter = "none";
        daily_filter = "none";
        $.ajax({
          type: "get",
          url: url,
          data: {
            month: month_filter,
            quarter: quarter_filter,
            year: year_filter,
            date: daily_filter,
          },

          success: function (data) {
            console.log(data);
            //var res = JSON.parse(data);
            var printWindow = window.open("", "", "height=800,width=1200");
            printWindow.document.write(
              "<html><head><title>" + title + " Report :</title>"
            );
            printWindow.document.write("</head><body>");
            printWindow.document.write(data);
            printWindow.document.write("</body></html>");
            printWindow.document.close();
            printWindow.print();
          },
          error: function (data) {
            console.log(data);
          },
        });
      } else {
        $(".error_message").html("Select month and year filter first!!");
        return false;
      }
    } else if (filter == "quarter_year_search") {
      var quarter_filter = $(".quarter_filter")
        .closest(".quarter-input")
        .find(".quarter-text")
        .text();
      var year_filter = $(".year_filter")
        .closest(".year-input")
        .find(".year-text")
        .text();
      if (quarter_filter != "Quarterly" && year_filter != "Year") {
        $(".error_message").html("");
        quarter_filter = quarter_filter;
        year_filter = year_filter;
        month_filter = "none";
        daily_filter = "none";
        $.ajax({
          type: "get",
          url: url,
          data: {
            month: month_filter,
            quarter: quarter_filter,
            year: year_filter,
            date: daily_filter,
          },

          success: function (data) {
            console.log(data);
            //var res = JSON.parse(data);
            var printWindow = window.open("", "", "height=800,width=1200");
            printWindow.document.write(
              "<html><head><title>" + title + " Report :</title>"
            );
            printWindow.document.write("</head><body>");
            printWindow.document.write(data);
            printWindow.document.write("</body></html>");
            printWindow.document.close();
            printWindow.print();
          },
          error: function (data) {
            console.log(data);
          },
        });
      } else {
        $(".error_message").html("Select quarter and year filter first!!");
        return false;
      }
    } else if (filter == "daily_search") {
      var daily_filter = $(".daily_filter").val();
      $(".error_message").html("");
      quarter_filter = "none";
      year_filter = "none";
      month_filter = "none";
      daily_filter = daily_filter;
      $.ajax({
        type: "get",
        url: url,
        data: {
          month: month_filter,
          quarter: quarter_filter,
          year: year_filter,
          date: daily_filter,
        },

        success: function (data) {
          console.log(data);
          //var res = JSON.parse(data);
          var printWindow = window.open("", "", "height=800,width=1200");
          printWindow.document.write(
            "<html><head><title>" + title + " Report :</title>"
          );
          printWindow.document.write("</head><body>");
          printWindow.document.write(data);
          printWindow.document.write("</body></html>");
          printWindow.document.close();
          printWindow.print();
        },
        error: function (data) {
          console.log(data);
        },
      });
    }
  } else {
    $(".radio_error_message").html("Select radio button first!!");
    return false;
  }
}

function download_pdf1(id) {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  var overlay = $(
    "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
  );
  $("body").append(overlay);
  setTimeout(function () {
    $("#loading").remove();
  }, 10000);

  var link = document.createElement("a");
  link.href = $("#base_url").val() + "/" + id;
  link.target = "_blank";
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

function download_excel1(id) {
  var overlay = $(
    "<div id='loading' style='width:100%; height: 100%; top: 0; z-index: 100; position: absolute; background: lightgray; opacity: 0.5; text-align: center; font-size: large; padding-top: 25%; font-weight: bold;'><span style='font-size:20px;font-weight:700;color:#0000FF'>Please Wait ... </span></div>"
  );
  $("body").append(overlay);
  setTimeout(function () {
    $("#loading").remove();
  }, 10000);

  var link = document.createElement("a");
  link.href = $("#base_url").val() + "/" + id;
  link.click();
}

function print1(url) {
  var title = $("#" + url).data("title");
  $.ajax({
    type: "get",
    url: url,
    data: {},

    success: function (data) {
      console.log(data);
      var printWindow = window.open("", "", "height=800,width=1200");
      printWindow.document.write(
        "<html><head><title>" + title + " Report :</title>"
      );
      printWindow.document.write("</head><body>");
      printWindow.document.write(data);
      printWindow.document.write("</body></html>");
      printWindow.document.close();
      printWindow.print();
    },
    error: function (data) {
      console.log(data);
    },
  });
}
