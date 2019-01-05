$(document).ready(function() {
  if(Cookies.get("remember_username") !== "undefined") {
    $("#username").val(Cookies.get("remember_username"));
    $("#remember_me").prop("checked", true);
  }

  $("#login").click(function(e){
    e.preventDefault();
    $(this).prop("disabled", true);
    //var address = "http://localhost/sixthserver/api";
    //var address = "http://10.0.0.17/sixthserver/api";
    var address = "https://mcasixthfrom.000webhostapp.com/sixthserver/api";

    // Start the login process
    performLogin($("#username").val(), $("#password").val(), address, function(code, msg) {
      // Error occurred
      sendAlert(msg, "Error");

      $("#message").text(msg);
      $("#message").data("changed", 1);

      $("#login").prop("disabled", false);
    }, function() {
      // Clear the cache
      if(typeof(Storage) !== "undefined") {
        localStorage.clear();
      }

      // Remember their username if they ticked the box
      if($("#remember_me").is(":checked")) {
        Cookies.set("remember_username", $("#username").val(), {expires: 365});
      } else {
        Cookies.remove("remember_username");
      }

      // If they are being forced to reset their password, redirect them
      if(Cookies.get("must_reset") == "false") {
        window.location = "home.html";
      } else {
        window.location = "force_change.html";
      }
    });
  });
});

// Stores if the user is logged in
window.loggedIn = null;

// Performs the login process
function login(username, password, base, error, success) {
  var queryUrl = base + "/accounts/login/";
  var postData = "username=" + username + "&password=" + password;

  // Query the login page
  $.post(queryUrl, postData, function(data, textStatus) {
    var status = data["status"]["code"];

    if(status != 200) {
      error(status, data["status"]["description"]);
    } else {
      success(data, base);
    }

  }, "json");
}

function performLogin(username, password, base, onerror, start) {
  login(username, password, base,
  function error(code, msg) {
    onerror(code, msg);
  }, function success(data, base) {
    var auth = data["content"]["auth"];

    window.loggedIn = true;

    // Set the cookies to acknowledge that the user is logged in
    Cookies.set("auth", auth, {expires: 1/24});
    Cookies.set("base", base, {expires: 1/24});
    Cookies.set("must_reset", data["content"]["reset"]);
  });

  $("#message").text("Logging in, please wait...");
  $("#message").data("changed", 0);
  waitForLoginCompletion(250, 24, 0, start); //max wait 6 seconds
}

// Waits for a response from the server by timing out the process
function waitForLoginCompletion(timePerPause, maxPauses, count, callback) {
  if(count >= maxPauses) {
    if($("#message").data("changed") == 0) {
      $("#message").text("Server timed out. Please try again.");
    }

    return;
  }

  if(window.loggedIn !== null) {
    callback();
  } else {
    setTimeout(function () {
      waitForLoginCompletion(timePerPause, maxPauses, count + 1, callback)
    }, timePerPause);
  }
}

// Basic function to send alerts to the user in the form of pop ups
function sendAlert(message, title = "Alert", button = "OK") {
	if(typeof navigator.notification !== "undefined") {
		navigator.notification.alert(message, null, title, button);
	} else {
		alert(message);
	}
}
