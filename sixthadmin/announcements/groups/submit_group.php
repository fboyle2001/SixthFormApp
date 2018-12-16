<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $name = get("name");
  $ids = get("ids");

  if($name == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No group name set"));
    die($reply->toJson());
  }

  if($ids == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No users set"));
    die($reply->toJson());
  }

  $insertGroup = "INSERT INTO `groups` (`GroupName`) VALUES ('$name')";
  $insertGroup = Database::get()->prepare("INSERT INTO `groups` (`GroupName`) VALUES (:name)");
  $insertGroup->execute(["name" => $name]);

  if($insertGroup == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to create new group"));
    die($reply->toJson());
  }

  $selectGroupId = Database::get()->prepare("SELECT `ID` FROM `groups` WHERE `GroupName` = :name");
  $selectGroupId->execute(["name" => $name]);

  if($selectGroupId->rowCount() == 0) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to assign group members"));
    die($reply->toJson());
  }

  $groupId = $selectGroupId->fetch(PDO::FETCH_ASSOC)["ID"];
  $arrayIds = explode(",", $ids);
  $failures = [];
  $successCount = 0;

  $groupInsert = Database::get()->prepare("INSERT INTO `grouplink` (`GroupID`, `AccountID`) VALUES (:groupId, :id)");

  foreach($arrayIds as $index => $id) {
    $result = $groupInsert->execute(["groupId" => $groupId, "id" => $id]);

    if($result == true) {
      $successCount++;
    } else {
      array_push($failures, $id);
    }
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully created group"));
  $reply->setValue("success_count", $successCount);
  $reply->setValue("failure_count", count($failures));
  $reply->setValue("failures", $failures);
  echo $reply->toJson();
?>
