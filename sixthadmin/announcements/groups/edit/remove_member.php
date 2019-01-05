<?php
  require("../../../shared.php");

	rejectGuest();

	$reply = new Reply();
	$groupId = post("groupId");
	$userId = post("userId");

  // Can't do anything without the ID
  if($groupId == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No group ID set"));
    die($reply->toJson());
  }

  // Can't do anything without the ID
  if($userId == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No user ID set"));
    die($reply->toJson());
  }

  // Check if the user is in the group
  $userInGroup = Database::get()->prepare("SELECT * FROM `grouplink` WHERE `AccountID` = :aId AND `GroupID` = :gId");
  $userInGroup->execute(["aId" => $userId, "gId" => $groupId]);

  if($userInGroup->rowCount() == 0) {
    $reply->setStatus(ReplyStatus::withData(400, "User not in group"));
    die($reply->toJson());
  }

  // Remove the user from the group
  $removeUser = Database::get()->prepare("DELETE FROM `grouplink` WHERE `AccountID` = :aId AND `GroupID` = :gId");
  $removeSuccess = $removeUser->execute(["aId" => $userId, "gId" => $groupId]);

  if($removeSuccess === false) {
    $reply->setStatus(ReplyStatus::withData(500, "Error removing user"));
    die($reply->toJson());
  }

  // Success
  $reply->setStatus(ReplyStatus::withData(200, "Removed user"));
  $reply->setValue("id", $userId);
  die($reply->toJson());
?>
