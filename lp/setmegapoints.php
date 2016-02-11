
<?php include 'connect.php';
session_start();
$user=$_POST['username'];
 $level=$_POST['level'];
 $megapoints=$_POST['megapoints'];
 $sql="UPDATE main SET megapoints = '$megapoints',tk_current_level='$level' WHERE tk_emailid='$user'";
 $result=mysql_query($sql) or die(mysql_error());



 ?>
