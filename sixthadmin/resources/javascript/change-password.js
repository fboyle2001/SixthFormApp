function doesPasswordMeetStandard(password) {
  if(password == null) {
    return "You must enter a password.";
  }

  if(password == "") {
    return "You must enter a password.";
  }

  if(password.length < 8) {
    return "Your password must be at least 8 characters long.";
  }

  if(/[0-9]+/.test(password) == false) {
    return "Your password must contain at least one number.";
  }

  if(/[a-z]+/.test(password) == false) {
    return "Your password must contain at least one lowercase letter.";
  }

  if(/[A-Z]+/.test(password) == false) {
    return "Your password must contain at least one uppercase letter.";
  }

  return true;
}

function doPasswordsMatch(first, second) {
  var password = $("#" + first).val();
  var check = $("#" + second).val();

  return password === check;
}

$(document).ready(function() {

  $("#password").on("change keyup", function() {
    var reached = doesPasswordMeetStandard($(this).val());

    if(reached === true) {
      $("#password_standard").text("Password meets standard");
    } else {
      $("#password_standard").text(reached);
      $("#submission").hide();
    }

    var match = doPasswordsMatch("password", "confirmation");

    if(match === true) {
      $("#confirm_status").text("Passwords match");

      if(doesPasswordMeetStandard($(this).val()) === true) {
        $("#submission").show();
      }
    } else {
      $("#confirm_status").text("Passwords do not match");
      $("#submission").hide();
    }
  });

  $("#confirmation").on("change keyup", function() {
    var match = doPasswordsMatch("password", "confirmation");

    if(match === true) {
      $("#confirm_status").text("Passwords match");

      if(doesPasswordMeetStandard($(this).val()) === true) {
        $("#submission").show();
      }
    } else {
      $("#confirm_status").text("Passwords do not match");
      $("#submission").hide();
    }
  });

});
