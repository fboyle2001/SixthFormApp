$(document).ready(function () {
  $("#search_member").click(function (e) {
    $("#search_member").attr("disabled", "disabled");
    var username = $("#username").val();
    var queryUrl = "/sixthadmin/announcements/groups/search_user.php?user=" + username;
    $.getJSON(queryUrl, processUserList);
  });

  $("#submit").click(function (e) {
    e.preventDefault();
    $("#submit").attr("disabled", "disabled");
    submitGroup();
  })
});

var existingUsers = [];

function processUserList(data) {
  $("#search_member").removeAttr("disabled");

  if(data["status"]["code"] != 200) {
    $("#search_err_msg").text(data["status"]["description"]);
    return;
  }

  $("#possible_members > tbody > tr").remove();

  $.each(data["content"]["users"], function(index, item) {
    if(existingUsers.indexOf(item["Username"]) != -1) {
      return true;
    }

    $("#possible_members > tbody").append('<tr id="possible_' + item["ID"] + '"><td>' + item["Username"] + '</td><td><button id="possible_button_' + item["ID"] + '" data-username="' + item["Username"] + '" data-id="' + item["ID"] + '">Add To Group</button></td></tr>');
    $("#possible_button_" + item["ID"]).on("click", addToGroup);
  });
}

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

function processGroupName(data, name) {
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    $("#submit").removeAttr("disabled");
    return;
  }

  addGroupToDatabase(name);
}

function addToGroup(e) {
  e.preventDefault();

  var id = $(this).data("id");
  var username = $(this).data("username");

  $("#possible_" + id).remove();

  if(existingUsers.indexOf(username) != -1) {
    alert(username + " is already a member of the group.");
    $("#add_member").removeAttr("disabled");
    return;
  }

  existingUsers.push(username);
  var removeLink = "javascript:removeMember('" + username + "')";
  $("#members > tbody").append('<tr data-sid="' + id + '" id="row_' + username + '"><td>' + username + '</td><td><a class="removeLinks" href="' + removeLink + '">Remove</a></td></tr>');
  $("#add_member").removeAttr("disabled");
}

function removeMember(username) {
  if(existingUsers.indexOf(username) == -1) {
    return;
  }

  var id = $("#row_" + username).data("sid");

  if(username.toLowerCase().indexOf($("#username").val()) >= 0) {
    //put it back in the search table
    $("#possible_members > tbody").append('<tr id="possible_' + id + '"><td>' + username + '</td><td><button id="possible_button_' + id + '" data-username="' + username + '" data-id="' + id + '">Add To Group</button></td></tr>');
    $("#possible_button_" + id).on("click", addToGroup);
  }

  $("#row_" + username).remove();
  removeElementFromArray(existingUsers, username);
}

function submitGroup() {
  var groupName = $("#gname").val();

  if(groupName == null || groupName == "") {
    $("#submit").removeAttr("disabled");
    alert("You must fill in the group name first.");
    return;
  }

  verifyGroupName(groupName);
}

function addGroupToDatabase(groupName) {
  var ids = [];

  $("#members > tbody > tr").each(function (index, row) {
    var sid = $(row).data("sid");
    ids.push(sid);
  });

  if(ids.length < 2) {
    $("#submit").removeAttr("disabled");
    alert("You must select at least two users to form a group.");
    return;
  }

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
