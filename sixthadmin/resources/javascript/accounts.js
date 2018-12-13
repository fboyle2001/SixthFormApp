$(document).ready(function () {
	search("");
	$("#search").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#usersearch").val();
		search(searchTerm);
	});
});

function search(username) {
	var queryUrl = "/sixthadmin/accounts/username_search.php?username=" + username;
	$.getJSON(queryUrl, function(data) {
		process(data);
	});
}

function process(result) {
	$("#account_table > tbody").remove();

	if(result["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(result["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

	$("#account_table").append("<tbody>");
	$.each(result["content"]["records"], function (index, item) {
		$("#account_table > tbody").append('<tr><td>' + item["ID"] + '</td><td>' + item["Username"] + '</td><td>' + (item["IsAdmin"] == 0 ? "No" : "Yes") + '</td><td id="reset_' + item["ID"] + '"><a id="reset_link_' + item["ID"] + '" href="javascript:reset(' + item["ID"] + ')">Reset</a></td><td id="delete_' + item["ID"] + '"><a href="javascript:remove(' + item["ID"] + ')">Delete</a></td></tr>');
	});
	$("#account_table").append("</tbody>");
}

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
