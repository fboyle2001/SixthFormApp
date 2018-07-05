$(document).ready(function () {
	search("");
	$("#search").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#namesearch").val();
		search(searchTerm);
	});
});

function search(name) {
	var queryUrl = "http://localhost/sixthadmin/links/name_search.php?name=" + name;
	$.getJSON(queryUrl, function(data) {
		process(data);
	});
}

function process(result) {
	$("#links_table > tbody").remove();

  if(result["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(result["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

  $("#links_table").append("<tbody>");

  //translate date

  $.each(result["content"]["records"], function (index, item) {
    var date = new Date(item["ExpiryDate"] * 1000);
    console.log(date);
    var displayDate = date.getDate() + "/" + (date.getMonth() + 1) + "/" + (date.getYear() + 1900) + " " + date.getHours() + ":" + date.getMinutes();
    var link = decodeURIComponent(item["Link"]);
    $("#links_table").append('<tr><td>' + item["ID"] + '</td><td>' + item["Name"] + '</td><td>' + displayDate + '</td><td><a target="_blank" href="' + link + '">' + link + '</a></td><td>DELETE HERE</td></tr>');
  });

  $("#links_table").append("</tbody>");
}
