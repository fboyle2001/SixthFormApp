<?php
  require("../shared.php");

  rejectGuest();

  /**
  * When deleting an account the following all need to be deleted:
  * - Delete their account
  * - Delete their API key records
  * - Delete any grouplink records containing their ID
  * - Delete their push notification IDs
  **/

  $reply = new Reply();
  $id = post("id");

  // Must have an ID set
  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Check it exists
  $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  if($selectQuery->rowCount() != 1) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  $row = $selectQuery->fetch(PDO::FETCH_ASSOC);

  $deleteAPI = Database::get()->prepare("DELETE FROM `apikeys` WHERE `Username` = :username");
  $deleteAPI->execute(["username" => $row["Username"]]);

  $deleteGroupLink = Database::get()->prepare("DELETE FROM `grouplink` WHERE `AccountID` = :id");
  $deleteGroupLink->execute(["id" => $row["ID"]]);

  $deletePush = Database::get()->prepare("DELETE FROM `push` WHERE `AccountID` = :id");
  $deletePush->execute(["id" => $row["ID"]]);

  // Remove the single ID from the database
  $deleteAccount = Database::get()->prepare("DELETE FROM `accounts` WHERE `ID` = :id");
  $deleteAccount->execute(["id" => $id]);

  if($deleteAccount == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete account"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted account"));
  echo $reply->toJson();
?>
