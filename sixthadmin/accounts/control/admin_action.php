<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/Reply.php");

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

  }

  echo $reply->toJson();
?>
