// Checks if a password meets the minimum standard
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

// Check if passwords match
function doPasswordsMatch(first, second) {
  var password = $("#" + first).val();
  var check = $("#" + second).val();

  return password === check;
}

$(document).ready(function() {
  // When the user types
  $("#password").on("change keyup", function() {
    var reached = doesPasswordMeetStandard($(this).val());

    // Does the password meet the standard
    if(reached === true) {
      $("#password_standard").text("Password meets standard");
    } else {
      $("#password_standard").text(reached);
      $("#submission").hide();
    }

    var match = doPasswordsMatch("password", "confirmation");

    // Does the password match the confirmation
    if(match === true) {
      $("#confirm_status").text("Passwords match");

      // If both true then let them submit it
      if(reached === true) {
        $("#submission").show();
      }
    } else {
      $("#confirm_status").text("Passwords do not match");
      $("#submission").hide();
    }
  });

  // Similar to above but with the confirmation field
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
