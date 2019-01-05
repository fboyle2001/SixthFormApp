$(document).ready(function () {
  // When the elements are ready, load the list of groups.
  searchByName("");

  // When the user clicks the search button, call the search function.
  $("#search_by_name").click(function (e) {
    e.preventDefault();
    searchByName($("#groupsearch").val());
  })
});

// Searches for a group by its name. Takes a single string argument.
function searchByName(name) {
  var queryUrl = "/sixthadmin/announcements/groups/name_search.php?name=" + name;

  // Fetches the JSON from the URL and then calls another function to process the result
  $.getJSON(queryUrl, processResult);
}

// Stores users in each group when they are requested
var usersByGroup = {};

// Process the search results
function processResult(data) {
  // Clear the table
  $("#groups > tbody").remove();

  // If the request was unsuccessful display an error.
  if(data["status"]["code"] != 200) {
		$("#message").text(data["status"]["description"]);
		return;
	}

	if(data["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

  $("#groups").append("<tbody>");

  // Rebuild the table
  $.each(data["content"]["records"], function(index, item) {
    // Links to corresponding functions for on click actions
    var userDisplay = '<a id="' + item["ID"] + '_members" href="javascript:viewMembers(' + item["ID"] + ')">View Members</a>';
    var deleteDisplay = '<a id="' + item["ID"] + '_delete" href="javascript:deleteGroup(' + item["ID"] + ')">Delete</a>';
    var editDisplay = '<a id="' + item["ID"] + '_edit" href="edit/?id=' + item["ID"] + '">Edit</a>';

    // Defaults group have negative ids
    if(item["ID"] < 0) {
      userDisplay = 'Default';
      deleteDisplay = 'Default';
      editDisplay = 'Default';
    }

    // If the group's users have already been requested then display them
    if(usersByGroup[item["ID"]] != null) {
      var userDisplay = usersByGroup[item["ID"]];
    }

    // Put the row in the table
    $("#groups > tbody").append('<tr><td>' + item["GroupName"] + '</td><td>' + userDisplay + '</td><td>'  + deleteDisplay + '</td><td>' + editDisplay + '</td></tr>');
  });
}

// Deletes a group based on the id
function deleteGroup(id) {
  // Checks if the action was intentional as it is irreversible
  var certain = confirm("Are you sure you want to delete this group?");

  if(certain == false) {
    return;
  }

  // Remove other actions to prevent button spam
  $("#" + id + "_delete").removeAttr("href");
  $("#" + id + "_delete").text("Deleting...");
  $("#" + id + "_members").removeAttr("href");
  $("#" + id + "_members").text("Unavailable");
  $("#" + id + "_edit").removeAttr("href");
  $("#" + id + "_edit").text("Unavailable");

  var queryUrl = "/sixthadmin/announcements/groups/delete.php";

  // Perform the ajax request to remove the group then process the result
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

// Function informs the user that the group has been removed
function processRemoveResult(data, id) {
  var status = data["status"];
  $("#" + id + "_delete").text(status["description"]);
}

// Corresponds to the view members action for each group
function viewMembers(id) {
  // If we've already fetched the group don't bother doing it again
  if(usersByGroup[id] != null) {
    return;
  }

  // Show updates as to what is happening
  $("#" + id + "_members").removeAttr("href");
  $("#" + id + "_members").text("Retrieving...");

  var queryUrl = "/sixthadmin/announcements/groups/get_members.php?id=" + id;

  // Process the result
  $.getJSON(queryUrl, function (data) {
    processMembers(data, id);
  });
}

// Processes request for members
function processMembers(data, id) {
  // Wasn't successful so tell the user why
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    return;
  }

  var users = "";

  // Generate a comma-seperated list of users
  $.each(data["content"]["records"], function (index, item) {
    if(users != "") {
      users += ", ";
    }

    users += item;
  });

  // Cache and display
  usersByGroup[id] = users;
  $("#" + id + "_members").text(users);
}
