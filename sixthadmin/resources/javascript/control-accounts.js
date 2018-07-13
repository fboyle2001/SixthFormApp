function forceChange() {
  var certain = confirm("Are you certain you want to force all users to change their passwords?");

  if(certain == false) {
    return;
  }

  var queryUrl = "http://localhost/sixthadmin/accounts/control/force_change.php";
  $("#fc").removeAttr("href");
  $("#fc").text("Applying...");

  $.getJSON(queryUrl, function(data) {
    $("#fc").text(data["status"]["description"]);
  });
}

function incrementYears() {

}

function deleteOld() {

}
