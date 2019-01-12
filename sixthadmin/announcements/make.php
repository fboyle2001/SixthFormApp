<?php
  require("../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

	$selectGroups = Database::get()->query("SELECT * FROM `groups`");
	$options = "";

	if($selectGroups == true) {
		while($record = $selectGroups->fetch(PDO::FETCH_ASSOC)) {
			$options .= '<option value="' . $record["ID"] . '">' . $record["GroupName"] . '</option>';
		}
	}

  $message = "";

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = post("title");
    $content = post("content");
		$group = post("group");

    if($title == null) {
      $message = "Title cannot be empty.";
    } else if ($content == null) {
      $message = "Content cannot be empty.";
    } else {
			if($group == null) {
				$group = -999;
			}

      $date = time();
      $insertQuery = Database::get()->prepare("INSERT INTO `announcements` (`Title`, `Content`, `DateAdded`, `GroupID`) VALUES (:title, :content, :dateAdded, :group)");
      $insertQuery->execute(["title" => $title, "content" => $content, "dateAdded" => $date, "group" => $group]);

      if($insertQuery == true) {
        $message = "Made announcement with title $title.";
        $trimmedTitle = strlen($title) > 97 ? substr($title, 0, 97) . '...' : $title;

        // Made an announcement so push it
        sendNotification("Announcement", $trimmedTitle);
      } else {
        $message = "Unable to make announcement at this time, please try again later.";
      }
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
			// Includes the default header which includes the stylesheet and navigation JavaScript
			// It also includes jQuery in the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/head.php");
		?>
		<title>Make Announcement</title>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <div>
      <h1>Make Announcement</h1>
      <p>To make an announcement, simply fill in the title and content box.</p>
      <p>The content box can be expanded by dragging in the bottom right of the box.</p>
      <br>
      <form method="POST" id="announcement_form">
          <table>
            <tr><td>Title:</td><td><input type="text" name="title" required></td></tr>
            <tr><td>Content:</td><td><textarea rows="8" name="content" cols="60" required form="announcement_form"></textarea></td></tr>
						<tr>
							<td>Group:</td>
							<td>
								<select form="announcement_form" id="group_list" name="group">
									<?php echo $options; ?>
								</select>
							</td>
						</tr>
            <tr><td colspan="2"><input type="submit" value="Make Announcement"></td></tr>
          </table>
      </form>
      <p><?php echo $message; ?></p>
    </div>
  </body>
</html>
