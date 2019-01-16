$(document).ready(function () {
  nameSearch("");

  // Search by file name
	$("#search_by_name").click(function (e) {
		e.preventDefault();
		var searchTerm = $("#namesearch").val();
		nameSearch(searchTerm);
	});

  // Search for newsletters or notices
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
  var queryUrl = "/sixthadmin/files/name_search.php?name=" + name;

  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

function typeSearch(type) {
  var queryUrl = "/sixthadmin/files/type_search.php?type=" + type;

  $.getJSON(queryUrl, function (data) {
    processData(data);
  });
}

// Types class. Used to resolve type number to type name
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
  // Empty the table
  $("#files_table > tbody").remove();

  if(result["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(result["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

  // Refill the table
  $("#files_table").append("<tbody>");

  $.each(result["content"]["records"], function(index, item) {
    // Convert timestamps
    var addedDate = new Date(item["AddedDate"] * 1000);

		var timeDate = item["ExpiryDate"];
		var displayExpiryDate = "";

    // Max int ~2038 it will need changing
		if(timeDate == 2147483647) {
			displayExpiryDate = "Never";
		} else {
      var expiryDate = new Date(item["ExpiryDate"] * 1000);
      var displayExpiryDate = expiryDate.getDate() + "/" + (expiryDate.getMonth() + 1) + "/" + (expiryDate.getYear() + 1900);
		}

    // Resolve date and type
    var displayAddedDate = addedDate.getDate() + "/" + (addedDate.getMonth() + 1) + "/" + (addedDate.getYear() + 1900);
    var type = types.resolve(item["Type"]);

    // Add it to the table
    $("#files_table > tbody").append('<tr><td>' + item["Name"] + '</td><td>' + displayAddedDate + '</td><td>' + displayExpiryDate + '</td><td>' + type + '</td><td><a target="_blank" href="/sixthadmin/files/view.php?file=' + item["Link"] + '">Open</a></td><td id="delete_' + item["ID"] + '"><a id="delete_link_' + item["ID"] + '" href="javascript:remove(' + item["ID"] + ')">Delete</a></td></tr>');
  });

  $("#announcements_table").append("</tbody>");
}

// Deletes a file
function remove(id) {
  var certain = confirm("Are you sure you want to delete this?");

  if(certain == false) {
    return;
  }

  $("#delete_link_" + id).remove();
  $("#delete_" + id).text("Deleting...");
	var queryUrl = "/sixthadmin/files/delete.php";

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
