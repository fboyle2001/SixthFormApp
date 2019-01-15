function loadPage() {
  // Try to load the content from cache, otherwise get it from the server
  var content = retrieveContent("sixth-links");

  if(content == false || content == null) {
    query("/fetch/links/list/", {"validOnly": true}, function(data) {
      loadLinks(data);
    }, function(data) {
      sendAlert("An unexpected error occurred. Error Code L01", "Error");
    });

    return;
  }

  loadLinks(JSON.parse(content));
}

// Displays the data returned from the cache or server
function loadLinks(data) {
  cacheContent("sixth-links", JSON.stringify(data));
  $("#ltl").empty();
  $("#perm").empty();
  $.each(data["content"]["records"], function(index, item){
    var storedDate = item["ExpiryDate"];
    var link = "javascript:openInBrowser('" + item["Link"] + "')";

    // Max int = No expiry (works until ~2038)
    if(storedDate == 2147483647) {
      $("#perm").append('<p><a href="' + link + '">' + item["Name"] + '</a></p>');
    } else {
      // Convert the unix timestamp to a date
      var date = new Date(storedDate * 1000);
      var displayDate = prependZero(date.getDate()) + "/" + prependZero(date.getMonth() + 1) + "/" + (date.getYear() + 1900);
      $("#ltl").append('<p><a href="' + link + '">' + item["Name"] + '</a> (expires on ' + displayDate + ')</p>');
    }
  });
}
