<?php
  include("../../api_util.php");

  if(!validate(0)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  $status = ReplyStatus::withData(200, "Success");
  $reply = Reply::withStatus($status);

  $username = get_username();
  $secret = get_secret();

  $query = "SELECT `ExpireTime` FROM `apikeys` WHERE `Username` = '$username' AND `Secret` = '$secret'";
  $result = DatabaseHandler::getInstance()->executeQuery($query);
  $result = $result->getRecords()[0]["ExpireTime"];

  #no need to display if expired since they can't reach here if it has

  $timeRemaining = $result - time();
  $reply->setValue("timeRemaining", $timeRemaining);
  $reply->setValue("authLevel", get_level());

  echo $reply->toJson();
?>
