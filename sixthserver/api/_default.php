<?php
  die("403"); //remove this when copying

  include($_SERVER["DOCUMENT_ROOT"] . "/sixthserver/api/api_util.php");

  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

?>
