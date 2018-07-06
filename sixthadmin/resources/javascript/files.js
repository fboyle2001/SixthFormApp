$(document).ready(function () {
  nameSearch("");

	$("#search_by_name").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#namesearch").val();
		nameSearch(searchTerm);
	});

  $("#search_by_type").click(function (e) {
    e.preventDefault();
    var type = $("#typesearch").val();
    var searchTerm = $('#type [value="' + type + '"]').data("id");
    $("#typesearch").val("");

    if(searchTerm === undefined) {
      $("#message").text((type == "" ? "You must enter a search term when searching by type." : type + " is an invalid type to search for."));
      return;
    }

    typeSearch(searchTerm);
  });
});

function nameSearch(name) {
  var queryUrl = "http://localhost/sixthadmin/files/name_search.php?name=" + name;

  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

function typeSearch(type) {
  var queryUrl = "http://localhost/sixthadmin/files/type_search34.php?type=" + type;

  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

var types = {
  dict: {
    0: "Other",
    1: "Newsletter",
    2: "Notices"
  },
  resolve: function(type) {
    return this.dict[type] !== undefined ? this.dict[type] : "Other";
  }
};

function processData(result) {
  $("#files_table > tbody").remove();

  if(result["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(result["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

  $("#files_table").append("<tbody>");

  $.each(result["content"]["records"], function(index, item) {
    var addedDate = new Date(item["AddedDate"] * 1000);

		var timeDate = item["ExpiryDate"];
		var displayExpiryDate = "";

		if(timeDate == 2147483647) {
			displayExpiryDate = "Never";
		} else {
      var expiryDate = new Date(item["ExpiryDate"] * 1000);
      var displayExpiryDate = expiryDate.getDate() + "/" + (expiryDate.getMonth() + 1) + "/" + (expiryDate.getYear() + 1900) + " " + expiryDate.getHours() + ":" + expiryDate.getMinutes();
		}

    var displayAddedDate = addedDate.getDate() + "/" + (addedDate.getMonth() + 1) + "/" + (addedDate.getYear() + 1900) + " " + addedDate.getHours() + ":" + addedDate.getMinutes();
    var type = types.resolve(item["Type"]);

    $("#files_table > tbody").append('<tr><td>' + item["ID"] + '</td><td>' + item["Name"] + '</td><td>' + displayAddedDate + '</td><td>' + displayExpiryDate + '</td><td>' + type + '</td><td><a target="_blank" href="/sixthserver' + item["Link"] + '">Open</a></td><td id="delete_' + item["ID"] + '"><a id="delete_link_' + item["ID"] + '" href="javascript:remove(' + item["ID"] + ')">Delete</a></td></tr>');
  });

  $("#announcements_table").append("</tbody>");
}

function remove(id) {
  var certain = confirm("Are you sure you want to delete this?");

  if(certain == false) {
    return;
  }

  $("#delete_link_" + id).remove();
  $("#delete_" + id).text("Deleting...");
	var queryUrl = "http://localhost/sixthadmin/files/delete.php";

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
