<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

  // Must have an auth key
  if(!has_arg("GET", "auth")) {
    $status = ReplyStatus::withData(400, "Missing auth key");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  $authKey = get("auth");

  // Validate the auth key without headers
  if(!non_header_auth_validate($authKey)) {
    $status = ReplyStatus::withData(403, "Invalid auth key");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  // Check if they have requested a file
  if(!has_arg("GET", "file")) {
    $status = ReplyStatus::withData(400, "No file requested");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  // Consists of name and extension
  $file = get("file");

  // Check if the file exists
  $selectFile = Database::get()->prepare("SELECT * FROM `files` WHERE `Link` = :link");
  $selectFile->execute(["link" => $file]);

  // Only one row or there will be a problem
  if($selectFile->rowCount() != 1) {
    $status = ReplyStatus::withData(400, "Invalid file request");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  // In case a file has not yet been deleted, prevent access to it
  $expiryTime = $selectFile->fetch()["ExpiryDate"];

  if(time() > $expiryTime) {
    $status = ReplyStatus::withData(410, "File has expired");
    $reply = Reply::withStatus($status);
    die($reply->toJson());
  }

  // File does exist so now display it
  // Files are kept outside of the public_html folder so jump back ../../../../../files/

  $filePath = "../../../../../files/$file";

  // Don't display errors and set some headers to help the browser

  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=$filePath");
  @readfile($filePath);
?>
