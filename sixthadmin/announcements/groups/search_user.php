<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $username = get("user");

  $users = [];

  if($username == null) {
    $selectQuery = Database::get()->prepare("SELECT `ID`, `Username` FROM `accounts`");
    $selectQuery->execute(["username" => $username]);
    $users = $selectQuery->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $selectQuery = Database::get()->prepare("SELECT `ID`, `Username` FROM `accounts` WHERE `Username` LIKE '%' :username '%'");
    $selectQuery->execute(["username" => $username]);
    $users = $selectQuery->fetchAll(PDO::FETCH_ASSOC);
  }

  if(sizeof($users) == 0) {
    $reply->setStatus(ReplyStatus::withData(400, "No user found"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully found account"));
  $reply->setValue("users", $users);
  echo $reply->toJson();
?>
