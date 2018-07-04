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
			$("#account_table > tbody").append('<tr><td>' + item["ID"] + '</td><td>' + item["Username"] + '</td><td>' + (item["IsAdmin"] == 0 ? "No" : "Yes") + '</td><td>Fill</td><td>Fill</td></tr>');
		});
		$("#account_table").append("</tbody>");
	}
}
