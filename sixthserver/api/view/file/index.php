<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

  // Must have an auth key
  if(!has_arg("GET", "auth")) {
    header("Location: ../../error/expired.pdf");
    die();
  }

  $authKey = get("auth");

  // Validate the auth key without headers
  if(!non_header_auth_validate($authKey)) {
    header("Location: ../../error/expired.pdf");
    die();
  }

  // Check if they have requested a file
  if(!has_arg("GET", "file")) {
    header("Location: ../../error/file.pdf");
    die();
  }

  // Consists of name and extension
  $file = get("file");

  // Check if the file exists
  $selectFile = Database::get()->prepare("SELECT * FROM `files` WHERE `Link` = :link");
  $selectFile->execute(["link" => $file]);

  // Only one row or there will be a problem
  if($selectFile->rowCount() != 1) {
    header("Location: ../../error/file.pdf");
    die();
  }

  // In case a file has not yet been deleted, prevent access to it
  $expiryTime = $selectFile->fetch()["ExpiryDate"];

  if(time() > $expiryTime) {
    header("Location: ../../error/file.pdf");
    die();
  }

  // File does exist so now display it
  // Files are kept outside of the public_html folder so jump back ../../../../../files/

  $filePath = "../../../../../files/$file";

  // Don't display errors and set some headers to help the browser

  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=$filePath");
  @readfile($filePath);
?>
