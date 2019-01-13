<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $username = get("user");


  // Search by username or display them all
  if($username == null) {
    $selectQuery = Database::get()->prepare("SELECT `ID`, `Username` FROM `accounts`");
    $selectQuery->execute();
  } else {
    $selectQuery = Database::get()->prepare("SELECT `ID`, `Username` FROM `accounts` WHERE `Username` LIKE '%' :username '%'");
    $selectQuery->execute(["username" => $username]);
  }

  // Check if data was returned
  if($selectQuery->rowCount() == 0) {
    $reply->setStatus(ReplyStatus::withData(400, "No user found"));
    die($reply->toJson());
  }

  // Get all the data
  $users = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

  $reply->setStatus(ReplyStatus::withData(200, "Successfully found account"));
  $reply->setValue("users", $users);
  echo $reply->toJson();
?>
