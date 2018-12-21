<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require("../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

  if(!has_arg("GET", "file")) {
    header("Location: /sixthadmin/files/");
    die();
  }

  $file = get("file");

  //check if the file exists
  $selectFile = Database::get()->prepare("SELECT * FROM `files` WHERE `Link` = :link");
  $selectFile->execute(["link" => $file]);

  if($selectFile->rowCount() != 1) {
    header("Location: /sixthadmin/files/");
    die();
  }

  //in case a file has not yet been deleted, prevent access to it
  $expiryTime = $selectFile->fetch()["ExpiryDate"];

  if(time() > $expiryTime) {
    header("Location: /sixthadmin/files/");
    die();
  }

  //file does exist so now display it
  //files are kept outside of the public_html folder so jump back ../../../../../files/

  $filePath = "../../../files/$file";

  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=$filePath");
  @readfile($filePath);
?>
