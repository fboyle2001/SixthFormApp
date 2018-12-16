<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $username = get("username");

  if($username == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No username set"));
    die($reply->toJson());
  }

  $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `Username` = :username");
  $selectQuery->execute(["username" => $username]);

  if($selectQuery->rowCount() == 0) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid username"));
    die($reply->toJson());
  }

  $row = $selectQuery->fetch(PDO::FETCH_ASSOC);

  $reply->setStatus(ReplyStatus::withData(200, "Successfully found account"));
  $reply->setValue("id", $row["ID"]);
  echo $reply->toJson();
?>
