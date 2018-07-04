$(document).ready(function () {
	search("");
	$("#search").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#usersearch").val();
		var queryUrl = "http://localhost/sixthadmin/accounts/username_search.php?username=" + searchTerm;
		search(searchTerm);
	});
	
});

function search(username) {
	$.getJSON(queryUrl, function(data) {
		process(data);
	});
}

function process(result) {
	$("#account_table tr").remove();
	if(data["content"]["found"] == false) {
		
	} else {
		$("#account_table").append("<tr><th>ID</th><th>Username</th></tr>");
		$.each(data["content"]["records"], function (row) {
			$("#account_table").append('');
		});
	}
}