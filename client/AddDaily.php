<html>




<body>




<form method="post" action="AddDaily.php" enctype="multipart/form-data">
    <p>File :</p>
    <input type="file" accept ="application/pdf" name="File"> 
	<p>Expiry Date :</p>
	<input type "date" name ="ExpDate">
    <input TYPE="submit" name="upload" value="Submit"/>
</form>


 

<?php
	if(isset($_POST['submit'])){
		$Date = date("d/m/y");
		$_POST['ExpDate'];
		$_FILES['File']['name'];
		
		
		$servername = 'localhost';
		$username = 'DevAdmin';
		$password = 'test';
		$db_name = 'sixthapp';

		$con = mysqli_connect($servername, $username, $password, $db_name);




		$sql="insert into `files` (`Name`, `AddedDate`, `ExpiryDate`, `Type`, `Link`) values('".$_FILES['File']['name']."', '".$Date."', '".$_POST['ExpDate']."', '".Daily."','".$_FILES['file']."')";
	
	
		if(mysqli_query($con, $sql)){
			echo 'data added';
		}
	}
?>



</body>


</html>


