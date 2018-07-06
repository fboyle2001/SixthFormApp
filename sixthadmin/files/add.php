<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

  $message = "";

?>

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
      <h1>Add New File</h1>
      <p>To create a new File that will be distributed to the app, choose the file and pick an expiry Date.</p>
      <p>If you do not want the URL to expire, leave the date field empty.</p>
      <br>
			<form method="post" action="AddDaily.php" enctype="multipart/form-data">
	    <p>File :</p>
	    <input type="file" accept ="application/pdf" name="File">
	 	<p>Expiry Date :</p>
	 	<input type = "date" name ="ExpDate">
		<p>File Type :</p>
		<input type="radio" name="type" value="2" checked> Daily Notice<br>
		<input type="radio" name="type" value="1"> News Letter<br>
	    <input TYPE="submit" name="upload" value="Submit"/>
	</form>
    </div>
  </body>
</html>
<?php
 	if(isset($_POST['upload'])){


 		$Date = time();
 		$_POST['ExpDate'];
 		$_FILES['File']['name'];

		$ExpDate = strtotime($_POST['ExpDate']);



		$servername = 'localhost';
		$username = 'DevAdmin';
 		$password = 'test';
 		$db_name = 'sixthapp';
 		$con = mysqli_connect($servername, "root", "", $db_name);
 		$sql="insert into `files` (`Name`, `AddedDate`, `ExpiryDate`, `Type`, `Link`) values('".$_FILES['File']['name']."', '".$Date."', '".$ExpDate."', '".$_POST['type']."','".$_FILES['File']["tmp_name"]."')";





 		if(mysqli_query($con, $sql)){
 			echo 'data added';
 		}
 		else{
 			echo'failed';


 		}



 	}






?>
