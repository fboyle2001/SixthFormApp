<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $name = get("name");

  $selectQuery = "SELECT * FROM `groups`";

  if($name != null) {
    $selectQuery = Database::get()->prepare("SELECT * FROM `groups` WHERE `GroupName` LIKE '%' :name '%'");
    $selectQuery->execute(["name" => $name])
  } else {
    $selectQuery = Database::get()->query("SELECT * FROM `groups`");
  }

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid group name"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Data found"));
  $reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
  $reply->setValue("found", $selectQuery == true);
  echo $reply->toJson();
?>
