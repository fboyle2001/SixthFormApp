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


</div>

</body>

</html>
