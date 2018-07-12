<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");
  require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/Reply.php");

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
  $insertGroup = DatabaseHandler::getInstance()->executeQuery($insertGroup);

  if($insertGroup->wasSuccessful() == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to create new group"));
    die($reply->toJson());
  }

  $selectGroupId = "SELECT `ID` FROM `groups` WHERE `GroupName` = '$name'";
  $selectGroupId = DatabaseHandler::getInstance()->executeQuery($selectGroupId);

  if($selectGroupId->wasDataReturned() == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to assign group members"));
    die($reply->toJson());
  }

  $groupId = $selectGroupId->getRecords()[0]["ID"];
  $arrayIds = explode(",", $ids);
  $failures = [];
  $successCount = 0;

  foreach($arrayIds as $index => $id) {
    $query = "INSERT INTO `grouplink` (`GroupID`, `AccountID`) VALUES ($groupId, $id)";
    $query = DatabaseHandler::getInstance()->executeQuery($query);

    if($query->wasSuccessful()) {
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
