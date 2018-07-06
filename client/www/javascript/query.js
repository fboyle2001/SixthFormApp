function isLoggedIn() {
  if(Cookies.get("auth") === undefined) {
    return false;
  }

  if(Cookies.get("base") === undefined) {
    return false;
  }

  return true;
}

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
	window.open(url, "_blank");
}
