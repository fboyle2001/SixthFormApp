<?php
  define("AllowIncludes", 1);
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

  $query = Database::get()->prepare("SELECT `ExpireTime` FROM `apikeys` WHERE `Username` = :username AND `Secret` = :secret");
  $query->execute(["username" => $username, "secret" => $secret]);
  $result = $query->fetch()["ExpireTime"];

  #no need to display if expired since they can't reach here if it has

  $timeRemaining = $result - time();
  $reply->setValue("timeRemaining", $timeRemaining);
  $reply->setValue("authLevel", get_level());
  $reply->setValue("expire", $result);

  echo $reply->toJson();
?>
