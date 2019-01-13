<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $actionId = post("actionId");
  $adminId = $_SESSION["userId"];

  // Check they have submitted the data
  if($adminId == null) {
    $reply->setStatus(ReplyStatus::withData(403, "Must be an admin"));
    die($reply->toJson());
  }

  if($actionId == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No action specified"));
    die($reply->toJson());
  }

  // Log the action
  $adminQuery = Database::get()->prepare("INSERT INTO `adminactions` (`AdminID`, `Action`) VALUES (:adminId, :actionId)");
  $adminQuery->execute(["adminId" => $adminId, "actionId" => $actionId]);

  if($adminQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Server error"));
    die($reply->toJson());
  }

  if($actionId == 1) {
    // Make users change their password on login
    $updateQuery = Database::get()->exec("UPDATE `accounts` SET `Reset` = 1 WHERE 1 = 1");

    if($updateQuery == 0) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to force password change."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully forced password change on app login for all users."));
    }
  } else if ($actionId == 2) {
    // Increment year group
    $updateQuery = Database::get()->exec("UPDATE `accounts` SET `Year` = `Year` + 1 WHERE `Year` <> 0");

    if($updateQuery == 0) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to increment year group."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully incremented year group for all students."));
    }
  } else if ($actionId == 3) {
    // Delete all old students
    $deleteQuery = Database::get()->exec("DELETE FROM `accounts` WHERE `Year` >= 14");

    if($deleteQuery == 0) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to delete old students (or none exist)."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted old students."));
    }
  }

  echo $reply->toJson();
?>
