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
<input type = "file" name = "FileUpload" id = "FileUpload">
<input type = "submit" value = "Upload file" name = "submit">


<?php
#if(isset($_POST['submit']))
#{
	##############This doesnt work yet and idk why but ill figure it out
	#$file = $_FILES['file']['name'];
	#$file_loc = $_FILES['file']['tmp_name'];
	#$file_size = $_FILES['file']['size'];
	#$file_type = $_FILES['file']['type'];
	#$folder = "uploads/";
	
	
	#move_uploaded_file($file_loc,$folder.$file);
	#$sql="INSERT INTO ----------- VALUES('$file','$file_type','$file_size')";
	######################
	
	#$servername = '-------';
	#$username = '----';
	#$password = '--------';
	#$db_name = '------';

	#$con = mysqli_connect($servername, $username, $password, $db_name);



	#mysql_query($sql);
#}

?>


</div>

</body>

</html>
