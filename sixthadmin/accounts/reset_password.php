<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/Reply.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  $selectQuery = "SELECT * FROM `accounts` WHERE `ID` = '$id'";
  $selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

  if($selectQuery->wasDataReturned() == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  $defaultPassword = password_hash("Passw0rd", PASSWORD_BCRYPT, ["cost" => $cost]);

  $resetQuery = "UPDATE `accounts` SET `Password` = '$defaultPassword', `Reset` = 1 WHERE `ID` = '$id'";
  $resetQuery = DatabaseHandler::getInstance()->executeQuery($resetQuery);

  if($resetQuery->wasSuccessful() == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to reset password"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully reset password"));
  echo $reply->toJson();
?>
