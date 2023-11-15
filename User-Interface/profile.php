<?php include "navigation.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>driver profile</title>




</head>
<body >
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
</svg>

  <a class="bi bi-bell" href="notification.php">notification</a>
  
      
   <?php 
 include 'db_connect.php'; 






$global_user_id = 0;

   if(isset($_COOKIE["current_driver"])){
  $number=$_COOKIE["current_driver"];
  $check="SELECT * FROM driver_registration WHERE phone_number=$number";

    $result=mysqli_query($database_connection,$check);
    $row=mysqli_num_rows($result);

    if ($row > 0) {
      while ($data=mysqli_fetch_assoc($result)) {

        $global_user_id = $data['ID'];
        ?>
        <div style="width: 500px; height:600px; margin-left: 500px; ">
        <div class="card-group">
        <div class="card" style="background-color: #E0F1F1;text-align: center;">

        <table style="border: #C0D8C6 sold 3px;">
        
     <img height="350px" src="upload_truck_image/<?php echo $data['truck_photo']; ?>" class="card-img-top">

      
      
          <div class="card-body">
     
           
          <tr>
            <th scope="col">Name</th>
            <td style="margin-left:2px;"><?php echo  $data['names'] ?></td>
          </tr>
          <tr>
            <th scope="col">Phone Number</th>
            <td style="margin-left:2px;"><?php echo  $data['phone_number'] ?></td>
          </tr>
      
           <tr>
            <th scope="col">Charge per kilometer(TK)</th>
            <td style="margin-left:2px;"><?php echo  $data['tk'] ?></td>
          </tr>
      
      
            <tr>
            <th scope="col">Truck Size</th>
            <td style="margin-left:2px;"><?php echo  $data['truck_size'] ?></td>
          </tr>
           <tr>
            <th scope="col">Weight Capacity</th>
            <td style="margin-left:2px;"><?php echo  $data['weight_capacity'] ?></td>
          </tr>
          <tr>
            <th scope="col">Stand Address</th>
            <td style="margin-left:2px;"><?php echo  $data['stand_address'] ?></td>
          </tr>
        
    
      </table>
      <div class="text-center">
           <a href="id=<?php  echo $data["ID"];  ?>" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm" style="background-color: black; color:white;">
           Update</a>
          
      </div> 
     
      <?php }}} ?>  
        </div>
        </div>
        </div>
    
      </div>
      
     
       
      

<!-- Modal -->

<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Update From</h4>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        
      </div>
      <?php 
	   include 'db_connect.php'; 
      
       
      
       	$selectInfo="SELECT * FROM driver_registration WHERE ID=$global_user_id";

       	$runInfo=mysqli_query($database_connection,$selectInfo);
         $row=mysqli_num_rows($runInfo);
         if ($row > 0) {

       	while($data =mysqli_fetch_assoc($runInfo)){
       
	?>
      <form action="" method="post">

      <div class="modal-body mx-6">
        <div class="md-form mb-2">
          <i class="fas fa-envelope prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-email">Your Name</label>
          <input type="text" name="name" value="<?php echo $data["names"]; ?>" class="form-control validate">
          
        </div>


        <div class="md-form mb-2">
          <i class="fas fa-envelope prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-email">Charge per kilometer (tk)</label>
          <input type="text" name="charge" value="<?php echo $data["tk"]; ?>"class="form-control validate">
          
        </div>

        <div class="md-form mb-2">
          <i class="fas fa-envelope prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-email">Truck size</label>
          <input type="text" name="size" value="<?php echo $data["truck_size"]; ?>"class="form-control validate">
          
        </div> 

        <div class="md-form mb-2">
          <i class="fas fa-envelope prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-email">Weight Capacity</label>
          <input type="text" name="weight" value="<?php echo $data["weight_capacity"]; ?>"class="form-control validate">
          
        </div>

        <div class="md-form mb-2">
          <i class="fas fa-envelope prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-email">Stand Address</label>
          <input type="text" name="stand_address" value="<?php echo $data["stand_address"]; ?>"class="form-control validate">
          <input type="hidden" name="id" value="<?php echo $edit_id; ?>">

        </div>
        
        <div class="md-form mb-2">
          <i class="fas fa-envelope prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-email">New truck Image</label>
          <input type="file" name="avatar" value="<?php echo $data['truck_photo']; ?>"class="form-control validate">
          
        </div>


      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-default" name="edit_button" style="background-color: black; color:white">Update</button>
      </div>

          </form>

      <?php }} ?>

       
    </div>
  </div>
</div>




</body>



<!-- update query -->

<?php

include 'db_connect.php'; 



 if (isset($_REQUEST["edit_button"])) {
 	$name = $_REQUEST["name"];
 	
   $charge=$_REQUEST["charge"];
 	 $size=$_REQUEST["size"];
   $weight=$_REQUEST["weight"];
   $stand_address=$_REQUEST["stand_address"];
   $get_id=$_REQUEST['update_id'];

  
  //  $avatar=$_FILES["avatar"]["name"];
  // $avatar_tmpName =$_FILES["avatar"]["tmp_name"];
  // $location ="upload_truck_image/";
  // $uniq_name=uniqid().".jpg";

  // move_uploaded_file($avatar_tmpName ,$location."$uniq_name.jpg");



  $update_query = "UPDATE driver_registration SET names='$name', stand_address='$stand_address',
  truck_size='$size', weight_capacity='$weight', tk='$charge'  WHERE ID='$get_id'";
 

    $run_query=mysqli_query($database_connection, $update_query);
    if ($run_query==true) {
    	echo "<script>
      alert('Successfully Update')
      </script>";
    }
 }


 ?>
   






<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"crossorigin="anonymous">
          
        </script> 
</body>
</html>
<?php include "footer.html" ?>