<?php 

setcookie("current_driver","",time()-(86400*7));
header("location:index.php");

setcookie("current_user","",time()-(86400*7));
header("location:index.php");
?>