$(document).ready(function () {
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
  })
});

function groupNameSuccess(data) {
  $("#change_group_name_msg").text(data["status"]["description"]);

  if(data["status"]["code"] != 200) {
    return;
  }

  $("#head_gn").text(data["content"]["name"]);
}
