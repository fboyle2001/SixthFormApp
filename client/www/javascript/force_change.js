// Caching is unnecessary on this page
// Registers a click handler
function loadPage() {
  $("#change_pwd").click(function (e) {
    e.preventDefault();
    changePassword();
  });
}

// Logout the user by removing all the cookies
function logout() {
  Cookies.remove("auth");
  Cookies.remove("base");
  Cookies.remove("expire");
  Cookies.remove("must_reset");
  window.location = "index.html";
}

function changePassword() {
  var password = $("#new_pwd").val();
  var check = $("#new_pwd_check").val();

  // Passwords must match
  if(password !== check) {
    sendAlert("Your passwords do not match.");
    return;
  }

  // Check it is secure enough
  var result = doesPasswordMeetStandard(password);

  if(result !== true) {
    sendAlert(result);
    return;
  }

  // Update the user's password and inform them of the result
  query("/accounts/change/", {"password": password}, function (data) {
    if(data["status"]["code"] == 200) {
      sendAlert("Successfully changed your password.");
      window.location = "home.html";
    } else {
      sendAlert(data["status"]["description"], "Error");
    }
  }, function(data) {
    sendAlert("An unexpected error occurred. Error Code FC01", "Error");
  });
}
