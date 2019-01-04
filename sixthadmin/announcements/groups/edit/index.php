<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require("../../../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

  // Get the ID from the URL
  $id = get("id");

  // If none is set then send them back to group view page
  if($id == null) {
    header("Location: index.php");
    die();
  }

  // Validate the ID as it is in the URL

  $checkId = Database::get()->prepare("SELECT * FROM `groups` WHERE `ID` = :id");
  $checkId->execute(["id" => $id]);

  // No group exists with that id so redirect them
  if($checkId->rowCount() != 1) {
    header("Location: index.php");
    die();
  }

  // Get the returned row data
  $groupDetails = $checkId->fetch(PDO::FETCH_ASSOC);
  // Get the users of the group
  $userQuery = Database::get()->prepare("SELECT `accounts`.`Username` FROM `grouplink` INNER JOIN `accounts` ON `grouplink`.`AccountID` = `accounts`.`ID` WHERE `grouplink`.`GroupID` = :id");
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
			// Includes the default header which includes the stylesheet and navigation JavaScript
			// It also includes jQuery in the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/head.php");
		?>
    <script src="/sixthadmin/resources/javascript/edit-group.js"></script>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <span id="group_id" class="hidden"><?php echo $groupDetails["ID"]?></span>
    <div>
      <h1>Editing Group: <span id="head_gn"><?php echo $groupDetails["GroupName"] ?></span></h1>
      <br>
      <h2>Change Name</h2>
      <p>To edit the group name, simply change it below and then click change name.</p>
      <input type="text" id="group_name" value="<?php echo $groupDetails["GroupName"] ?>"> <button id="change_group_name">Change Name</button> <span id="change_group_name_msg"></span>
    </div>
  </body>
</html>
