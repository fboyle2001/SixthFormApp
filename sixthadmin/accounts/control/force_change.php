<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/Reply.php");

  rejectGuest();

  $reply = new Reply();

  $updateQuery = "UPDATE `accounts` SET `Reset` = 1 WHERE 1 = 1";
  $updateQuery = DatabaseHandler::getInstance()->executeQuery($updateQuery);

  if($updateQuery->wasSuccessful() == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to force password change."));
  } else {
    $reply->setStatus(ReplyStatus::withData(200, "Successfully forced password change on app login for all users."));
  }

  echo $reply->toJson();
?>
