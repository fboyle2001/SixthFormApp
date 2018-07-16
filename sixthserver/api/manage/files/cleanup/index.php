<?php
  include($_SERVER["DOCUMENT_ROOT"] . "/sixthserver/api/api_util.php");

	$reply = new Reply();

  if(validate(AccessLevel::admin)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

  $time = time();

  $selectQuery = "SELECT * FROM `files` WHERE `ExpiryDate` < $time";
  $selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

  if($selectQuery->wasDataReturned() == false) {
    $reply->setStatus(ReplyStatus::withData(200, "No files to delete"));
    $reply->setValue("count", 0);
    $reply->setValue("success", 0);
    $reply->setValue("failure", 0);
    die($reply->toJson());
  }

  $found = count($selectQuery->getRecords());
  $success = 0;
  $failure = 0;

  foreach($selectQuery->getRecords() as $record) {
    $id = $record["ID"];
    $resourceLink = $_SERVER["DOCUMENT_ROOT"] . "/sixthserver" . $record["Link"];
    $result = unlink($resourceLink);

    $deleteQuery = "DELETE FROM `files` WHERE `ID` = $id";
    $deleteQuery = DatabaseHandler::getInstance()->executeQuery($deleteQuery);

    if($result == true && $deleteQuery->wasSuccessful() == true) {
      $success++;
    } else {
      $failure++;
    }
  }

  $reply->setStatus(ReplyStatus::withData(200, "Attempted delete"));
  $reply->setValue("count", $found);
  $reply->setValue("success", $success);
  $reply->setValue("failure", $failure);

  echo $reply->toJson();

?>
