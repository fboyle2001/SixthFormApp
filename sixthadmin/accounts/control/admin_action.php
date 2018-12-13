<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $actionId = post("actionId");
  $adminId = $_SESSION["userId"];

  if($adminId == null) {
    $reply->setStatus(ReplyStatus::withData(403, "Must be an admin"));
    die($reply->toJson());
  }

  if($actionId == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No action specified"));
    die($reply->toJson());
  }

  $adminQuery = "INSERT INTO `adminactions` (`AdminID`, `Action`) VALUES ($adminId, $actionId)";
  $adminQuery = DatabaseHandler::getInstance()->executeQuery($adminQuery);

  if($adminQuery->wasSuccessful() == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Server error"));
    die($reply->toJson());
  }

  if($actionId == 1) {
    $updateQuery = "UPDATE `accounts` SET `Reset` = 1 WHERE 1 = 1";
    $updateQuery = DatabaseHandler::getInstance()->executeQuery($updateQuery);

    if($updateQuery->wasSuccessful() == false) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to force password change."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully forced password change on app login for all users."));
    }
  } else if ($actionId == 2) {
    $updateQuery = "UPDATE `accounts` SET `Year` = `Year` + 1 WHERE `Year` <> 0";
    $updateQuery = DatabaseHandler::getInstance()->executeQuery($updateQuery, true);

    if($updateQuery->wasSuccessful() == false) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to increment year group."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully incremented year group for all students."));
    }
  } else if ($actionId == 3) {
    $deleteQuery = "DELETE FROM `accounts` WHERE `Year` >= 14";
    $deleteQuery = DatabaseHandler::getInstance()->executeQuery($deleteQuery);

    if($deleteQuery->wasSuccessful() == false) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to delete old students."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted old students."));
    }
  }

  echo $reply->toJson();
?>
