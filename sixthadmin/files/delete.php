<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Make sure the file exists
  $selectQuery = Database::get()->prepare("SELECT * FROM `files` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  // Remove it from the system storage
  $link = $selectQuery->fetch(PDO::FETCH_ASSOC)["Link"];
  $storedFile = "../../../files/" . $link;

  $unlinkResult = unlink($storedFile);

  if($unlinkResult == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete file"));
    die($reply->toJson());
  }

  // Remove it from the database
  // Maybe consider rearranging these?
  $deleteQuery = Database::get()->prepare("DELETE FROM `files` WHERE `ID` = :id");
  $deleteQuery->execute(["id" => $id]);

  if($deleteQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "File deleted but unable to remove database reference"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted file"));
  echo $reply->toJson();
?>
