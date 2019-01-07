// Same as force_change.js with variations as to what happens on success

// Register the click handlers
function loadPage() {
  setThemeRadio();
  setZoomCheck();

  $("#change_pwd").click(function (e) {
    e.preventDefault();
    changePassword();
  });

  $("#clear_cache").click(function(e) {
    e.preventDefault();

    // Get the next time they can clear the cache and the current time
    var next = Cookies.get("next_clear");
    var currentTime = Math.floor(Date.now() / 1000);

    // If it's the first time let them do it
    if(next === undefined) {
      clearStorage();
      return;
    }

    // If they still have time remaining tell them
    if(next > currentTime) {
      sendAlert("You can only clear the cache once every 2 minutes.");
      return;
    }

    // Clear the cache
    Cookies.set("next_clear", currentTime + 120);
    clearStorage();
    sendAlert("Cache cleared.");
  });

  $("#logout").click(function (e) {
    e.preventDefault();
    logout();
  });

  $("input[name=stylesheet_select]").change(function() {
    var value = $("input[name=stylesheet_select]:checked").val();
    Cookies.set("stylesheet", value, {expires: 365});
    window.location = "settings.html";
  });

  $("input[name=zoom_enabled]").change(function () {
    var value = this.checked;

    if(value == "true" || value === true) {
      value = "yes";
    } else {
      value = "no";
    }

    Cookies.set("zoom", value, {expires: 365});
    window.location = "settings.html";
  })
}

// Set the default radio button based on what the user already has selected
function setThemeRadio() {
  var currentStyle = Cookies.get("stylesheet");

  if(currentStyle === undefined) {
    currentStyle = "light.css";
  }

  $("input[name=stylesheet_select][value='" + currentStyle + "']").prop("checked", true);
}

// Set the default zoom checkbox value based on user's current selection
function setZoomCheck() {
  var zoom = Cookies.get("zoom");

  if(zoom === undefined) {
    zoom = true;
  }

  if(zoom == "yes") {
    $("input[name=zoom_enabled]").prop("checked", true);
  }
}

// If the user wants to log out, remove all of the cookies and redirect to the login
function logout() {
  Cookies.remove("auth");
  Cookies.remove("base");
  Cookies.remove("expire");
  Cookies.remove("must_reset");
  Cookies.remove("last_clear");
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
