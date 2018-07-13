function forceChange() {
  var queryUrl = "http://localhost/sixthadmin/accounts/control/force_change.php";
  $("#fc").removeAttr("href");
  $("#fc").text("Applying...");

  $.getJSON(queryUrl, function(data) {
    $("#fc").text(data["status"]["description"]);
  });
}

function forceReset() {

}

function incrementYears() {

}

function deleteOld() {

}
