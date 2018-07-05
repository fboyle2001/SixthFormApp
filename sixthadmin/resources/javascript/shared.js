// Stores the content to be displayed in the dropdown navigation menus.
var dropdownContent = {
  'accounts': '<a href="/sixthadmin/accounts/"><li>View Accounts</li></a><a href="/sixthadmin/accounts/create.php"><li>Create Account</li></a>',
  'announcements': '<a href="/sixthadmin/announce/"><li>View Announcements</li></a><a href="/sixthadmin/announce/make.php"><li>Make Announcement</li></a><a href="/sixthadmin/announce/remove.php"><li>Remove Announcement</li></a>',
  'calendar': '<a href="/sixthadmin/calendar/"><li>View Calendar</li></a><a href="/sixthadmin/calendar/add.php"><li>Add Event</li></a><a href="/sixthadmin/calendar/remove.php"><li>Remove Event</li></a>',
  'files': '<a href="/sixthadmin/files/"><li>View Files</li></a><a href="/sixthadmin/files/upload.php"><li>Upload File</li></a><a href="/sixthadmin/files/delete.php"><li>Remove File</li></a>',
  'links': '<a href="/sixthadmin/links/"><li>View Links</li></a><a href="/sixthadmin/links/add.php"><li>Add Link</li></a><a href="/sixthadmin/links/remove.php"><li>Remove Link</li></a>'
};

// Waits until the document is loaded
$(document).ready(function () {
  // When the user hovers over an element with the attribute 'data-content-key'
  $("[data-content-key]").hover(function () {
    // Get the value of the data attribute
    var key = $(this).data("content-key");

    // If the key is not in the array, slide up the dropdown menu since it is not needed.
    if(!(key in dropdownContent)) {
      $("#subnav").slideUp();
    } else {
      // Set the HTML content of the dropdown to the value associated with the key in the array.
      $("#subnav").html("<nav><ul>" + dropdownContent[key] + "</ul></nav>");
      // Slide down the navigation bar.
      $("#subnav").slideDown();
    }
  });
});
