<?php
  require("../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

	$message = "";

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$displayName = post("displayName");
		$type = post("type");
		$expiryDate = post("expiryDate");

		if($displayName == null) {
			$message = "Display name must be set.";
		} else if ($type == null) {
			$message = "Type must be set.";
		} else if (!is_uploaded_file($_FILES["uploadedFile"]["tmp_name"])) {
			$message = "You must upload a file.";
		} else if (!in_array($type, [1, 2])){
			$message = "Type is invalid.";
		} else {
			$base = "../../../files/";
			$randomName = random_str(16) . "_$type.pdf";
			$storageFile = $base . $randomName;

			if(file_exists($storageFile)) {
				$message = "An issue occured with saving the file. Please try again.";
			} else if($_FILES["uploadedFile"]["size"] > 15000000) {
				$message = "File size is currently limited to 15mb, this file is too big.";
			} else if(strtolower(pathinfo($_FILES["uploadedFile"]["name"], PATHINFO_EXTENSION)) != "pdf") {
				$message = "File must be a pdf.";
			} else {
				$result = move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $storageFile);

				if($result == true) {
					$addedDate = time();

					if($expiryDate == null) {
						$expiryDate = 2147483647;
					} else {
			      $expiryDate = strtotime($expiryDate) + 60 * 60 * 2; //account for timezone issues (CHECK??)
					}

          $insertQuery = Database::get()->prepare("INSERT INTO `files` (`Name`, `AddedDate`, `ExpiryDate`, `Type`, `Link`) VALUES (:name, :added, :expiry, :type, :link)");
          $insertQuery->execute(["name" => $displayName, "added" => $addedDate, "expiry" => $expiryDate, "type" => $type, "link" => $randomName]);

					if($insertQuery == true) {
						$message = "Successfully uploaded file.";
					} else {
						$message = "File uploaded but unable to add database. This shouldn't happen.";
					}
				} else {
					$message = "Unable to upload your file, please try again.";
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
			// Includes the default header which includes the stylesheet and navigation JavaScript
			// It also includes jQuery in the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/head.php");
		?>
		<title>Upload File</title>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
		<div>
			<h1>Upload File</h1>
			<p>From here, you can upload files which will be distributed to the app.</p>
			<p>If you do not want the file to expire, leave the date field blank.</p>
			<br>
			<form method="POST" enctype="multipart/form-data">
				<table>
					<tr><td>File</td><td><input type="file" accept="application/pdf" name="uploadedFile" required></td></tr>
					<tr><td>Name</td><td><input type="text" name="displayName" required></td></tr>
					<tr><td>Expiry Date</td><td><input type="date" name="expiryDate"></td></tr>
					<tr><td colspan="2">Type:</td></tr>
					<tr><td>Notices</td><td><input type="radio" name="type" value="2" required></td></tr>
					<tr><td>Newsletter</td><td><input type="radio" name="type" value="1" required></td></tr>
					<tr><td colspan="2"><input type="submit" value="Upload File"></td></tr>
				</table>
			</form>
			<p id="message"><?php echo $message; ?></p>
		</div>
  </body>
</html>
