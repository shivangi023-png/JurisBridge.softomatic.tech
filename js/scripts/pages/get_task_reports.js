function get_report(id) {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    type: "post",
    url: "get_task_report",
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
        var link = document.createElement("a");
        link.href =
        $("#base_url").val() +"/" +id;
        link.target = "_blank";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

function download_excel(id) {
    var link = document.createElement("a");
    link.href =$("#base_url").val() +"/" + id;
    link.click();
}

function print(url) {
    var title = $("#" + url).data("title");
    $.ajax({
      type: "get",
      url: url,
      data: {},

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