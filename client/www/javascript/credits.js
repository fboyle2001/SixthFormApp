// When the document is ready register a click handler to return to login
$(document).ready(function() {
  $("#back").click(function (e) {
    e.preventDefault();
    window.location = "index.html";
  });
});
