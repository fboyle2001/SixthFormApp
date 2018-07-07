window.user = null;

function login(username, password, base, error, success) {
  var queryUrl = base + "/accounts/login/";
  console.log(queryUrl);
  var postData = "username=" + username + "&password=" + password;

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
    console.log(Cookies.get("auth"));
    window.user = {
      auth: auth,
      base: base
    };

    Cookies.set("auth", auth);
    Cookies.set("base", base);
    Cookies.set("resource_base", base.substr(0, base.lastIndexOf("/")));
  });

  waitForLoginCompletion(250, 8, 0, start); //max wait 2 seconds
}

function waitForLoginCompletion(timePerPause, maxPauses, count, callback) {
  if(count >= maxPauses) {
    console.log("here");
    return;
  }

  if(window.user !== null) {
    //console.log(count * timePerPause);
    callback();
  } else {
    setTimeout(function () {
      waitForLoginCompletion(timePerPause, maxPauses, count + 1, callback)
    }, timePerPause);
  }
}

//open the console and run 'window.user.query("/accounts/details/", {}, function(data) {console.log(data)}, null);' it will work.
