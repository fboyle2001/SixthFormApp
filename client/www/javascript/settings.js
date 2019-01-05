// Same as force_change.js with variations as to what happens on success

// Register the click handler
function loadPage() {
  $("#change_pwd").click(function (e) {
    e.preventDefault();
    changePassword();
  });
}

// If the user wants to log out, remove all of the cookies and redirect to the login
function logout() {
  Cookies.remove("auth");
  Cookies.remove("base");
  Cookies.remove("expire");
  Cookies.remove("must_reset");
  window.location = "index.html";
}

// Changes the user's password
function changePassword() {
  var password = $("#new_pwd").val();
  var check = $("#new_pwd_check").val();

  // Passwords must match
  if(password !== check) {
    sendAlert("Your passwords do not match.");
    return;
  }

  // If it doesn't meet the minimum standard, don't allow them to change it
  var standard = doesPasswordMeetStandard(password);

  if(standard !== true) {
    sendAlert(standard);
    return;
  }

  // Process the result of the password change
  query("/accounts/change/", {"password": password}, function (data) {
    sendAlert("Successfully changed your password.");
  }, function(data) {
    sendAlert("An unexpected error occurred. Error Code S02", "Error");
  });
}
