<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  // Must have an ID set
  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Check the ID is valid
  $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  // Hash the default password
  $defaultPassword = password_hash("Passw0rd", PASSWORD_BCRYPT, ["cost" => $cost]);

  // Update the database
  $resetQuery = Database::get()->prepare("UPDATE `accounts` SET `Password` = :password, `Reset` = 1 WHERE `ID` = :id");
  $resetQuery->execute(["password" => $defaultPassword, "id" => $id]);

  if($resetQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to reset password"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully reset password"));
  echo $reply->toJson();
?>
