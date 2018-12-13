$(document).ready(function () {
  $("#add_member").click(function (e) {
    e.preventDefault();
    $("#add_member").attr("disabled", "disabled");
    var username = $("#username").val();
    verifyUser(username);
  });

  $("#submit").click(function (e) {
    e.preventDefault();
    $("#submit").attr("disabled", "disabled");
    submitGroup();
  })
});

var existingUsers = [];

function verifyUser(username) {
  if(username == null || username == "") {
    $("#add_member").removeAttr("disabled");
    return;
  }

  var queryUrl = "/sixthadmin/announcements/groups/verify_user.php?username=" + username;

  $.getJSON(queryUrl, function (data) {
    processResult(data, username);
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

function processResult(data, username) {
  $("#username").val("");

  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    $("#add_member").removeAttr("disabled");
    return;
  }

  if(existingUsers.indexOf(username) != -1) {
    alert(username + " is already a member of the group.");
    $("#add_member").removeAttr("disabled");
    return;
  }

  existingUsers.push(username);
  var removeLink = "javascript:removeMember('" + username + "')";
  $("#members > tbody").append('<tr data-sid="' + data["content"]["id"] + '" id="row_' + username + '"><td>' + username + '</td><td><a class="removeLinks" href="' + removeLink + '">Remove</a></td></tr>');
  $("#add_member").removeAttr("disabled");
}

function removeMember(username) {
  if(existingUsers.indexOf(username) == -1) {
    return;
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
  return;
}
