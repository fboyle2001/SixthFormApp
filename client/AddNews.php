<html>




<body>




<form method="post" action="AddNews.php" enctype="multipart/form-data">
    <p>File :</p>
    <input type="file" accept ="application/pdf" name="File"> 
	<p>Expiry Date :</p>
	<input type "date" name ="ExpDate">
    <input TYPE="submit" name="upload" value="Submit"/>
</form>


 

<?php


	echo date("d/m/y");
	echo $_POST['ExpDate'];
	echo $_FILES['File']['name'];
	

?>


</body>


</html>