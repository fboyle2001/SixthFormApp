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
});

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
    var userId = item["ID"];

    var removeLink = '<a href="javascript:removeMember(' + userId + ')">Remove</a>';

    $("#member_table > tbody").append('<tr id="user_row_' + userId + '"><td>' + username + '</td><td>' + removeLink + '</td></tr>');
  });
}

function removeMember(id) {
  // If it is the last member, inform the user that the group will then be deleted
  var rows = $("#member_table > tbody > tr").length;
  var deleteGroup = false;
  console.log(rows);

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
      processRemoval(data, deleteGroup);
    },
    error: function(data) {
      alert("An unexpected error occurred");
      console.log(data);
    }
  });
}

function processRemoval(data, deleteGroup) {
  // Wasn't successful so tell the user why
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    return;
  }

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

  // TODO: Add them to the add member search if they meet the criteria
}

function processDelete(data) {
  if(data["status"]["code"] != 200) {
    alert(data["status"]["description"]);
    return;
  }

  window.location.replace("/sixthadmin/announcements/groups/");
}
