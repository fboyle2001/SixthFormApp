$(document).ready(function () {
  // Load current members
  loadMembers();

  // When the user clicks to change the group name, query the page and perform the callback
  $("#change_group_name").click(function (e) {
    e.preventDefault();
    $("#change_group_name").attr("disabled", "disabled");

    var name = $("#group_name").val();

    $.ajax({
      url: "/sixthadmin/announcements/groups/edit/change_name.php",
      type: "post",
      dataType: "json",
      data: {
        "id": getUrlParameter("id"),
        "name": name
      },
      success: function(data) {
        $("#change_group_name").removeAttr("disabled");
        groupNameSuccess(data);
      },
      error: function(data) {
        alert("An unexpected error occurred");
        console.log(data);
      }
    });
  });

  $("#user_search").click(function(e) {
    e.preventDefault();
    $("#user_search_").attr("disabled", "disabled");

    var name = $("#user_search_value").val();
    currentSearchTerm = name;

    $.ajax({
      url: "/sixthadmin/announcements/groups/search_user.php",
      type: "get",
      dataType: "json",
      data: {
        "user": name
      },
      success: function(data) {
        $("#change_group_name").removeAttr("disabled");
        processUserSearch(data);
      },
      error: function(data) {
        alert("An unexpected error occurred");
        console.log(data);
      }
    });
  })
});

var existingUsers = [];
var currentSearchTerm = null;

function groupNameSuccess(data) {
  $("#change_group_name_msg").text(data["status"]["description"]);

  if(data["status"]["code"] != 200) {
    return;
  }

  $("#head_gn").text(data["content"]["name"]);
}

function loadMembers() {
  // Load the members from URL then process the result
  $.ajax({
    url: "/sixthadmin/announcements/groups/edit/get_member_list.php",
    type: "post",
    dataType: "json",
    data: {
      "id": getUrlParameter("id")
    },
    success: processMembers,
    error: function(data) {
      alert("An unexpected error occurred");
      console.log(data);
    }
  });
}

function processMembers(data) {
  // Wasn't successful so tell the user why
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    return;
  }

  // Clear the table
  $("#member_table > tbody > tr").remove();

  // Make each user into a row and display them
  $.each(data["content"]["records"], function (index, item) {
    var username = item["Username"];
    existingUsers.push(username);
    var userId = item["ID"];

    var removeLink = '<a id="user_remove_' + userId + '" data-username="' + username + '" href="javascript:removeMember(' + userId + ')">Remove</a>';

    $("#member_table > tbody").append('<tr id="user_row_' + userId + '"><td>' + username + '</td><td>' + removeLink + '</td></tr>');
  });
}

function removeMember(id) {
  var username = $("#user_remove_" + id).data("username");
  // If it is the last member, inform the user that the group will then be deleted
  var rows = $("#member_table > tbody > tr").length;
  var deleteGroup = false;

  if(rows == 1) {
    var certain = confirm("WARNING: Deleting this final account will delete the group.");

    if(certain == false) {
      return;
    }

    deleteGroup = true;
  }

  // Request removal then process the result
  $.ajax({
    url: "/sixthadmin/announcements/groups/edit/remove_member.php",
    type: "post",
    dataType: "json",
    data: {
      "groupId": getUrlParameter("id"),
      "userId": id
    },
    success: function(data) {
      processRemoval(data, deleteGroup, username);
    },
    error: function(data) {
      alert("An unexpected error occurred");
      console.log(data);
    }
  });
}

function processRemoval(data, deleteGroup, username) {
  // Wasn't successful so tell the user why
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    return;
  }

  removeElementFromArray(existingUsers, username);

  // Delete the group after removing the user to prevent database errors
  if(deleteGroup == true) {
    var queryUrl = "/sixthadmin/announcements/groups/delete.php";

    // Perform the ajax request to remove the group then process the result
  	$.ajax({
  		url: queryUrl,
  		type: "post",
  		dataType: "json",
  		data: {
        "id": getUrlParameter("id")
      },
  		success: function(data) {
  			processDelete(data);
  		},
  		error: function(data) {
  			alert("An unexpected error occurred");
  			console.log(data);
  		}
  	});
  }

  var userId = data["content"]["id"];

  // Remove the user from the table
  $("#user_row_" + userId).remove();

  // Put the user back in the user search table if they meet the criteria
  if(username.toLowerCase().indexOf(currentSearchTerm) >= 0) {
    $("#add_member_table > tbody").append('<tr id="possible_' + userId + '"><td>' + username + '</td><td><button id="possible_button_' + userId + '" data-username="' + username + '" data-id="' + userId + '">Add To Group</button></td></tr>');
    $("#possible_button_" + userId).on("click", addToGroup);
  }
}

// After the group is deleted, redirect to the group view
function processDelete(data) {
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    return;
  }

  window.location.replace("/sixthadmin/announcements/groups/");
}

// Process the results of the search for a user
function processUserSearch(data) {
  // In case of error
  if(data["status"]["code"] != 200) {
    $("#search_err_msg").text(data["status"]["description"]);
    return;
  }

  // Clear the table
  $("#add_member_table > tbody > tr").remove();

  // Add each user to the table
  $.each(data["content"]["users"], function(index, item) {
    if(existingUsers.indexOf(item["Username"]) != -1) {
      return true;
    }

    // Store the necessary data in the data-<name> attributes and register a click handler
    $("#add_member_table > tbody").append('<tr id="possible_' + item["ID"] + '"><td>' + item["Username"] + '</td><td><button id="possible_button_' + item["ID"] + '" data-username="' + item["Username"] + '" data-id="' + item["ID"] + '">Add To Group</button></td></tr>');
    $("#possible_button_" + item["ID"]).on("click", addToGroup);
  });
}

// Add a new user to the group
function addToGroup(e) {
  e.preventDefault();

  var userId = $(this).data("id");
  var username = $(this).data("username");

  // If they are already in the group don't allow them to be double added
  if(existingUsers.indexOf(username) != -1) {
    alert("User is already in group");
    return;
  }

  $("#possible_" + userId).remove();

  // Query the page to add them to the group then process the result
  $.ajax({
    url: "/sixthadmin/announcements/groups/edit/add_member.php",
    type: "post",
    dataType: "json",
    data: {
      "groupId": getUrlParameter("id"),
      "userId": userId
    },
    success: function(data) {
      processAddToGroup(data, username);
    },
    error: function(data) {
      alert("An unexpected error occurred");
      console.log(data);
    }
  });
}

// Process the result of adding them to the group
function processAddToGroup(data, username) {
  // In case of error
  if(data["status"]["code"] != 200) {
    $("#search_err_msg").text(data["status"]["description"]);
    return;
  }

  // Add the user to the user list and append them to the table
  var userId = data["content"]["id"];
  var removeLink = '<a id="user_remove_' + userId + '" data-username="' + username + '" href="javascript:removeMember(' + userId + ')">Remove</a>';

  existingUsers.push(username);

  $("#member_table > tbody").append('<tr id="user_row_' + userId + '"><td>' + username + '</td><td>' + removeLink + '</td></tr>');
}
