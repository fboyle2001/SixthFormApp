<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require("../../../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

  $reply = new Reply();

  $id = post("id");
  $name = post("name");

  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJSON());
  }

  // Check if a group already has the name

  if($name == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No name set"));
    die($reply->toJSON());
  }

  $groupNameExists = Database::get()->prepare("SELECT * FROM `groups` WHERE `GroupName` = :name");
  $groupNameExists->execute(["name" => $name]);

  if($groupNameExists->rowCount() != 0) {
    $reply->setStatus(ReplyStatus::withData(400, "Group name in use"));
    die($reply->toJson());
  }

  // Now change the group name

  $updateName = Database::get()->prepare("UPDATE `groups` SET `GroupName` = :name WHERE `ID` = :id");
  $updateSuccess = $updateName->execute(["name" => $name, "id" => $id]);

  if($updateSuccess === false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to update name"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Changed Successfully"));
  $reply->setValue("name", $name);
  die($reply->toJson());
?>
