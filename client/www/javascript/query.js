function query(url, postData, callback, fatal) {
  if(!isLoggedIn()) {
    var response = {
      "status": {
        "code": 401,
        "description": "User is not logged in"
      },
      "content": {}
    };

    fatal(response);
    return;
  }

  $.ajax({
    url: Cookies.get("base") + url,
    type: "post",
    dataType: "json",
    data: postData,
    headers: {
      authorization: Cookies.get("auth")
    },
    success: function(data) {
      callback(data);
    },
    error: function(data) {
      fatal(data);
    }
  });
}

function openInBrowser(url) {
	cordova.InAppBrowser.open(url, "_system", "location=yes");
}

function isLoggedIn() {
  if(Cookies.get("auth") === undefined) {
    return false;
  }

  if(Cookies.get("base") === undefined) {
    return false;
  }

  if(Cookies.get("resource_base") === undefined) {
    return false;
  }

  return true;
}

function verifyUser(start, fatal) {
  if(Cookies.get("auth") === undefined) {
    return fatal("You are not logged in.");
  }

  if(Cookies.get("base") === undefined) {
    return fatal("You are not logged in.");
  }

  if(Cookies.get("resource_base") === undefined) {
    return fatal("You are not logged in.");
  }

  query("/accounts/details/", {}, function (data) {
    var code = data["status"]["code"];

    if(code == 200) {
      start();
    } else {
      fatal("Session has expired.");
    }
  }, function (data) {
    fatal("Unable to locate server.");
  });
}

function onDeviceReady() {
  console.log("ready");
  $(document).ready(function () {
    loadPage();
  });
}

function onResume() {
  console.log("resuming");
  verifyUser(function () {
    return;
  }, function (err) {
    window.location = "index.html";
  });
}

document.addEventListener("resume", onResume, false);
document.addEventListener("deviceready", onDeviceReady, false);
