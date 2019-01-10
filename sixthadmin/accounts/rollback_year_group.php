<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }
	
  $validationQuery = Database::get()->query("SELECT `Year` FROM `accounts` WHERE `ID` = $id");
  $data = $validationQuery->fetch(PDO::FETCH_OBJ);
  
  if($data->Year == 12) {
	 $Year = 0;
  }
  else{
	 $Year = 1;
	
  }

  $resetQuery = Database::get()->prepare("UPDATE `accounts` SET `Year` = `Year` - $Year WHERE `ID` = :id");
  $resetQuery->execute(["id" => $id]);

  if($resetQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to rollback"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully rolledback"));
  echo $reply->toJson();
?>