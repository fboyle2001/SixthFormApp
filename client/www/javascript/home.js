// Just register click handlers when the page is loaded
// Load announcements immediately as well
function loadPage() {
  initialLoad();

  $("#search").click(function (e) {
    e.preventDefault();
    var value = $("#contains").val();
    search(value);
  });

  $("#reset").click(function (e) {
    e.preventDefault();
    $("#contains").val("");
    search(null);
  })
}

// Fetches the announcements
function initialLoad() {
  var content = retrieveContent("sixth-announce");

  if(content == false || content == null) {
    search(null);
    return;
  }

  loadAnnouncements(JSON.parse(content));
}

// Searches the announcements based on the user's query
function search(parameter) {
  query("/fetch/announcements/list/", {"limit": 10, "contains": parameter}, function(data) {
    loadAnnouncements(data);
  }, function(data) {
    sendAlert("An unexpected error occurred. Error Code H01", "Error");
  });
}

// Load the announcements from the server, cache then display them.
function loadAnnouncements(data) {
  cacheContent("sixth-announce", JSON.stringify(data));
  $("#announcements").empty();
  $.each(data["content"]["records"], function(index, item){
    var date = new Date(item["DateAdded"] * 1000);
    var displayDate = date.getDate() + "/" + (date.getMonth() + 1) + "/" + (date.getYear() + 1900) + " " + date.getHours() + ":" + date.getMinutes();

    $("#announcements").append('<div class="announcement"><h2 class="title">' + item["Title"] + '</h2><p class="added">Added ' + displayDate + '. Sent to ' + item["GroupName"] + '.</p><p>' + item["Content"] + '</p></div><br>');
  });
}
