<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require("../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

	$message = "";

	// If the user has submitted POST data process it
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if(!has_arg("POST", "password")) {
			$message = "No password set";
		} else {
			if(!has_arg("POST", "confirmation")) {
				$message = "No confirmation of password set";
			} else {
				$password = post("password");
				$confirmation = post("confirmation");

				// Confirm passwords match
				if($password !== $confirmation) {
					$message = "Passwords do not match";
				} else {
					// Hash and store the password
					$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]);

					$update = Database::get()->prepare("UPDATE `accounts` SET `Password` = :password WHERE `Username` = :username");
					$success = $update->execute(["password" => $hashedPassword, "username" => $_SESSION["user"]]);

					if($success === true) {
						$message = "Updated password";
					} else {
						$message = "Failed to update password";
					}
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
    <script src="/sixthadmin/resources/javascript/change-password.js"></script>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <div>
      <h1>Change Password</h1>
      <form method="POST">
        <table>
          <tr><td>New Password</td><td><input type="password" name="password" id="password"></td><td><span id="password_standard"></span></td></tr>
          <tr><td>Confirm Password</td><td><input type="password" name="confirmation" id="confirmation"></td><td><span id="confirm_status"></span></tr>
          <tr id="submission" style="display: none;"><td colspan="3"><input type="submit" value="Change"></td></tr>
        </table>
      </form>
			<p><?php echo $message; ?></p>
    </div>
  </body>
</html>
