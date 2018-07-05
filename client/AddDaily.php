<html> 
 

 

<body> 
 
 
 
<form method="post" action="AddDaily.php" enctype="multipart/form-data"> 
    <p>File :</p> 
    <input type="file" accept ="application/pdf" name="File">  
 	<p>Expiry Date :</p>
 	<input type "date" name ="ExpDate">
	<p>File Type :</p>
	<input type="radio" name="Type" value="1" checked> Daily Notice<br>
	<input type="radio" name="Type" value="2"> News Letter<br>
    <input TYPE="submit" name="upload" value="Submit"/> 
</form> 
 
 
 

   
 

<?php 
 	if(isset($_POST['upload'])){ 
	
		print_r($_FILES["File"]);
 		$Date = time(); 
 		$_POST['ExpDate']; 
 		$_FILES['File']['name']; 

		
 		echo $_POST['Type'];
		
		$servername = 'localhost'; 
		$username = 'DevAdmin'; 
 		$password = 'test'; 
 		$db_name = 'sixthapp'; 
 		$con = mysqli_connect($servername, "root", "", $db_name); 
 		$sql="insert into `files` (`Name`, `AddedDate`, `ExpiryDate`, `Type`, `Link`) values('".$_FILES['File']['name']."', '".$Date."', '".$_POST['ExpDate']."', '".$_POST['Type']."','".$_FILES['File']["tmp_name"]."')"; 
 		
		
		#header('Content-type: application/pdf');
		#header('Content-Disposition: inline; filename="' . $_FILES['File']['name'] . '"');
		#header('Content-Transfer-Encoding: binary');
		#header('Accept-Ranges: bytes');
		#@readfile($_FILES['File']["tmp_name"]);
 	 
	 
 		if(mysqli_query($con, $sql)){ 
 			echo 'data added'; 
 		} 
 		else{ 
 			echo'failed'; 
			

 		} 
 	}
	
	
	
	

	
?>	
	<iframe src=\"$_FILES['File']['name']\" width=\"100%\" style=\"height:100%\"></iframe>

 
 
 
 
 
 
</body> 
 
 
 
 
</html> 



