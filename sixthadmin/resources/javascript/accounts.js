$(document).ready(function () {
	search("");
	$("#search").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#usersearch").val();
		search(searchTerm);
	});
});

// Perform a search using the specified username
function search(username) {
	var queryUrl = "/sixthadmin/accounts/username_search.php?username=" + username;
	$.getJSON(queryUrl, function(data) {
		process(data);
	});
}

function process(result) {
	// Clear the table
	$("#account_table > tbody").remove();

	if(result["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(result["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

	// Rebuild the table
	$("#account_table").append("<tbody>");
	$.each(result["content"]["records"], function (index, item) {
		// Display their year
		var year = item["IsAdmin"] == 0 ? item["Year"] : "Admin";
		var rollback = "N/A";

		// Only allow rollbacks if year > 12 and are students
		if(item["IsAdmin"] == 0 && item["Year"] == 13 || item["IsAdmin"] == 0 && item["Year"] == 14) {
			rollback = '<a href="javascript:rollback(' + item["ID"] + ')">Rollback</a>';
		}

		$("#account_table > tbody").append('<tr><td>' + item["Username"] + '</td><td id="year_group_' + item["ID"] + '">' + year + '</td><td id="reset_' + item["ID"] + '"><a id="reset_link_' + item["ID"] + '" href="javascript:reset(' + item["ID"] + ')">Reset</a></td><td id="delete_' + item["ID"] + '"><a href="javascript:remove(' + item["ID"] + ')">Delete</a></td><td id="rollback_' + item["ID"] + '">' + rollback + '</td></tr>');
	});
	$("#account_table").append("</tbody>");
}

// Remove an account
function remove(id) {
  var certain = confirm("Are you sure you want to delete this account?");

  if(certain == false) {
    return;
  }

	$("#delete_link_" + id).remove();
	$("#delete_" + id).text("Deleting...");
	var queryUrl = "/sixthadmin/accounts/delete_account.php";

	$.ajax({
		url: queryUrl,
		type: "post",
		dataType: "json",
		data: "id=" + id,
		success: function(data) {
			processRemoveResult(data, id);
		},
		error: function(data) {
			alert("An unexpected error occurred");
			console.log(data);
		}
	});
}

function processRemoveResult(data, id) {
	var status = data["status"];
	$("#delete_" + id).text(status["description"]);
}

// Resets the password for an account
function reset(id) {
  var certain = confirm("Are you sure you want to reset the password for this account?");

  if(certain == false) {
    return;
  }

	$("#reset_link_" + id).remove();
	$("#reset_" + id).text("Resetting...");

	var queryUrl = "/sixthadmin/accounts/reset_password.php";
	$.ajax({
		url: queryUrl,
		type: "post",
		dataType: "json",
		data: "id=" + id,
		success: function(data) {
			processResetResult(data, id);
		},
		error: function(data) {
			alert("An unexpected error occurred.");
			console.log(data);
		}
	});
}

function processResetResult(data, id) {
	var status = data["status"];
	var code = status["code"];
	$("#reset_" + id).text(status["description"]);
}

// Rollbacks a student's year group (e.g. resitting)
function rollback(id) {
  var certain = confirm("Are you sure you want to rollback a year for this account?");

  if(certain == false) {
    return;
  }

	$("#rollback_link_" + id).remove();
	$("#rollback_" + id).text("Rolling back...");
	var queryUrl = "/sixthadmin/accounts/rollback_year_group.php";

	$.ajax({
		url: queryUrl,
		type: "post",
		dataType: "json",
		data: "id=" + id,
		success: function(data) {
			processRollbackResult(data, id);
		},
		error: function(data) {
			alert("An unexpected error occurred");
			console.log(data);
		}
	});
}

function processRollbackResult(data, id) {
	var status = data["status"];
	var code = status["code"];

	if(code == 200) {
		$("#year_group_" + id).text(data["content"]["new_year"]);
	}

	$("#rollback_" + id).text(status["description"]);
}
