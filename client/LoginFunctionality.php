<html>
<body>

<div align="center">
<?php

echo"<table>";
echo '<form method="post" action= "loginFunctionality.php">';
echo '<tr><td>username</td><td><input type="text" name="username"></td></tr>';
echo '<tr><td>password</td><td><input type="password" name="password"></td></tr>';
echo '<tr><td colspan = "2"><input type="submit" name="submit"></td></tr>';
echo"</form>";
echo"</table>";



#$servername = '-------';
#$username = '----';
#$password = '--------';
#$db_name = '------';

#$con = mysqli_connect($servername, $username, $password, $db_name);

#if(isset($_POST['submit'])){
#$sql= "select * from `login` where `Username`='".$_POST['username']."' ";
#$result=mysqli_query($con, $sql);
#$rows= mysqli_num_rows($result);

#if ($rows!=0){
#$sql= "select * from `login` where `Password`='".$_POST['password']."' ";
#$result=mysqli_query($con, $sql);
#$rows= mysqli_num_rows($result);

#if ($rows!=0){
#echo 'logged in';
#$_SESSION['login'] = 'yes';
#$_SESSION['username'] = $_POST['username'];
#header('location: index.php');

#}

#}

#}



?>



</div>
</body>

</html>
