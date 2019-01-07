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

	// GET as it will have to be provided in a link
  $file = get("file");

  // Check if the file exists
  $selectFile = Database::get()->prepare("SELECT * FROM `files` WHERE `Link` = :link");
  $selectFile->execute(["link" => $file]);

	// Redirect if it does not
  if($selectFile->rowCount() != 1) {
    header("Location: /sixthadmin/files/");
    die();
  }

  // In case a file has not yet been deleted, prevent access to it
  $expiryTime = $selectFile->fetch()["ExpiryDate"];

  if(time() > $expiryTime) {
    header("Location: /sixthadmin/files/");
    die();
  }

  // File does exist so now display it
  // Files are kept outside of the public_html folder so jump back ../../../../../files/

  $filePath = "../../../files/$file";

	// No erroring and provide a few headers to help
  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=$filePath");
  @readfile($filePath);
?>
