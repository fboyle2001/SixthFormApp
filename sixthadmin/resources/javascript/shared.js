// Stores the content to be displayed in the dropdown navigation menus.
var dropdownContent = {
  'accounts': '<a href="/sixthadmin/accounts/"><li>View Accounts</li></a><a href="/sixthadmin/accounts/create.php"><li>Create Account</li></a><a href="/sixthadmin/accounts/control/index.php"><li>System Control</li></a>',
  'announcements': '<a href="/sixthadmin/announcements/"><li>View Announcements</li></a><a href="/sixthadmin/announcements/make.php"><li>Make Announcement</li></a><a href="/sixthadmin/announcements/groups/index.php"><li>View Groups</li></a><a href="/sixthadmin/announcements/groups/create.php"><li>Create Group</li></a>',
  'files': '<a href="/sixthadmin/files/"><li>View Files</li></a><a href="/sixthadmin/files/upload.php"><li>Upload File</li></a>',
  'links': '<a href="/sixthadmin/links/"><li>View Links</li></a><a href="/sixthadmin/links/add.php"><li>Add Link</li></a>'
};

// Waits until the document is loaded
$(document).ready(function () {
  // When the user hovers over an element with the attribute 'data-content-key'
  $("[data-content-key]").hover(function (e) {
    // Get the left offset of the menu item
    var left = $(this).offset()["left"];

    // Get the value of the data attribute
    var key = $(this).data("content-key");

    // If the key is not in the array, slide up the dropdown menu since it is not needed.
    if(!(key in dropdownContent)) {
      $("#subnav").slideUp();
    } else {
      // Move the dropdown to the correct position
      var style = "position: absolute; left: " + left + "px;"
      // Set the HTML content of the dropdown to the value associated with the key in the array.
      $("#subnav").html('<nav style="' + style + '"><ul>' + dropdownContent[key] + '</ul></nav>');
      // Slide down the navigation bar.
      $("#subnav").slideDown();
    }
  });

  $("#subnav").mouseleave(function () {
    $("#subnav").slideUp();
  });


});

function removeElementFromArray(array, element) {
  var index = array.indexOf(element);

  if(index == -1) {
    return;
  }

  array.splice(index, 1);
}
