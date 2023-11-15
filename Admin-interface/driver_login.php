
<?php  include'db_connection.php';?>
<?php include'nav.php';?>
<!DOCTYPE html>
<html>
<head>
  <title>index</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <style>
   
      .style_table{
        margin-left: 200px;
        position: absolute;


      }
table,th,td{
    border:1px solid;
}
tr:nth-child(even){
  background-color:aquamarine;
}
tr:nth-child(odd){
  background-color:beige;
}


    </style>


</head>
<body>
    
  
 
     

       
<div class="style_table">
  <h1 style="margin-left: 500px; color: white">Driver Login Information</h1>
  <h1 style="color: white">Total Driver:<?php
    $count = "SELECT COUNT(ID) FROM driver_login";
    $run_count = mysqli_query($database_connection_admin, $count);
    $row = mysqli_fetch_array($run_count);
    $countValue = $row[0];
    echo $countValue;
  ?></h1>
  <?php
    if (isset($_REQUEST["deleted"])) {
      echo "DELETE SUCCESSFUL!";
    } elseif (isset($_REQUEST["edited"])) {
      echo "UPDATE SUCCESSFUL!";
    }
  ?>
  <br><br>
  <table border="1px" style="width:100%; text-align: center;">
    <tr>
      <th>SL_No</th>
      <th>DB_ID</th>
      <th>Number</th>
      <th>Password</th>
      <th>Time & Date</th>
      <th>Action</th>
    </tr>
    <?php
    // Pagination variables
    $limit = 10; // Number of records per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    $showdata = "SELECT * FROM user_login LIMIT $start, $limit";
    $run = mysqli_query($database_connection_admin, $showdata);

    $count = 1;
    if ($run == true) {
      while ($data = mysqli_fetch_array($run)) {
    ?>
        <tr>
          <td><?php echo $count;
              $count++; ?></td>
          <td><?php echo $data["id"]; ?></td>
          <td><?php echo $data["numbers"]; ?></td>
          <td><?php echo $data["password"]; ?></td>
          <td><?php echo $data["time&date"]; ?></td>
          <td> <a onclick="return confirm('ARE YOU SURE!')" href="delete.php?id=<?php echo $data["id"]; ?>">Delete</a></td>
        </tr>
    <?php }
    }
    ?>
  </table>
  <!-- Pagination links -->
  <?php
  $pagination_query = "SELECT COUNT(*) FROM driver_login";
  $pagination_run = mysqli_query($database_connection_admin, $pagination_query);
  $row = mysqli_fetch_array($pagination_run);
  $total_records = $row[0];
  $total_pages = ceil($total_records / $limit);

  echo "<div class='pagination'>";
  for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=" . $i . "'>" . $i . "</a>";
  }
  echo "</div>";
  ?>
</div>


</body>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</html>


 