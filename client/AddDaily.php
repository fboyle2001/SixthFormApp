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
	

 		$Date = time(); 
 		$_POST['ExpDate']; 
 		$_FILES['File']['name']; 

		$ExpDate = strtotime($_POST['ExpDate']);
		

		
		$servername = 'localhost'; 
		$username = 'DevAdmin'; 
 		$password = 'test'; 
 		$db_name = 'sixthapp'; 
 		$con = mysqli_connect($servername, "root", "", $db_name); 
 		$sql="insert into `files` (`Name`, `AddedDate`, `ExpiryDate`, `Type`, `Link`) values('".$_FILES['File']['name']."', '".$Date."', '".$ExpDate."', '".$_POST['Type']."','".$_FILES['File']["tmp_name"]."')"; 
 		
		

 	 
	 
 		if(mysqli_query($con, $sql)){ 
 			echo 'data added'; 
 		} 
 		else{ 
 			echo'failed'; 
			

 		} 
		###################################
		
		#header('Content-type: application/pdf');
		#header('Content-Disposition: inline; filename="' . $_FILES['File']['name'] . '"');
		#header('Content-Transfer-Encoding: binary');
		#header('Accept-Ranges: bytes');
		#@readfile($_FILES['File']["tmp_name"]);

		################################### Once the database links are set up just put the link from the database in the last brackets where the temp name currently is
		
		
 	}
	
	
	
	

	
?>	


 
 
 
 
 
 
</body> 
 
 
 
 
</html> 



