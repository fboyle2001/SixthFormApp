// Force all users to change their password
function forceChange() {
  var certain = confirm("Are you certain you want to force all users to change their passwords?");

  if(certain == false) {
    return;
  }

  var queryUrl = "/sixthadmin/accounts/control/admin_action.php";
  $("#fc").removeAttr("href");
  $("#fc").text("Applying...");

  $.ajax({
		url: queryUrl,
		type: "post",
		dataType: "json",
		data: "actionId=" + 1,
    success: function(data) {
      $("#fc").text(data["status"]["description"]);
    },
    error: function(data) {
      alert("An unexpected error occurred")
      console.log(data);
    }
  });
}

// Increment all student's year groups
function incrementYears() {
  var certain = confirm("Are you certain you want to increment the year group of all students?");

  if(certain == false) {
    return;
  }

  var queryUrl = "/sixthadmin/accounts/control/admin_action.php";
  $("#iy").removeAttr("href");
  $("#iy").text("Applying...");

  $.ajax({
		url: queryUrl,
		type: "post",
		dataType: "json",
		data: "actionId=" + 2,
    success: function(data) {
      $("#iy").text(data["status"]["description"]);
    },
    error: function(data) {
      alert("An unexpected error occurred")
      console.log(data);
    }
  });
}

// Delete all old students (e.g. year 14)
function deleteOld() {
  var certain = confirm("Are you certain you want to delete old students?");

  if(certain == false) {
    return;
  }

  var queryUrl = "/sixthadmin/accounts/control/admin_action.php";
  $("#do").removeAttr("href");
  $("#do").text("Applying...");

  $.ajax({
		url: queryUrl,
		type: "post",
		dataType: "json",
		data: "actionId=" + 3,
    success: function(data) {
      $("#do").text(data["status"]["description"]);
    },
    error: function(data) {
      alert("An unexpected error occurred")
      console.log(data);
    }
  });
}
