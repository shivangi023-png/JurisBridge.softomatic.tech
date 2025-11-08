$("#project_id,#task_id").val("");
$(".staff_id").select2({
  dropdownAutoWidth: true,
  width: "100%",
  multiple: true,
  placeholder: "Select Staff",
});
$(".project_startdate_enddate").daterangepicker(
  {
    showDropdowns: true,
    autoApply: true,
    locale: {
      format: "DD/MM/YYYY",
    },
    autoclose: true,
    alwaysShowCalendars: true,
    startDate: $(this).data("project_start_date"),
    endDate: $(this).data("project_end_date"),
  },
  function (start, end) {
    $("#start_date_input").val(start.format("DD/MM/YYYY"));
    $("#end_date_input").val(end.format("DD/MM/YYYY"));
  }
);

// Task functions
$(document).on("click", "#save_task_submit_btn", function () {
  $(".valid_err").html("");
  var project_id = $("#project_id").val();
  var template_id = $("#template_id").val();
  var task_title = $(".task_title").val();
  var task_description = $(".new_task_editor > .ql-editor").html();
  var task_type = $(".task_type").val();
  var task_priority = $(".task_priority").val();
  var task_assignee = $(".task_assignee").val();
  var task_date = $(".task_date").val();
  var task_status = $(".task_status").val();
  var task_is_milestone = $(".task_is_milestone:checked").val();
  var task_file = $(".task_file")[0].files[0];

  if (task_is_milestone == undefined) {
    task_is_milestone = "";
  }
  if (task_description == "<p><br></p>") {
    task_description = "";
  }
  var fd = new FormData();
  fd.append("project_id", project_id);
  fd.append("template_id", template_id);
  fd.append("title", task_title);
  fd.append("description", task_description);
  fd.append("type", task_type);
  fd.append("priority", task_priority);
  fd.append("assignee", task_assignee);
  fd.append("task_date", task_date);
  fd.append("status", task_status);
  fd.append("is_milestone", task_is_milestone);
  fd.append("file", task_file);

  var arr = [];
  if (task_title == "") {
    arr.push("task_title_err");
    arr.push("Title required");
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
      url: "add_task",
      data: fd,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        console.log(data);
        var res = data;
        console.log(res);
        if (res.status == "success") {
          $(".tbl_div").empty().html(res.body);
          $(".todo-new-task-sidebar,.app-content-overlay").removeClass("show");
          $("#project_id").val("");
          $("#template_id").val("");
          $("#task_id").val("");
          $(".task_title").val("");
          $(".new_task_editor > .ql-editor").html("");
          $(".task_type").val("");
          $(".task_priority").val("");
          $(".task_assignee").val("");
          $(".task_date").val("");
          $(".task_status").val();
          $(".task_is_milestone").prop("checked", false);

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Task Created Successfully</span></div></div>'
          );
          fetch_task_list();
        } else if (res.status == "error") {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>Task can`t be created</span></div></div>'
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

//Save Task Template
$(document).on("click", "#submit_task_template_btn", function () {
  $(".valid_err").html("");
  var template_id = $("#template_id").val();
  var task_title = $(".task_title").val();
  var task_description = $(".ql-editor").html();
  var task_type = $(".task_type").val();
  var task_priority = $(".task_priority").val();
  var task_assignee = $(".task_assignee").val();
  var task_date = $(".task_date").val();
  var task_status = $(".task_status").val();
  var task_is_milestone = $(".task_is_milestone:checked").val();
  var task_file = $(".task_file")[0].files[0];

  if (task_is_milestone == undefined) {
    task_is_milestone = "";
  }
  if (task_description == "<p><br></p>") {
    task_description = "";
  }
  var fd = new FormData();
  fd.append("project_id", project_id);
  fd.append("template_id", template_id);
  fd.append("title", task_title);
  fd.append("description", task_description);
  fd.append("type", task_type);
  fd.append("priority", task_priority);
  fd.append("assignee", task_assignee);
  fd.append("task_date", task_date);
  fd.append("status", task_status);
  fd.append("is_milestone", task_is_milestone);
  fd.append("file", task_file);

  var arr = [];
  if (task_title == "") {
    arr.push("task_title_err");
    arr.push("Title required");
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
      url: "add_task_template",
      data: fd,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        console.log(data);
        var res = JSON.parse(data);
        console.log(res);
        if (res.status == "success") {
          $(".todo-new-task-sidebar,.app-content-overlay").removeClass("show");
          $("#project_id").val("");
          $("#template_id").val("");
          $("#task_id").val("");
          $(".task_title").val("");
          $(".ql-editor").html("");
          $(".task_type").val("");
          $(".task_priority").val("");
          $(".task_assignee").val("");
          $(".task_date").val("");
          $(".task_status").val();
          $(".task_is_milestone").prop("checked", false);

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
          location.reload();
        } else if (res.status == "error") {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", "#update_task_btn", function () {
  $(".valid_err").html("");
  var id = $("#task_id").val();
  var project_id = $("#project_id").val();
  var template_id = $("#template_id").val();
  var task_title = $(".task_title").val();
  var task_description = $(".new_task_editor > .ql-editor").html();
  var task_type = $(".task_type").val();
  var task_priority = $(".task_priority").val();
  var task_assignee = $(".task_assignee").val();
  var task_date = $(".task_date").val();
  var task_status = $(".task_status").val();
  var task_is_milestone = $(".task_is_milestone:checked").val();
  var file_link = $("#file_link").val();
  var task_file = $(".task_file")[0].files[0];
  if (task_is_milestone == undefined) {
    task_is_milestone = "";
  }
  if (task_file == undefined) {
    task_file = "";
  }
  if (task_description == "<p><br></p>") {
    task_description = "";
  }
  var arr = [];
  if (task_title == "") {
    arr.push("task_title_err");
    arr.push("Title required");
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
    var fd = new FormData();
    fd.append("id", id);
    fd.append("project_id", project_id);
    fd.append("template_id", template_id);
    fd.append("title", task_title);
    fd.append("description", task_description);
    fd.append("type", task_type);
    fd.append("priority", task_priority);
    fd.append("assignee", task_assignee);
    fd.append("task_date", task_date);
    fd.append("status", task_status);
    fd.append("is_milestone", task_is_milestone);
    fd.append("file", task_file);
    fd.append("file_link", file_link);
    $.ajax({
      type: "post",
      url: "update_task",
      data: fd,
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        if (res.status == "success") {
          $(".todo-new-task-sidebar,.app-content-overlay").removeClass("show");
          $("#project_id").val("");
          $("#template_id").val("");
          $("#task_id").val("");
          $(".task_title").val("");
          $(".new_task_editor > .ql-editor").html("");
          $(".task_type").val("");
          $(".task_priority").val("");
          $(".task_assignee").val("");
          $(".task_date").val("");
          $(".task_status").val();
          $(".task_is_milestone").prop("checked", false);

          fetch_task_list();
          location.reload();

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        } else if (res.status == "error") {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".new_task_btn", function () {
  $(".autoapply").daterangepicker({
    autoApply: true,
    locale: {
      format: "DD/MM/YYYY",
    },
  });
  $(".div_file_link").hide();
  $(".project_startdate_enddate,.task_date").val("");
  $("#project_id").val($(this).data("project_id"));
  $("#template_id").val($(this).data("template_id"));
  $("#task_id").val("");
  $(".valid_err").html("");
  $(".task_btn").removeAttr("id");
  $(".new-task-title").empty().html("New Task");
  $(".task_btn").attr("id", "save_task_submit_btn");
  $(".task_btn_name").empty().html("Save");
  $(".task_title").val("");
  $(".new_task_editor > .ql-editor").html("");
  $(".task_type").val("");
  $(".task_priority").val("");
  console.log(323);
  $(".task_assignee").val("");
  $(".task_assignee")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Select Assignee",
    })
    .trigger("change");
  $(".task_date").val("");
  $(".task_status").val();
  $(".task_is_milestone").prop("checked", false);
  $(".new_task_modal12,.app-content-overlay").addClass("show");
});

//End Task functions

//Project functions
$(document).on("click", "#submit_project_btn", function () {
  $(".valid_err").html("");
  var project_name = $(".project_name").val();
  var project_startdate_enddate = $(".project_startdate_enddate").val();
  var staff_id = $(".staff_id").val();
  var service_id = $(".service_id").val();
  var client_id = $("#client_id").val();
  var case_no = $("#case_no").val();
  var project_status = $("#project_status").val();
  if (staff_id.length == 0) {
    staff_id = null;
  }
  var arr = [];
  if (project_name == "") {
    arr.push("project_name_err");
    arr.push("Project Name required");
  }
  if (project_startdate_enddate == "") {
    arr.push("project_startdate_enddate_err");
    arr.push("Project Start Date and End Date Required");
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
      url: "new_project",
      data: {
        project_name: project_name,
        project_startdate_enddate: project_startdate_enddate,
        staff_id: staff_id,
        service_id: service_id,
        client_id: client_id,
        case_no: case_no,
        status: project_status,
      },
      success: function (res) {
        if (res.status == "success") {
          $("#NewProjectModal").modal("toggle");
          $(".project_name").val("");
          $(".project_startdate_enddate").val("");
          $(".staff_id").val("");
          $(".service_id").val("");
          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        } else if (res.status == "error") {
          $("#NewProjectModal").modal("toggle");
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".update_project", function () {
  $("#NewProjectModal").modal("toggle");

  $(".project_btn").removeAttr("id");
  $(".modal_project_title").empty().html("Update Project");
  $(".project_btn").attr("id", "update_project");
  $(".project_btn_name").empty().html("Update");

  $("#projectid").val($(this).data("project_id"));
  $(".project_name").val($(this).data("project_name"));

  $(".project_startdate_enddate").val("");
  if (
    $(this).data("project_start_date") != "" ||
    $(this).data("project_end_date")
  ) {
    $(".project_startdate_enddate").val(
      $(this).data("project_start_date") +
        " - " +
        $(this).data("project_end_date")
    );
    $(".project_startdate_enddate").daterangepicker(
      {
        showDropdowns: true,
        autoApply: true,
        locale: {
          format: "DD/MM/YYYY",
        },
        autoclose: true,
        alwaysShowCalendars: true,
        startDate: $(this).data("project_start_date"),
        endDate: $(this).data("project_end_date"),
      },
      function (start, end) {
        $("#start_date_input").val(start.format("DD/MM/YYYY"));
        $("#end_date_input").val(end.format("DD/MM/YYYY"));
      }
    );
  }

  // Set selected
  $(".staff_id").val($(this).data("project_staff_id"));
  $(".staff_id")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
      multiple: true,
      dropdownParent: $("#NewProjectModal"),
    })
    .trigger("change");

  $(".service_id").val($(this).data("project_service_id"));
  $(".service_id")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
    })
    .trigger("change");
  $("#project_status").val($(this).data("project_status_id"));
  $("#project_status")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
    })
    .trigger("change");
});

$(document).on("click", "#update_project", function () {
  $(".valid_err").html("");
  var project_id = $("#projectid").val();
  var project_name = $(".project_name").val();
  var project_startdate_enddate = $(".project_startdate_enddate").val();
  var staff_id = $(".staff_id").val();
  var service_id = $(".service_id").val();
  var status = $("#project_status").val();
  if (staff_id.length == 0) {
    staff_id = null;
  }

  var arr = [];
  if (project_name == "") {
    arr.push("project_name_err");
    arr.push("Project Name required");
  }
  if (project_startdate_enddate == "") {
    arr.push("project_startdate_enddate_err");
    arr.push("Project Start Date and End Date Required");
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
      url: "update_project",
      data: {
        id: project_id,
        project_name: project_name,
        project_startdate_enddate: project_startdate_enddate,
        staff_id: staff_id,
        service_id: service_id,
        status: status,
      },
      success: function (res) {
        if (res.status == "success") {
          $("#NewProjectModal").modal("toggle");
          $("#projectid").val("");
          $(".project_name").val("");
          $(".project_startdate_enddate").val("");
          $(".staff_id").val("");
          $(".service_id").val("");
          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
          fetch_projects_list();
        } else if (res.status == "error") {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".delete_project", function () {
  var project_id = $(this).data("project_id");
  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this Project",
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
        url: "delete_project",
        data: {
          id: project_id,
        },
        success: function (res) {
          if (res.status == "success") {
            $("#alert").html(
              '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            );
            fetch_projects_list();
          } else if (res.status == "error") {
            $("#alert").html(
              '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            );
          }
        },
        error: function (data) {
          console.log(data);
        },
      });
    }
  });
});

$(document).on("click", ".new_modal_project", function () {
  $("#NewProjectModal").modal("toggle");
  $(".valid_err").html("");
  $(".project_btn").removeAttr("id");
  $(".modal_project_title").empty().html("Create Project");
  $(".project_btn").attr("id", "submit_project_btn");
  $(".project_btn_name").empty().html("Create");

  $("#projectid").val("");
  $(".project_name").val("");
  $(".project_startdate_enddate").val("");
  $(".staff_id")
    .val("")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
    })
    .trigger("change");
  $(".service_id")
    .val("")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
    })
    .trigger("change");
});

$(document).on("click", ".project_task", function (e) {
  $(".new_task_modal12,.app-content-overlay").addClass("show");
  $(".task_btn").removeAttr("id");
  $(".new-task-title").empty().html("Update Task");
  $(".task_btn").attr("id", "update_task_btn");
  $(".task_btn_name").empty().html("Update");

  $("#project_id").val($(this).data("project_id"));
  $("#template_id").val($(this).data("template_id"));
  $("#task_id").val($(this).data("task_id"));
  $(".task_title").val($(this).data("task_title"));
  $(".task_type").val($(this).data("task_type"));
  $(".task_priority").val($(this).data("task_priority"));
  if ($(this).data("task_status") != "") {
    $(".task_status").val($(this).data("task_status"));
  } else {
    $(".task_status").val("1");
  }

  $("#file_link").val($(this).data("file_link"));
  $(".div_file_link").hide();
  $(".div_file_upload").show();
  if ($(this).data("file_link") != "") {
    $(".div_file_link").show();
    $(".div_file_upload").hide();
    var fileName = /[^/]*$/.exec($(this).data("file_link"));
    var split = fileName[0].split(".");
    fileName = split[0];
    var extension = split[1];
    if (fileName.length > 10) {
      fileName = fileName.substring(0, 10);
    }
    $(".file_link")
      .attr("href", $(this).data("file_link"))
      .attr("download", fileName + "." + extension)
      .attr("target", "_blank")
      .text(fileName + "." + extension);
  }
  if ($(this).data("task_is_milestone") == "yes") {
    $(".task_is_milestone").prop("checked", true);
  } else {
    $(".task_is_milestone").prop("checked", false);
  }

  $(".autoapply").daterangepicker({
    autoApply: true,
    locale: {
      format: "DD/MM/YYYY",
    },
  });

  $(".project_startdate_enddate,.task_date").val("");

  if ($(this).data("task_start_date") != "" || $(this).data("task_end_date")) {
    $(".task_date").val(
      $(this).data("task_start_date") + " - " + $(this).data("task_end_date")
    );

    $(".autoapply").daterangepicker(
      {
        showDropdowns: true,
        autoApply: true,
        locale: {
          format: "DD/MM/YYYY",
        },
        autoclose: true,
        alwaysShowCalendars: true,
        startDate: $(this).data("task_start_date"),
        endDate: $(this).data("task_end_date"),
      },
      function (start, end) {
        $("#start_date_input").val(start.format("DD/MM/YYYY"));
        $("#end_date_input").val(end.format("DD/MM/YYYY"));
      }
    );
  }
  console.log("abc=" + $(this).data("task_assignee"));
  // Set selected
  if ($(this).data("task_assignee") != "") {
    $(".task_assignee").val($(this).data("task_assignee")).trigger("change");
  } else {
    $(".task_assignee").val("");
  }

  $(".task_assignee")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Select Assignee",
    })
    .trigger("change");
  if ($(e.target).hasClass("ignore-click") || $("a").hasClass("ignore-click")) {
    return false;
  }
});

//End Project functions

//Template Functions
//New Task Template Open form
$(document).on("click", ".new_task_Template_btn", function () {
  $(".autoapply").daterangepicker({
    autoApply: true,
    locale: {
      format: "DD/MM/YYYY",
    },
  });
  $(".div_file_link").hide();
  $(".project_startdate_enddate,.task_date").val("");
  $("#template_id").val($(this).data("main_template_id"));
  $("#task_id").val("");
  $(".valid_err").html("");
  $(".task_btn").removeAttr("id");
  $(".new-task-title").empty().html("New Task Template");
  $(".task_btn").attr("id", "submit_task_template_btn");
  $(".task_btn_name").empty().html("Save");
  $(".task_title").val("");
  // $('.ql-editor').html('');
  $(".new_task_editor > .ql-editor").html("");
  $(".task_type").val("");
  $(".task_priority").val("");
  $(".task_assignee").val("");
  $(".task_assignee")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Select Assignee",
    })
    .trigger("change");
  $(".task_date").val("");
  $(".task_status").val();
  $(".task_is_milestone").prop("checked", false);
  $(".new_task_modal12,.app-content-overlay").addClass("show");
});

$(".new_task_cancel").click(function () {
  $(".todo-new-task-sidebar,.app-content-overlay").removeClass("show");
});

$(document).on("click", ".new_modal_template", function () {
  $("#NewTemplateModal").modal("toggle");
  $(".valid_err").html("");
  $(".template_btn").removeAttr("id");
  $(".modal_template_title").empty().html("Create Template");
  $(".template_btn").attr("id", "submit_template_btn");
  $(".template_btn_name").empty().html("Create");
  $("#form_template_status").hide();
  $("#templateid").val("");
  $(".template_name").val("");
  $(".template_description").val("");
});

$(document).on("click", "#submit_template_btn", function () {
  $(".valid_err").html("");
  var template_name = $(".template_name").val();
  var template_description = $(".template_description").val();

  var arr = [];
  if (template_name == "") {
    arr.push("template_name_err");
    arr.push("Template Name required");
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
      url: "add_template",
      data: {
        template_name: template_name,
        description: template_description,
      },
      success: function (res) {
        if (res.status == "success") {
          $("#NewTemplateModal").modal("toggle");
          $(".template_name").val("");
          fetch_template_list();
          fetch_template_task_list();

          console.log("add template");

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        } else if (res.status == "error") {
          $("#NewTemplateModal").modal("toggle");

          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", "#update_template", function () {
  $(".valid_err").html("");
  var template_id = $("#templateid").val();
  var template_name = $(".template_name").val();
  var template_description = $(".template_description").val();
  var template_status = $("#main_template_status").val();

  var arr = [];
  if (template_name == "") {
    arr.push("template_name_err");
    arr.push("Template Name required");
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
      url: "update_template",
      data: {
        id: template_id,
        template_name: template_name,
        description: template_description,
        status: template_status,
      },
      success: function (res) {
        if (res.status == "success") {
          $("#NewTemplateModal").modal("toggle");
          $("#templateid").val("");
          $(".template_name").val("");

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
          location.reload();
        } else if (res.status == "error") {
          $("#NewTemplateModal").modal("toggle");

          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".delete_template", function () {
  var main_template_id = $(this).data("main_template_id");
  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete this Template",
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
        url: "delete_template",
        data: {
          id: main_template_id,
        },
        success: function (res) {
          if (res.status == "success") {
            $("#alert").html(
              '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            );

            window.location.href = $("#base_url").val() + "/task";
          } else if (res.status == "error") {
            $("#alert").html(
              '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                res.msg +
                "</span></div></div>"
            );
          }
        },
        error: function (data) {
          console.log(data);
        },
      });
    }
  });
});

$(document).on("click", "#update_template_task_btn", function () {
  $(".valid_err").html("");
  var id = $("#task_id").val();
  var main_template_id = $("#template_id").val();
  var task_title = $(".task_title").val();
  var task_description = $(".ql-editor").html();
  var task_type = $(".task_type").val();
  var task_priority = $(".task_priority").val();
  var task_assignee = $(".task_assignee").val();
  var task_date = $(".task_date").val();
  var task_status = $(".task_status").val();
  var task_is_milestone = $(".task_is_milestone:checked").val();
  var file_link = $("#file_link").val();
  var task_file = $(".task_file")[0].files[0];
  if (task_file == undefined) {
    task_file = "";
  }
  if (task_is_milestone == undefined) {
    task_is_milestone = "";
  }
  if (task_description == "<p><br></p>") {
    task_description = "";
  }
  var arr = [];
  if (task_title == "") {
    arr.push("task_title_err");
    arr.push("Title required");
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
    var fd = new FormData();
    fd.append("id", id);
    fd.append("template_id", main_template_id);
    fd.append("title", task_title);
    fd.append("description", task_description);
    fd.append("priority", task_priority);
    fd.append("assignee", task_assignee);
    fd.append("task_date", task_date);
    fd.append("status", task_status);
    fd.append("type", task_type);
    fd.append("is_milestone", task_is_milestone);
    fd.append("file", task_file);
    fd.append("file_link", file_link);
    $.ajax({
      type: "post",
      url: "update_task_template",
      data: fd,
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        if (res.status == "success") {
          $(".todo-new-task-sidebar,.app-content-overlay").removeClass("show");
          $("#project_id").val("");
          $("#template_id").val("");
          $("#task_id").val("");
          $(".task_title").val("");
          $(".ql-editor").html("");
          $(".task_type").val("");
          $(".task_priority").val("");
          $(".task_assignee").val("");
          $(".task_date").val("");
          $(".task_status").val();
          $(".task_is_milestone").prop("checked", false);

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
          location.reload();
        } else if (res.status == "error") {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".update_main_template", function () {
  $("#NewTemplateModal").modal("toggle");
  $(".valid_err").html("");
  $(".template_btn").removeAttr("id");
  $(".modal_template_title").empty().html("Update Template");
  $(".template_btn").attr("id", "update_template");
  $(".template_btn_name").empty().html("Update");

  $("#form_template_status").show();
  $("#templateid").val($(this).data("main_template_id"));
  $(".template_name").val($(this).data("main_template_name"));
  $(".template_description").val($(this).data("main_template_description"));
  $("#main_template_status").val($(this).data("status"));
});

$(document).on("click", ".template_task", function () {
  $(".new_task_modal12,.app-content-overlay").addClass("show");
  $(".task_btn").removeAttr("id");
  $(".new-task-title").empty().html("Update Template Task");
  $(".task_btn").attr("id", "update_template_task_btn");
  $(".task_btn_name").empty().html("Update");

  $("#task_id").val($(this).data("task_template_id"));
  $(".task_title").val($(this).data("task_title"));
  $("#template_id").val($(this).data("main_template_id"));
  $(".task_type").val($(this).data("task_type"));
  $(".task_priority").val($(this).data("task_priority"));
  if ($(this).data("task_status") != "") {
    $(".task_status").val($(this).data("task_status"));
  } else {
    $(".task_status").val("1");
  }

  $("#file_link").val($(this).data("file_link"));
  $(".div_file_link").hide();
  $(".div_file_upload").show();
  if ($(this).data("file_link") != "") {
    $(".div_file_link").show();
    $(".div_file_upload").hide();
    var fileName = /[^/]*$/.exec($(this).data("file_link"));
    var split = fileName[0].split(".");
    fileName = split[0];
    var extension = split[1];
    if (fileName.length > 10) {
      fileName = fileName.substring(0, 10);
    }
    $(".file_link")
      .attr("href", $(this).data("file_link"))
      .attr("download", fileName + "." + extension)
      .attr("target", "_blank")
      .text(fileName + "." + extension);
  }
  if ($(this).data("task_is_milestone") == "yes") {
    $(".task_is_milestone").prop("checked", true);
  } else {
    $(".task_is_milestone").prop("checked", false);
  }

  $(".autoapply").daterangepicker({
    autoApply: true,
    locale: {
      format: "DD/MM/YYYY",
    },
  });

  $(".project_startdate_enddate,.task_date").val("");

  if ($(this).data("task_start_date") != "" || $(this).data("task_end_date")) {
    $(".task_date").val(
      $(this).data("task_start_date") + " - " + $(this).data("task_end_date")
    );

    $(".autoapply").daterangepicker(
      {
        showDropdowns: true,
        autoApply: true,
        locale: {
          format: "DD/MM/YYYY",
        },
        autoclose: true,
        alwaysShowCalendars: true,
        startDate: $(this).data("task_start_date"),
        endDate: $(this).data("task_end_date"),
      },
      function (start, end) {
        $("#start_date_input").val(start.format("DD/MM/YYYY"));
        $("#end_date_input").val(end.format("DD/MM/YYYY"));
      }
    );
  }
  var newTaskEditor = new Quill(".new_task_editor", {
    modules: {
      toolbar: ".new_task_quill_toolbar",
    },
    placeholder: "Add Description...",
    theme: "snow",
  });
  $(".new_task_editor > .ql-editor").html("");
  if ($(this).data("task_description") != "") {
    $(".new_task_editor > .ql-editor").html($(this).data("task_description"));
  }

  // if ($(this).data('task_description') != '') {
  //   $('.ql-editor').html($(this).data('task_description'));
  // }
  // Set selected
  console.log(12345);
  if ($(this).data("task_assignee") != "") {
    $(".task_assignee").val($(this).data("task_assignee"));
  } else {
    $(".task_assignee").val("");
  }
  $(".task_assignee")
    .select2({
      dropdownAutoWidth: true,
      width: "100%",
      placeholder: "Select Assignee",
    })
    .trigger("change");
  return false;
});

// Duplicate template task

$(document).on("click", ".create_task_from_template", function () {
  $(".valid_err").html("");
  $("#TemplateToTaskModal").modal("toggle");
  $("#modal_project_id").val($(this).data("project_id"));
  $(".main_template").removeClass("main_template_selected");
});

$(document).on("click", ".main_template", function () {
  $("#modal_main_template_id").val("");
  $(".main_template").removeClass("main_template_selected");
  $(this).addClass("main_template_selected");
  $("#modal_main_template_id").val($(this).data("main_template_id"));
});

$(document).on("click", "#submit_create_template_task", function () {
  $(".modal_err").html("");
  var modal_project_id = $("#modal_project_id").val();
  var modal_main_template_id = $("#modal_main_template_id").val();
  // modal_err
  if (modal_main_template_id == "") {
    var error_msg = "Please Select Template";
    $(".modal_err").html(
      '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
        error_msg +
        "</span></div></div>"
    );
  } else {
    $.ajax({
      type: "post",
      url: "duplicate_template",
      data: {
        project_id: modal_project_id,
        main_template_id: modal_main_template_id,
      },
      success: function (res) {
        if (res.status == "success") {
          $("#TemplateToTaskModal").modal("toggle");
          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        } else if (res.status == "error") {
          // $('#TemplateToTaskModal').modal('toggle');
          $(".modal_err").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});
// End Duplicate
//End Template Functions

//Subtask
$(document).on("click", ".add_subtask_btn", function (e) {
  $(".autoapply").daterangepicker({
    autoApply: true,
    locale: {
      format: "DD/MM/YYYY",
    },
  });
  $(".div_subtask_file_link").hide();
  $(".project_startdate_enddate,.subtask_date").val("");
  $("#sub_project_id").val($(this).data("project_id"));
  $("#_task_id").val($(this).data("task_id"));
  $("#sub_template_id").val($(this).data("template_id"));
  $("#taskTitle").empty().html($(this).data("task_title"));
  $(".valid_err").html("");
  $(".subtask_btn").removeAttr("id");
  $(".sub-task-title").empty().html("Create Sub Task");
  $(".subtask_btn").attr("id", "save_subtask_submit_btn");
  $(".subtask_btn_name").empty().html("Save");
  $(".subtask_title").val("");
  $(".subtask_editor > .ql-editor").html("");
  $(".subtask_type").val("");
  $(".subtask_priority").val("");
  $(".subtask_assignee").select2().val("").trigger("change");
  $(".subtask_date").val("");
  $(".subtask_status").val("");
  $(".subtask_is_milestone").prop("checked", false);
  $(".add_subtask_sidebar,.app-content-overlay").addClass("show");
});

//create subtask save data
$(document).on("click", "#save_subtask_submit_btn", function () {
  $(".valid_err").html("");
  var project_id = $("#sub_project_id").val();
  var _task_id = $("#_task_id").val();
  var template_id = $("#sub_template_id").val();
  var task_title = $(".subtask_title").val();
  var task_description = $(".subtask_editor > .ql-editor").html();
  var task_type = $(".subtask_type").val();
  var task_priority = $(".subtask_priority").val();
  var task_assignee = $(".subtask_assignee").val();
  var task_date = $(".subtask_date").val();
  var task_status = $(".subtask_status").val();
  var task_is_milestone = $(".subtask_is_milestone:checked").val();
  var task_file = $(".subtask_file")[0].files[0];

  if (task_is_milestone == undefined) {
    task_is_milestone = "";
  }
  if (task_file == undefined) {
    task_file = "";
  }
  if (task_description == "<p><br></p>") {
    task_description = "";
  }
  var fdata = new FormData();
  fdata.append("project_id", project_id);
  fdata.append("task_id", _task_id);
  fdata.append("template_id", template_id);
  fdata.append("title", task_title);
  fdata.append("description", task_description);
  fdata.append("type", task_type);
  fdata.append("priority", task_priority);
  fdata.append("assignee", task_assignee);
  fdata.append("task_date", task_date);
  fdata.append("status", task_status);
  fdata.append("is_milestone", task_is_milestone);
  fdata.append("file", task_file);

  var arr = [];
  if (task_title == "") {
    arr.push("subtask_title_err");
    arr.push("Title required");
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
      url: "add_subtask",
      data: fdata,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        // console.log(data);
        var res = JSON.parse(data);
        // console.log(res);
        if (res.status == "success") {
          $(".add_subtask_sidebar,.app-content-overlay").removeClass("show");
          $("#sub_project_id").val("");
          $("#_task_id").val("");
          $("#sub_template_id").val("");
          $(".subtask_title").val("");
          $(".subtask_editor > .ql-editor").html("");
          $(".subtask_type").val("");
          $(".subtask_priority").val("");
          $(".subtask_assignee").val("");
          $(".subtask_date").val("");
          $(".subtask_status").val("");
          $(".subtask_is_milestone").prop("checked", false);
          $(".subtask_file").val("");

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
          fetch_task_list();
          location.reload();
        } else if (res.status == "error") {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(document).on("click", ".subtask_update_click", function (e) {
  $(".add_subtask_sidebar,.app-content-overlay").addClass("show");
  $(".subtask_btn").removeAttr("id");
  $(".sub-task-title").empty().html("Update Sub Task");
  $(".subtask_btn").attr("id", "update_subtask_btn");
  $(".subtask_btn_name").empty().html("Update");

  $("#subtask_id").val($(this).data("id"));
  $("#taskTitle").html($(this).data("task_title"));
  $("#sub_project_id").val($(this).data("project_id"));
  subtask_id;
  $("#sub_template_id").val($(this).data("template_id"));
  $("#_task_id").val($(this).data("task_id"));
  $(".subtask_title").val($(this).data("title"));
  $(".subtask_type").val($(this).data("type"));
  $(".subtask_priority").val($(this).data("priority"));
  $(".subtask_status").val($(this).data("status"));

  $("#subtask_file_link").val($(this).data("subtask_file_link"));
  $(".div_subtask_file_link").hide();
  $(".div_subtask_file_upload").show();
  if ($(this).data("subtask_file_link") != "") {
    $(".div_subtask_file_link").show();
    $(".div_subtask_file_upload").hide();
    var fileName1 = /[^/]*$/.exec($(this).data("subtask_file_link"));
    var split1 = fileName1[0].split(".");
    fileName1 = split1[0];
    var extension1 = split1[1];
    if (fileName1.length > 10) {
      fileName1 = fileName1.substring(0, 10);
    }
    $(".subtask_file_link")
      .attr("href", $(this).data("subtask_file_link"))
      .attr("download", fileName1 + "." + extension1)
      .attr("target", "_blank")
      .text(fileName1 + "." + extension1);
  }

  if ($(this).data("subtask_is_milestone") == "yes") {
    $(".subtask_is_milestone").prop("checked", true);
  } else {
    $(".subtask_is_milestone").prop("checked", false);
  }

  $(".autoapply").daterangepicker({
    autoApply: true,
    locale: {
      format: "DD/MM/YYYY",
    },
  });

  $(".project_startdate_enddate,.subtask_date").val("");

  if ($(this).data("task_start_date") != "" || $(this).data("task_end_date")) {
    $(".subtask_date").val(
      $(this).data("task_start_date") + " - " + $(this).data("task_end_date")
    );

    $(".autoapply").daterangepicker(
      {
        showDropdowns: true,
        autoApply: true,
        locale: {
          format: "DD/MM/YYYY",
        },
        autoclose: true,
        alwaysShowCalendars: true,
        startDate: $(this).data("task_start_date"),
        endDate: $(this).data("task_end_date"),
      },
      function (start, end) {
        $("#start_date_input").val(start.format("DD/MM/YYYY"));
        $("#end_date_input").val(end.format("DD/MM/YYYY"));
      }
    );
  }
  var newTaskEditor = new Quill(".subtask_editor", {
    modules: {
      toolbar: ".subtask_quill_toolbar",
    },
    placeholder: "Add Description...",
    theme: "snow",
  });
  $(".subtask_editor > .ql-editor").html("");
  if ($(this).data("task_description") != "") {
    $(".subtask_editor > .ql-editor").html($(this).data("task_description"));
  }
  // Set selected
  $(".subtask_assignee").val($(this).data("assignee"));
  $(".subtask_assignee").select2().trigger("change");
});

$(document).on("click", "#update_subtask_btn", function () {
  $(".valid_err").html("");
  var id = $("#subtask_id").val();
  var task_id = $("#_task_id").val();
  var project_id = $("#sub_project_id").val();
  var template_id = $("#sub_template_id").val();
  var title = $(".subtask_title").val();
  var description = $(".subtask_editor > .ql-editor").html();
  var type = $(".subtask_type").val();
  var priority = $(".subtask_priority").val();
  var assignee = $(".subtask_assignee").val();
  var subtask_date = $(".subtask_date").val();
  var status = $(".subtask_status").val();
  var is_milestone = $(".subtask_is_milestone:checked").val();
  var subtask_file_link = $("#subtask_file_link").val();
  var subtask_task_file = $(".subtask_file")[0].files[0];
  if (subtask_task_file == undefined) {
    subtask_task_file = "";
  }
  if (is_milestone == undefined) {
    is_milestone = "";
  }
  if (description == "<p><br></p>") {
    description = "";
  }
  var arr = [];
  if (title == "") {
    arr.push("task_title_err");
    arr.push("Title required");
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
    var fd = new FormData();
    fd.append("id", id);
    fd.append("project_id", project_id);
    fd.append("task_id", task_id);
    fd.append("template_id", template_id);
    fd.append("title", title);
    fd.append("description", description);
    fd.append("type", type);
    fd.append("priority", priority);
    fd.append("assignee", assignee);
    fd.append("task_date", subtask_date);
    fd.append("status", status);
    fd.append("is_milestone", is_milestone);
    fd.append("file", subtask_task_file);
    fd.append("file_link", subtask_file_link);

    $.ajax({
      type: "post",
      url: "update_subtask",
      data: fd,
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        if (res.status == "success") {
          $(".add_subtask_sidebar,.app-content-overlay").removeClass("show");

          $("#subtask_id").val("");
          $("#_task_id").val("");
          $("#sub_project_id").val("");
          $("#sub_template_id").val("");
          $(".subtask_title").val("");
          $(".subtask_editor > .ql-editor").html("");
          $(".subtask_type").val("");
          $(".subtask_priority").val("");
          $(".subtask_assignee").val("");
          $(".subtask_date").val("");
          $(".subtask_status").val("");
          $(".subtask_is_milestone").prop("checked", false);

          fetch_task_list();

          $("#alert").html(
            '<div class="alert bg-rgba-success alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
          location.reload();
        } else if (res.status == "error") {
          $("#alert").html(
            '<div class="alert bg-rgba-danger alert-dismissible mx-5" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
              res.msg +
              "</span></div></div>"
          );
        }
      },
      error: function (data) {
        console.log(data);
      },
    });
  }
});

$(".subtask_cancel").click(function () {
  $(".add_subtask_sidebar,.app-content-overlay").removeClass("show");
});
//End Subtask

function fetch_projects_list() {
  $.ajax({
    type: "get",
    url: "projects_table",
    success: function (res) {
        console.log('fetch_projects_list');
      $(".project_list").empty().html(res);
         if ($(".table_config").length) {
          var dataListView = $(".table_config").DataTable({
            columnDefs: [{
                targets: 0,
                className: "control",
              },
              {
                // targets: [0, 1],
                targets: [6],
                orderable: false,
              },
            ],
            // order: [2, "asc"],
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"Bf><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',
            buttons: [
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5'
            ],
            language: {
              search: "",
              searchPlaceholder: "Search",
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

function fetch_task_list(search_task = "") {
  $.ajax({
    type: "post",
    url: "get_task_list",
    data: {
      search_task: search_task,
    },
    success: function (res) {
      $(".task_list").empty().html(res);
    },
    error: function (data) {
      console.log(data);
    },
  });
}

function fetch_client_project_list() {
  $.ajax({
    type: "post",
    url: "client_project_list",
    data: {},
    success: function (res) {
      $(".client_project_list").empty().html(res);

      // Client Project menu active
      var url2 = window.location.href.split("/");
      var client_link = url2[url2.length - 1];
      // console.log(client_link.split('-')[0]);
      //url match projects_task-12
      if ("projects_task" == client_link.split("-")[0]) {
        $(".client_project_link").each(function (i) {
          var href_1 = $(this).attr("href");
          // console.log(href_1);
          if (href_1.indexOf(client_link) !== -1) {
            $(".div_project" + $(this).data("client_id")).addClass("show");
            $(this).addClass("template_active");
          }
        });
      }
      //End Client Project menu active
    },
    error: function (data) {
      console.log(data);
    },
  });
}

function fetch_template_task_list() {
  $.ajax({
    type: "post",
    url: "template_task_list",
    data: {},
    success: function (res) {
      $(".template_task_list").empty().html(res);

      // template menu active show logic
      var url1 = window.location.href.split("/");
      var template_link = url1[url1.length - 1];
      if ("template_task" == template_link.split("-")[0]) {
        $("#list_template a").each(function (i) {
          var href = $(this).attr("href");
          if (href.indexOf(template_link) !== -1) {
            // console.log(href);
            $(this).addClass("template_active");
          }
        });
      }
      //End template menu active show logic
    },
    error: function (data) {
      console.log(data);
    },
  });
}

function search_task(value) {
  fetch_task_list(value);
}

function assignee_list() {
  $.ajax({
    type: "get",
    url: "assignee_list",
    data: {},
    success: function (res) {
      if (res.status == "success") {
        var innerhtml = "";
        for (let i = 0; i < res.data.length; i++) {
          innerhtml +=
            '<option value = "' +
            res.data[i].sid +
            '">' +
            res.data[i].name +
            " </option>";
          $(".staff_id").empty().append(innerhtml);
        }
      }
    },
    error: function (data) {
      console.log(data);
    },
  });

  $.ajax({
    type: "get",
    url: "assignee_list",
    data: {},
    success: function (res) {
      if (res.status == "success") {
        var innerhtml = "";
        for (let i = 0; i < res.data.length; i++) {
          innerhtml +=
            '<option value = "' +
            res.data[i].sid +
            '">' +
            res.data[i].name +
            " </option>";

          $(".task_assignee").empty().append(innerhtml);
        }
      }
    },
    error: function (data) {
      console.log(data);
    },
  });
}
$(document).on("click", ".file_delete", function () {
  $("#file_link").val("");
  $(".div_file_link").hide();
  $(".div_file_upload").show();
});
$(document).on("click", ".subtask_file_delete", function () {
  $("#subtaskfile_link").val("");
  $(".div_subtask_file_link").hide();
  $(".div_subtask_file_upload").show();
});
function fetch_template_list() {
  $.ajax({
    type: "get",
    url: "template_table",
    success: function (res) {
      $(".template_list").empty().html(res);
    },
    error: function (data) {
      console.log(data);
    },
  });
}

$.ajax({
  type: "get",
  url: "assignee_list",
  data: {},
  success: function (res) {
    if (res.status == "success") {
      var innerhtml = '<option value="">Assignee</option>';
      for (let i = 0; i < res.data.length; i++) {
        innerhtml +=
          '<option value = "' +
          res.data[i].sid +
          '">' +
          res.data[i].name +
          " </option>";
        $(".task_assignee").empty().append(innerhtml);
      }
    }
  },
  error: function (data) {
    console.log(data);
  },
});

$(document).on("click", ".file_delete", function () {
  $("#file_link").val("");
  $(".div_file_link").hide();
  $(".div_file_upload").show();
});
$(document).on("click", ".subtask_file_delete", function () {
  $("#subtaskfile_link").val("");
  $(".div_subtask_file_link").hide();
  $(".div_subtask_file_upload").show();
});

function MilestoneType() {
  console.log("inside function");
  $.ajax({
    type: "get",
    url: "get_milestone_type",
    success: function (data) {
      console.log(data);

      var primaryColour = "#D2E6FF;";
      var dangerColour = "#F3DCF6 ";
      var successColour = "#F6E8D2";
      var warningColour = "#B9EED3";
      var infoColour = "#E3E3E3";
      var normalColour = "#C0EEF9";
      var moneyColour = "#FFC8CE";
      var meetingColour = "#FFEAB2";

      var themeMilestoneColors = [
        primaryColour,
        dangerColour,
        successColour,
        warningColour,
        infoColour,
        normalColour,
        moneyColour,
        meetingColour,
      ];

      var blockerColour1 = "#3C597D";
      var blockerColour2 = "#6F4E73";
      var blockerColour3 = "#8D6F40";
      var blockerColour4 = "#3D7256";
      var blockerColour5 = "#666666";
      var blockerColour6 = "#42717C";
      var blockerColour7 = "#A0555E";
      var blockerColour8 = "#977928";

      var blockMilestoneColors = [
        blockerColour1,
        blockerColour2,
        blockerColour3,
        blockerColour4,
        blockerColour5,
        blockerColour6,
        blockerColour7,
        blockerColour8,
      ];

      var valueColor1 = "#102947";
      var valueColor2 = "#5D2A64";
      var valueColor3 = "#6B4A15";
      var valueColor4 = "#22543A";
      var valueColor5 = "#3D3D3D";
      var valueColor6 = "#254750";
      var valueColor7 = "#833E46";
      var valueColor8 = "#715200";

      var ValueMilestoneColors = [
        valueColor1,
        valueColor2,
        valueColor3,
        valueColor4,
        valueColor5,
        valueColor6,
        valueColor7,
        valueColor8,
      ];

      var res = JSON.parse(data);
      var mainBox = "";
      for (var i = 0; i < res.label.length; i++) {
        var typeName = res.label[i];
        var typeCount = res.data[i];
        var backgroundColors = themeMilestoneColors[i];
        var blockColors = blockMilestoneColors[i];
        var valueColours = ValueMilestoneColors[i];

        mainBox +=
          `<div class="MainBox" style="background-color: ` +
          backgroundColors +
          `;"><h4 style="color: ` +
          blockColors +
          `";>` +
          typeName +
          `</h4><h3 style="color: ` +
          valueColours +
          `;">` +
          typeCount +
          `</h3></div>`;
      }
      $(".milestoneContent").empty().html(mainBox);
    },
    error: function (data) {
      // console.log(data);
    },
  });
}

