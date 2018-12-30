$(document).ready(function () {
  searchByName("");

  $("#search_by_name").click(function (e) {
    e.preventDefault();
    searchByName($("#groupsearch").val());
  })
});

function searchByName(name) {
  var queryUrl = "/sixthadmin/announcements/groups/name_search.php?name=" + name;

  $.getJSON(queryUrl, function (data) {
    processResult(data);
  });
}

var usersByGroup = {};

function processResult(data) {
  $("#groups > tbody").remove();

  if(data["status"]["code"] != 200) {
		$("#message").text("An error occurred");
		return;
	}

	if(data["content"]["found"] == false) {
		$("#message").text("No data found");
		return;
	}

  $("#groups").append("<tbody>");

  $.each(data["content"]["records"], function(index, item) {
    var userDisplay = '<a id="' + item["ID"] + '_members" href="javascript:viewMembers(' + item["ID"] + ')">View Members</a>';
    var deleteDisplay = '<a id="' + item["ID"] + '_delete" href="javascript:deleteGroup(' + item["ID"] + ')">Delete</a>';

    if(item["ID"] < 0) {
      userDisplay = 'Default Group';
      deleteDisplay = 'Default Group';
    }

    if(usersByGroup[item["ID"]] != null) {
      var userDisplay = usersByGroup[item["ID"]];
    }

    $("#groups > tbody").append('<tr><td>' + item["GroupName"] + '</td><td>' + userDisplay + '</td><td>'  + deleteDisplay + '</td></tr>');
  });
}

function deleteGroup(id) {
  var certain = confirm("Are you sure you want to delete this group?");

  if(certain == false) {
    return;
  }

  $("#" + id + "_delete").removeAttr("href");
  $("#" + id + "_delete").text("Deleting...");
  $("#" + id + "_members").removeAttr("href");
  $("#" + id + "_members").text("Unavailable");

  var queryUrl = "/sixthadmin/announcements/groups/delete.php";

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
  $("#" + id + "_delete").text(status["description"]);
}

function viewMembers(id) {
  if(usersByGroup[id] != null) {
    return;
  }

  $("#" + id + "_members").removeAttr("href");
  $("#" + id + "_members").text("Retrieving...");

  var queryUrl = "/sixthadmin/announcements/groups/get_members.php?id=" + id;

  $.getJSON(queryUrl, function (data) {
    processMembers(data, id);
  });
}

function processMembers(data, id) {
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    return;
  }

  var users = "";

  $.each(data["content"]["records"], function (index, item) {
    if(users != "") {
      users += ", ";
    }

    users += item;
  });

  usersByGroup[id] = users;
  $("#" + id + "_members").text(users);
}
