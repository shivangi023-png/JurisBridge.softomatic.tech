$(document).ready(function () {
 
 $('.datepicker').datepicker();
  // add class in row if checkbox checked

  $("#company").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select company",
  });
});
$(document).on("blur", ".emailid", function () {
  var mailformat =
    /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  var email = $(this).val();
  if (email != "") {
    if (mailformat.test(email) == false) {
      $(this).focus().css("border-color", "red");
      $(this)
        .closest(".form-label-group")
        .find(".emailid_err")
        .html("Invalid email id")
        .css("color", "red");
    } else {
      $(this).css("border-color", "");
      $(this).closest(".form-label-group").find(".email_err").html("");
      return true;
    }
  } else {
    $(this).css("border-color", "");
    $(this).closest(".form-label-group").find(".email_err").html("");
    return true;
  }
});

$(document).on("blur", ".mobile", function () {
  var phoneno = /^\d{10}$/;
  var mob_no = $(this).val();

  if (/^\d{10}$/.test(mob_no)) {
    $(this).css("border-color", "");
    $(this).closest(".form-label-group").find(".mobile_err").html("");
    return true;
  } else {
    $(this).focus().css("border-color", "red");
    $(this)
      .closest(".form-label-group")
      .find(".contact_err")
      .html("Invalid number; must be ten digits")
      .css("color", "red");
  }
});

$("#edit_img").click(function () {
  $(".img_link_div").hide();
  $(".img_file_div").show();
});

$("#cancel_img").click(function () {
  $(".img_link_div").show();
  $(".img_file_div").hide();
});

$("#edit_sign").click(function () {
  $(".sg_link_div").hide();
  $(".sg_file_div").show();
});

$("#cancel_sign").click(function () {
  $(".sg_link_div").show();
  $(".sg_file_div").hide();
});
