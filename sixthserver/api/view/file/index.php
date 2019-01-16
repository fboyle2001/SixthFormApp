<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

  // Must have an auth key
  if(!has_arg("GET", "auth")) {
    header("Content-type: application/pdf");
    header("Location: ../../error/expired.pdf");
    die();
  }

  $authKey = get("auth");

  // Validate the auth key without headers
  if(!non_header_auth_validate($authKey)) {
    header("Content-type: application/pdf");
    header("Location: ../../error/expired.pdf");
    die();
  }

  // Check if they have requested a file
  if(!has_arg("GET", "file")) {
    header("Content-type: application/pdf");
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
    header("Content-type: application/pdf");
    header("Location: ../../error/file.pdf");
    die();
  }

  // In case a file has not yet been deleted, prevent access to it
  $expiryTime = $selectFile->fetch()["ExpiryDate"];

  if(time() > $expiryTime) {
    header("Content-type: application/pdf");
    header("Location: ../../error/file.pdf");
    die();
  }

  $splitDot = explode(".", $file);

  if(sizeof($splitDot) == 1) {
    // Something weird happened. No extension.
    header("Content-type: application/pdf");
    header("Location: ../../error/file.pdf");
    die();
  }

  $extension = $splitDot[sizeof($splitDot) - 1];

  if(!in_array($extension, ["pdf", "doc", "docx"])) {
    // Invalid extension
    header("Content-type: application/pdf");
    header("Location: ../../error/file.pdf");
    die();
  }

  // File does exist so now display it
  // Files are kept outside of the public_html folder so jump back ../../../../../files/

  $filePath = "../../../../../files/$file";

  // Don't display errors and set some headers to help the browser

  if($extension == "pdf") {
    header("Content-type: application/pdf");
  } else {
    header("Content-type: application/msword");
  }

  header("Content-Disposition: inline; filename=$filePath");
  @readfile($filePath);
?>