function fetch_template_list() {
  $.ajax({
    type: "get",
    url: "template_table",
    success: function (res) {
      $(".template_list").empty().html(res);
    },
    error: function (data) {
      console.log(data);
    },
  });
}

function doughnutChart(value) {
  if (value == undefined) {
    value = $("#doughnutChartMonth").val();
  }
  $.ajax({
    type: "get",
    url: "get_task_chart",
    datatype: "text/html",
    data: {
      month: value,
    },

    success: function (data) {
      console.log(data);
      var jsonData = JSON.parse(data);

      var $primary = "#5A8DEE",
        $success = "#39DA8A",
        $danger = "#FF5B5C",
        $warning = "#FDAC41",
        $info = "#00CFDD",
        $label_color = "#475F7B",
        $grid_line_color = "#B70404";

      var themeColors = [
        $primary,
        $success,
        $danger,
        $warning,
        $info,
        $label_color,
        $grid_line_color,
      ];
      var doughnutChartContent = document.getElementById(
        "doughnutChartContent"
      );
      doughnutChartContent.innerHTML = "&nbsp;";
      $("#doughnutChartContent").append(
        '<canvas id="simple-doughnut-chart" width="250" height="250"></canvas>'
      );
      var doughnutChartctx = $("#simple-doughnut-chart");
      console.log(doughnutChartctx);

      var doughnutChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration: 500,
        //  title: {
        //     display: true,
        //     text: 'Assigned Leads'
        //   }
      };

      var doughnutChartData = {
        labels: jsonData.label,
        datasets: [
          {
            data: jsonData.data,
            backgroundColor: themeColors,
            hoverBackgroundColor: ["#36A2EB", "#e0e0e0"],
          },
        ],
      };

      var doughnutChartconfig = {
        type: "doughnut",
        options: doughnutChartOptions,
        data: doughnutChartData,
      };

      var doughnutChartChart = new Chart(doughnutChartctx, doughnutChartconfig);
    },
    error: function (data) {
      // console.log(data);
    },
  });
}
