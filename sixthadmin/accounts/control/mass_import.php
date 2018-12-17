<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require("../../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

  $attemptedUpload = false;
  $message = "";
  $failedStudents = [];
  $successes = 0;

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $attemptedUpload = true;
    if(!is_uploaded_file($_FILES["listFile"]["tmp_name"])) {
      $message = "You must upload a file.";
    } else {
      if($_FILES["listFile"]["size"] > 15000000) {
  			$message = "File size is currently limited to 15mb, this file is too big.";
      } else if(in_array(strtolower(pathinfo($_FILES["listFile"]["tmp_name"], PATHINFO_EXTENSION)), ["csv", "xls", "xlsx"])) {
  			$message = "File type must be csv, xls or xlsx.";
  		} else {
        $file = fopen($_FILES["listFile"]["tmp_name"], "r");

        $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `Username` = :username");
        $insertQuery = Database::get()->prepare("INSERT INTO `accounts` (`Username`, `Password`, `Year`, `IsAdmin`, `Reset`) VALUES (:username, :password, :year, 0, 1)");

        while(($data = fgetcsv($file)) !== false) {
          $username = strtolower(
          preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[1])) . "." . strtolower(
          preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[0]));
          $year = $data[2];

          $selectQuery->execute(["username" => $username]);

          if($selectQuery->rowCount() != 0) {
            array_push($failedStudents, [$data, "Account already exists with user name $username"]);
            continue;
          }

          $password = password_hash("Passw0rd", PASSWORD_BCRYPT, ["cost" => $cost]);
          $success = $insertQuery->execute(["username" => $username, "password" => $password, "year" => $year]);

          if($success === false) {
            array_push($failedStudents, [$data, "Unable to insert account into database"]);
            continue;
          }

          $successes++;
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
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <div>
      <h1>Import Student List</h1>
      <p>The student list should be a .csv, .xls or .xlsx file with the column order being first name, last name then year group.</p>
      <p>Each account will be created in the form <strong>[last name].[first name] all lowercase</strong> with the default password being <strong>Passw0rd</strong> (they will be <strong>forced to change this on their first login</strong> to the app)</p>
      <p><strong>Important Note: </strong>This process may take some time dependent on the size of the list <strong>do not exit the page whilst it is working</strong> , the screen may be white but it is working.</p>
      <br>
      <form method="POST" enctype="multipart/form-data" accept-charset="utf-8">
        <label for="listFile">Upload File: </label><input id="listFile" name="listFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
        <br>
        <br>
        <input type="submit" value="Upload Student List"/>
      </form>
    </div>
    <div>
      <?php
        if($attemptedUpload === true) {
          if($message == "") {
            echo '<h2>Upload Completed</h2>';
            $failures = sizeof($failedStudents);
            echo "<p>Uploaded $successes student accounts, failed to upload $failures student accounts</p>";

            if($failures != 0) {
              echo '<h3>Problems During Upload</h3>';
              echo '<table class="data-table"><thead><tr><th>First Name</th><th>Last Name</th><th>Year Group</th><th>Reason</th></tr></thead><tbody>';

              foreach($failedStudents as $record) {
                echo '<tr><td>' . $record[0][0] . '</td><td>' . $record[0][1] . '</td><td>' . $record[0][2] . '</td><td>' . $record[1] . '</td></tr>';
              }

              echo '</tbody></table>';
            } else {
              echo '<p>All accounts uploaded successfully</p>';
            }
          } else {
            echo '<h2>Upload Error</h2>';
            echo "<p>$message</p>";
          }
        }
      ?>
    </div>
  </body>
</html>
