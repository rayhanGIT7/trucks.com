<?php
 
  include'db_connection.php';
  
$get_ID = $_REQUEST['id'];

$delete_query ="DELETE FROM user_registration WHERE ID=$get_ID";

  $run_DLquery=mysqli_query($database_connection_admin,$delete_query);
  if ($run_DLquery==true) {
    echo"successfully delete";
  }
?>