$(document).ready(function () {
	search("");
	$("#search").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#usersearch").val();
		search(searchTerm);
	});

});

function search(username) {
	var queryUrl = "http://localhost/sixthadmin/accounts/username_search.php?username=" + username;
	$.getJSON(queryUrl, function(data) {
		process(data);
	});
}

function process(result) {
	$("#account_table > tbody").remove();

	if(result["status"]["code"] != 200) {
		return;
	}

	if(result["content"]["found"] == false) {

	} else {
		$("#account_table").append("<tbody>");
		$.each(result["content"]["records"], function (index, item) {
			$("#account_table > tbody").append('<tr><td>' + item["ID"] + '</td><td>' + item["Username"] + '</td><td>' + (item["IsAdmin"] == 0 ? "No" : "Yes") + '</td><td id="reset_' + item["ID"] + '"><a id="reset_link_' + item["ID"] + '" href="javascript:reset(' + item["ID"] + ')">Reset</a></td><td>Fill</td></tr>');
		});
		$("#account_table").append("</tbody>");
	}
}

function reset(id) {
	//disable the button
	$("#reset_link_" + id).remove();
	$("#reset_" + id).text("Resetting...");
	console.log("removed");
	var queryUrl = "http://localhost/sixthadmin/accounts/reset_password.php";
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
		}
	});
}

function processResetResult(data, id) {
	var status = data["status"];
	var code = status["code"];
	$("#reset_" + id).text(status["description"]);
}
