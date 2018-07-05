<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

  $message = "";

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = post("link_name");
    $expiryDate = post("expirydate");
    $url = post("url");

    if($name == null) {
      $message = "The name of the link must be set.";
    } else if ($url == null) {
      $message = "The URL must be set.";
    } else {
      if($expiryDate == null) {
        $expiryDate = 2147483647; //large date
      } else {
        $expiryDate = strtotime($expiryDate) + 60 * 60 * 2; //account for timezone issues
      }

      $insertQuery = "INSERT INTO `links` (`Name`, `ExpiryDate`, `Link`) VALUES ('$name', '$expiryDate', '$url')";
      $insertQuery = DatabaseHandler::getInstance()->executeQuery($insertQuery);

      if($insertQuery->wasSuccessful()) {
        $message = "Created new link with name $name.";
      } else {
        $message = "Unable to create new link, please try again later.";
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
      <h1>Add New Link</h1>
      <p>To create a new link that will be distributed to the app, fill in the Name, Date and URL.</p>
      <p>If you do not want the URL to expire, leave the date field empty.</p>
      <br>
      <form method="POST" id="link_form">
        <table>
          <tr><td>Name</td><td><input type="text" name="link_name" required></td></tr>
          <tr><td>Expiry Date</td><td><input type="date" name="expirydate"></td></tr>
          <tr><td>Link</td><td><input type="text" name="url"></td></tr>
          <tr><td colspan="2"><input type="submit" value="Add Link"></td></tr>
        </table>
      </form>
      <p><?php echo $message; ?></p>
    </div>
  </body>
</html>
