// When the document is ready register a click handler to return to login
$(document).ready(function() {
  $("#back").click(function (e) {
    e.preventDefault();
    window.location = "index.html";
  });
});

// Opens a link in the browser
function openInBrowser(url) {
  if(typeof cordova !== "undefined" && typeof cordova.InAppBrowser !== "undefined") {
    cordova.InAppBrowser.open(url, "_system");
  } else {
    window.open(url, "_blank");
  }
}
