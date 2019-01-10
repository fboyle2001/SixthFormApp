<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

  // Need to be at least a student
  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  $status = ReplyStatus::withData(200, "Success");
  $reply = Reply::withStatus($status);

  $username = get_username();
  $secret = get_secret();

  // Send them their expire time
  $query = Database::get()->prepare("SELECT `ExpireTime` FROM `apikeys` WHERE `Username` = :username AND `Secret` = :secret");
  $query->execute(["username" => $username, "secret" => $secret]);
  $result = $query->fetch()["ExpireTime"];

  // Used to be used for testing purposes so is kept in case it is needed
  $timeRemaining = $result - time();

  // Send the data back
  $reply->setValue("timeRemaining", $timeRemaining);
  $reply->setValue("authLevel", get_level());
  $reply->setValue("expire", $result);

  echo $reply->toJson();
?>
