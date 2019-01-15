function loadPage() {
  // Fetch the content from the cache if possible
  // If the content is not loaded then load it from the server and cache it
  var noticeContent = retrieveContent("sixth-notice-list");
  var newsletterContent = retrieveContent("sixth-nwsl-list");
  var latestNoticeContent = retrieveContent("sixth-notice-late");
  var latestNewsletterContent = retrieveContent("sixth-nwsl-late");

  if(noticeContent == false || noticeContent == null) {
    query("/fetch/files/notices/list/", {validOnly: true}, function(data) {
      loadNotices(data);
    }, function(data) {
      sendAlert("An unexpected error occurred. Error Code: D01", "Error");
    });
  } else {
    loadNotices(JSON.parse(noticeContent));
  }

  if(newsletterContent == false || newsletterContent == null) {
    query("/fetch/files/newsletters/list/", {validOnly: true}, function(data) {
      loadNewsletters(data);
    }, function(data) {
      sendAlert("An unexpected error occurred. Error Code: D02", "Error");
    });
  } else {
    loadNewsletters(JSON.parse(newsletterContent));
  }

  if(latestNoticeContent == false || latestNoticeContent == null) {
    query("/fetch/files/notices/latest/", {}, function(data) {
      loadLatestNotice(data);
    }, function(data) {
      sendAlert("An unexpected error occurred. Error Code: D03", "Error");
    });
  } else {
    loadLatestNotice(JSON.parse(latestNoticeContent));
  }

  if(latestNewsletterContent == false || latestNewsletterContent == null) {
    query("/fetch/files/newsletters/latest/", {}, function(data) {
      loadLatestNewsletter(data);
    }, function(data) {
      sendAlert("An unexpected error occurred. Error Code: D04", "Error");
    });
  } else {
    loadLatestNewsletter(JSON.parse(latestNewsletterContent));
  }
}

// Process the result received from the server
function loadLatestNotice(data) {
  // Cache it
  cacheContent("sixth-notice-late", JSON.stringify(data));

  if(data["content"]["found"] == false) {
    $("#latest_notice_name").text("(None Available)");
    return;
  }

  // Display it with a link to the file
  var latest = data["content"]["latest"];

      var url = Cookies.get("base") + "/view/file/?file=" + latest["Link"] + "&auth=" + Cookies.get("auth");
      var link = "javascript:openFileInBrowser('" + url + "')";

  $("#latest_notice_link").attr("href", link);
}

function loadNotices(data) {
  // Cache it
  cacheContent("sixth-notice-list", JSON.stringify(data));

  if(data["content"]["found"] == false) {
    $("#older_notices_error").text("No notices found.");
    $("#notices_table").hide();
    return;
  }

  // Display each notice in the table
  $.each(data["content"]["records"], function(index, item){
    var expiryDate = item["ExpiryDate"];

    if(expiryDate == 2147483647) {
      expiryDate = "Never";
    } else {
      expiryDate = new Date(expiryDate * 1000);
      expiryDate = prependZero(expiryDate.getDate()) + "/" + prependZero(expiryDate.getMonth() + 1) + "/" + (expiryDate.getYear() + 1900);
    }

    var addedDate = new Date(item["AddedDate"] * 1000);
    addedDate = prependZero(addedDate.getDate()) + "/" + prependZero(addedDate.getMonth() + 1) + "/" + (addedDate.getYear() + 1900);

    var url = Cookies.get("base") + "/view/file/?file=" + item["Link"] + "&auth=" + Cookies.get("auth");
    var link = "javascript:openFileInBrowser('" + url + "')";

    $("#notices_table > tbody").append('<tr><td>' + item["Name"] + '</td><td>' + addedDate + '</td><td>' + expiryDate + '</td><td><a href="' + link + '"><button>Open</button></a></td></tr>');
  });
}

function loadLatestNewsletter(data) {
  // Cache it
  cacheContent("sixth-nwsl-late", JSON.stringify(data));

  if(data["content"]["found"] == false) {
    $("#latest_newsletter_name").text("None Available");
    return;
  }

  // Display the latest newsletter at the top

  var latest = data["content"]["latest"];
  $("#latest_newsletter_name").text(latest["Name"]);

      var url = Cookies.get("base") + "/view/file/?file=" + latest["Link"] + "&auth=" + Cookies.get("auth");
      var link = "javascript:openFileInBrowser('" + url + "')";

  $("#latest_newsletter_link").attr("href", link);
}

function loadNewsletters(data) {
  // Cache it
  cacheContent("sixth-nwsl-list", JSON.stringify(data));

  if(data["content"]["found"] == false) {
    $("#older_newsletters_error").text("No newsletters found.");
    $("#newsletter_table").hide();
    return;
  }

  // Display each record in the table
  $.each(data["content"]["records"], function(index, item){
    var expiryDate = item["ExpiryDate"];

    if(expiryDate == 2147483647) {
      expiryDate = "Never";
    } else {
      expiryDate = new Date(expiryDate * 1000);
      expiryDate = prependZero(expiryDate.getDate()) + "/" + prependZero(expiryDate.getMonth() + 1) + "/" + (expiryDate.getYear() + 1900);
    }

    var addedDate = new Date(item["AddedDate"] * 1000);
    addedDate = prependZero(addedDate.getDate()) + "/" + prependZero(addedDate.getMonth() + 1) + "/" + (addedDate.getYear() + 1900);

    var url = Cookies.get("base") + "/view/file/?file=" + item["Link"] + "&auth=" + Cookies.get("auth");
    var link = "javascript:openFileInBrowser('" + url + "')";

    $("#newsletter_table > tbody").append('<tr><td>' + item["Name"] + '</td><td>' + addedDate + '</td><td>' + expiryDate + '</td><td><a href="' + link + '"><button>Open</button></a></td></tr>');
  });
}
