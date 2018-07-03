<?php

session_start();

if (empty($_SESSION['username'])) {
$_SESSION['username'] = 'Guest';
}

echo $_SESSION['username'];

?>

<html>

<body>


<form action="AddNews.php" method = "post" enctype = "multipart/form-data">
<input type = "file" name = "file" id = "FileUpload">
<input type = "submit" value = "Uploadfile" name = "submit">


<?php
if(isset($_POST['submit']))
{
	
	$file = $_FILES['file']['name'];
	$file_loc = $_FILES['file']['tmp_name'];
	$folder = "uploads/";
	
	
	#move_uploaded_file($file_loc,$folder.$file);
	#$sql="INSERT INTO ----------- VALUES('$file',)";
	
	
	#$servername = '-------';
	#$username = '----';
	#$password = '--------';
	#$db_name = '------';

	#$con = mysqli_connect($servername, $username, $password, $db_name);



	#mysql_query($sql);
}

?>


</div>

</body>

</html>
