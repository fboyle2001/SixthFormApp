$(document).ready(function () {
  // Search for a user to add
  $("#search_member").click(function (e) {
    $("#search_member").attr("disabled", "disabled");
    var username = $("#username").val();
    var queryUrl = "/sixthadmin/announcements/groups/search_user.php?user=" + username;
    $.getJSON(queryUrl, processUserList);
  });

  // Create the group
  $("#submit").click(function (e) {
    e.preventDefault();
    $("#submit").attr("disabled", "disabled");
    submitGroup();
  })
});

// Users in the group
var existingUsers = [];

// Display the search results
function processUserList(data) {
  $("#search_member").removeAttr("disabled");

  if(data["status"]["code"] != 200) {
    $("#search_err_msg").text(data["status"]["description"]);
    return;
  }

  // Clear the table
  $("#possible_members > tbody > tr").remove();

  $.each(data["content"]["users"], function(index, item) {
    if(existingUsers.indexOf(item["Username"]) != -1) {
      return true;
    }

    // Repopulate the table
    $("#possible_members > tbody").append('<tr id="possible_' + item["ID"] + '"><td>' + item["Username"] + '</td><td><button id="possible_button_' + item["ID"] + '" data-username="' + item["Username"] + '" data-id="' + item["ID"] + '">Add To Group</button></td></tr>');
    $("#possible_button_" + item["ID"]).on("click", addToGroup);
  });
}

// Validates that the group name has not been taken
function verifyGroupName(name) {
  if(name == null || name == "") {
    $("#submit").removeAttr("disabled");
    return;
  }

  var queryUrl = "/sixthadmin/announcements/groups/verify_name.php?name=" + name;

  $.getJSON(queryUrl, function (data) {
    processGroupName(data, name);
  });
}

// If their group name is valid then add them to the database
function processGroupName(data, name) {
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    $("#submit").removeAttr("disabled");
    return;
  }

  addGroupToDatabase(name);
}

// Adds a new user to the group
function addToGroup(e) {
  e.preventDefault();

  var id = $(this).data("id");
  var username = $(this).data("username");

  $("#possible_" + id).remove();

  // Don't let them be duplicated
  if(existingUsers.indexOf(username) != -1) {
    alert(username + " is already a member of the group.");
    $("#add_member").removeAttr("disabled");
    return;
  }

  // Add a link to remove them
  existingUsers.push(username);
  var removeLink = "javascript:removeMember('" + username + "')";
  $("#members > tbody").append('<tr data-sid="' + id + '" id="row_' + username + '"><td>' + username + '</td><td><a class="removeLinks" href="' + removeLink + '">Remove</a></td></tr>');
  $("#add_member").removeAttr("disabled");
}

// Removes a user from the group
function removeMember(username) {
  if(existingUsers.indexOf(username) == -1) {
    return;
  }

  var id = $("#row_" + username).data("sid");

  // If they meet the search criteria. Add them back to the search list.
  if(username.toLowerCase().indexOf($("#username").val()) >= 0) {
    $("#possible_members > tbody").append('<tr id="possible_' + id + '"><td>' + username + '</td><td><button id="possible_button_' + id + '" data-username="' + username + '" data-id="' + id + '">Add To Group</button></td></tr>');
    $("#possible_button_" + id).on("click", addToGroup);
  }

  // Remove from table and list of users
  $("#row_" + username).remove();
  removeElementFromArray(existingUsers, username);
}

// Begins the process of submitting a group by verifying details
function submitGroup() {
  var groupName = $("#gname").val();

  if(groupName == null || groupName == "") {
    $("#submit").removeAttr("disabled");
    alert("You must fill in the group name first.");
    return;
  }

  verifyGroupName(groupName);
}

// Adds the group to the database
function addGroupToDatabase(groupName) {
  var ids = [];

  // Find each member
  $("#members > tbody > tr").each(function (index, row) {
    var sid = $(row).data("sid");
    ids.push(sid);
  });

  // Min of 2 users
  if(ids.length < 2) {
    $("#submit").removeAttr("disabled");
    alert("You must select at least two users to form a group.");
    return;
  }

  // Make the IDs transmittable
  var idStr = "";

  $(ids).each(function (index, id) {
    if(idStr != "") {
      idStr += ",";
    }

    idStr += id;
  });

  var queryUrl = "/sixthadmin/announcements/groups/submit_group.php?name=" + groupName + "&ids=" + idStr;

  $.getJSON(queryUrl, function(data) {
    processSubmitResult(data);
  });
}

// If it was successful disable everything and redirect them
function processSubmitResult(data) {
  if(data["status"]["code"] != 200) {
    $("#submit").removeAttr("disabled");
    alert(data["status"]["description"]);
    return;
  }

  $("#add_member").attr("disabled", "disabled");
  $(".removeLinks").removeAttr("href");
  $("#gname").attr("disabled", "disabled");
  $("#username").attr("disabled", "disabled");
  alert("Successfully created group!");
  window.location.replace("index.php");
  return;
}
