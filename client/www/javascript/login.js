window.loggedIn = null;

function login(username, password, base, error, success) {
  var queryUrl = base + "/accounts/login/";
  $("#address").text(queryUrl);
  var postData = "username=" + username + "&password=" + password;

  $.post(queryUrl, postData, function(data, textStatus) {
    console.log("Data: " + data);
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
    $("#testing").text("Error: (" + code + ") " + msg)
  }, function success(data, base) {
    var auth = data["content"]["auth"];

    window.loggedIn = true;
    $("#testing").text("logged in");

    Cookies.set("auth", auth, {expires: 1/24});
    Cookies.set("base", base, {expires: 1/24});
    Cookies.set("resource_base", base.substr(0, base.lastIndexOf("/")), {expires: 1/24});
    Cookies.set("must_reset", data["content"]["reset"]);
  });

  $("#message").text("Logging in, please wait...");
  waitForLoginCompletion(250, 16, 0, start); //max wait 4 seconds
}

function waitForLoginCompletion(timePerPause, maxPauses, count, callback) {
  if(count >= maxPauses) {
    $("#message").text("Server timed out. Please try again.");
    return;
  }

  if(window.loggedIn !== null) {
    //console.log(count * timePerPause);
    callback();
  } else {
    setTimeout(function () {
      waitForLoginCompletion(timePerPause, maxPauses, count + 1, callback)
    }, timePerPause);
  }
}

function sendAlert(message, title = "Alert", button = "OK") {
	if(typeof navigator.notification !== "undefined") {
		navigator.notification.alert(message, null, title, button);
	} else {
		alert(message);
	}
}

//open the console and run 'window.user.query("/accounts/details/", {}, function(data) {console.log(data)}, null);' it will work.
