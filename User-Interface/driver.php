<?php include "navigation.php" ?>
<!DOCTYPE html>
<html>
<head>
	<title>driver</title>
   <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    
    <?php include 'db_connect.php';?>

</head>
<body>
<?php
$size="";
if(isset($_GET['size'])){
  $size=$_GET['size'];
}
  


   $insertdata="SELECT * FROM driver_registration where truck_size=$size";
   $runinsertdata=mysqli_query($database_connection ,$insertdata);

   if( $runinsertdata==true){
    ?>
    
    <div class="row row-cols-4">
      <?php

    while($data=mysqli_fetch_array($runinsertdata)){

    
?>
<div class="card-group">
  <div class="card" style="background-color: #E0F1F1;">
  
  <img height="350px;" src="upload_truck_image/<?php echo $data['truck_photo'];?>" class="card-img-top" alt="">


    <div class="card-body">
  <table>
      <tr>
      <th scope="col">BD ID</th>
      <td><?php echo $data['ID'];?></td>
    </tr>
    <tr>
      <th scope="col">Name</th>
      <td><?php echo $data['names'];?></td>
    </tr>
    <tr>
      <th scope="col">Phone Number</th>
      <td><?php echo $data['phone_number']; ?></td>
    </tr>

     <tr>
      <th scope="col">Charge per kilometer(TK)</th>
      <td><?php echo $data['tk']; ?></td>
    </tr>


      <tr>
      <th scope="col">Truck Size</th>
      <td><?php echo $data['truck_size']; ?></td>
    </tr>
     <tr>
      <th scope="col">Weight Capacity</th>
      <td><?php echo $data['weight_capacity']; ?></td>
    </tr>
    <tr>
      <th scope="col">Stand Address</th>
      <td><?php echo $data['stand_address']; ?></td>
    </tr>
  <?php
  $tk=$data['tk'];
  $id=$data['ID'];

?>
</table>



          <button class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"  style="font-size: 20px; background-color: #C0D8C6 ; font-weight: bold;">
          Hire driver</button>
          
      <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="personal-shifting.php?tk=<?php echo $tk; ?>&id=<?php echo $id; ?>">Personal</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="business-shifting.php?tk=<?php echo $tk; ?>&id=<?php echo $id; ?>">Business</a></li>
</ul>

       

        
  
  </div>
  </div>
  </div>
<?php  }} ?>
</div>



  





        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"crossorigin="anonymous">
          
        </script>
</body>
</html>
<?php include "footer.html" ?>