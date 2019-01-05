<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

  if(!has_arg("GET", "auth")) {
    $status = ReplyStatus::withData(400, "Missing auth key");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  $authKey = get("auth");

  if(!non_header_auth_validate($authKey)) {
    $status = ReplyStatus::withData(403, "Invalid auth key");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  if(!has_arg("GET", "file")) {
    $status = ReplyStatus::withData(400, "No file requested");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  //should consist of name and extension
  $file = get("file");

  //check if the file exists
  $selectFile = Database::get()->prepare("SELECT * FROM `files` WHERE `Link` = :link");
  $selectFile->execute(["link" => $file]);

  if($selectFile->rowCount() != 1) {
    $status = ReplyStatus::withData(400, "Invalid file request");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  //in case a file has not yet been deleted, prevent access to it
  $expiryTime = $selectFile->fetch()["ExpiryDate"];

  if(time() > $expiryTime) {
    $status = ReplyStatus::withData(410, "File has expired");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  //file does exist so now display it
  //files are kept outside of the public_html folder so jump back ../../../../../files/

  $filePath = "../../../../../files/$file";

  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=$filePath");
  @readfile($filePath);
?>
