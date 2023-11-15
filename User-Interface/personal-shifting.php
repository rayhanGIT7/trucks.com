<?php include "navigation.php" ?>
<!DOCTYPE html>
<html>
<head>
  <title>personal-shitting</title>

<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/"> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

 

<script>
 function validateshifting() {
  // Retrieve input values
  var name = document.getElementById("name").value;
  var numbers = document.getElementById("number").value;
  var email = document.getElementById("email").value;
  var date = document.getElementById("date").value;
  var picklocation = document.getElementById("picklocation").value;
  var droplocation = document.getElementById("droplocation").value;

  // Initialize error variables
  var nameErr = numberErr = emailErr = dateErr = pickErr = dropErr = true;

  // Validate name
  if(name == "") {
    document.getElementById("err_name").innerHTML = "Please enter your company name";
  } else {
    document.getElementById("err_name").innerHTML = "";
    nameErr = false;
  }

  // Validate phone number
  var phonen = /^\d{11}$/;
  if(numbers == "") {
    document.getElementById("err_number").innerHTML = "Please enter your phone number";
  } else if(!number.match(phonen)) {
    document.getElementById("err_number").innerHTML = "Please enter a valid 11-digit phone number";
  } else {
    document.getElementById("err_number").innerHTML = "";
    numberErr = false;
  }

  // Validate email

  if(email == "") {
    document.getElementById("err_email").innerHTML = "Please enter your email";
  }  else {
    document.getElementById("err_email").innerHTML = "";
    emailErr = false;
  }

  // Validate date
  if(date == "") {
    document.getElementById("err_date").innerHTML = "Please select a date";
  } else {
    document.getElementById("err_date").innerHTML = "";
    dateErr = false;
  }

  // Validate pickup location
  if(picklocation == "") {
    document.getElementById("err_piclocation").innerHTML = "Please enter pickup location";
  } else {
    document.getElementById("err_piclocation").innerHTML = "";
    pickErr = false;
  }

  // Validate drop location
  if(droplocation == "") {
    document.getElementById("err_droplocation").innerHTML = "Please enter drop location";
  } else {
    document.getElementById("err_droplocation").innerHTML = "";
    dropErr = false;
  }

  // Check if there are any errors
  if(nameErr || numberErr || emailErr || dateErr || pickErr || dropErr) {
    return false;
  } else {
    return true;
  }
}


   </script>



 
    <style>
     
      .form{
        width: 500px;
        height: 550px;
        padding: 20px;
        text-align: center;
        margin-left:600px;
        margin-top: 20px;
        background-color:#E0F1F1;
        font-size: 20px;
        border: solid 2px;
          
      }

     .form button {
      margin-top:15px;
      width: 150px;
     }
    </style>


</head>
 <?php $error_message=''; ?>
        <h1 style="color: red;"><?php echo $error_message; ?></h1>
  

<body>
  <div style="background-color: #E0F1F1;">
<div class="form">

<form  action="#" method="POST">
  <h3>Fill The Form For Personal Shifting</h3>
  <div class="form-group">

    <label>Enter Your  Name</label>
    <label style="color: red;">*</label>
    <input type="text" class="form-control" placeholder="Enter Company Name" id="name" name="d_name">
    <label id="err_name" style="color: red; font-size: 12px;"></label>
  </div>
<div class="form-group">
  <label>Enter your Phone Number</label>
  <label style="color: red; font-size: 20px;">*</label>
  <input type="tel" class="form-control" placeholder="Enter Phone Number" id="number" name="d_number">
  <label style="color: red; font-size: 12px;" id="err_number"></label>
</div>

 

  <div class="form-group">
    <label>Enter Your Email</label>
    <label style="color: red;">*</label>
    <input type="email" class="form-control"  placeholder="Enter Email" id="email" name="d_email">
    <label id="err_email" style="color: red; font-size: 12px;"></label>
  </div>

  <div class="form-group">
    <label>Date Of Shifting</label>
    <label style="color: red;">*</label>
    <input type="date" class="form-control"  id="date" name="d_date">
    <label id="err_date" style="color: red; font-size: 12px;"></label>
   </div>

  <div class="container text-center">
  <div class="row">
  <div class="col">
  <div class="form-group">
   
      <label>Pickup</label>
      <label style="color: red;">*</label>
      <input type="text" class="form-control"  placeholder="Pickup Location" id="picklocation" name="d_pickup">
       <label id="err_piclocation" style="color: red; font-size: 12px;"></label>
  </div>
  </div>
  <div class="col">
  <div class="form-group">
     <label>Drop</label>
     <label style="color: red;">*</label>
     <input type="text" class="form-control"  placeholder="Drop Location" id="droplocation" name="d_drop">
    <label id="err_droplocation" style="color: red; font-size: 12px;"></label>
  </div>
  </div>
    <div class="form-group">
    <label>Total Distance</label>
    <label style="color: red;">*</label>
    <input type="text" class="form-control" placeholder="Enter the total kilometar" id="distance" name="distance">
    <label id="err_date" style="color: red; font-size: 12px;"></label>
   </div>
</div>
</div>



  <button type="submit" name="submit" class="btn btn-primary" onclick=  "return validateshifting()" style="width: 300px; margin-left: 20px;">Submit</button>
</form>
</div>
  
</div> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"crossorigin="anonymous">

          
        </script> 
      
</body>
</html>

<?php include "footer.html" ?>
<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
    $name = $_POST['d_name'];
    $number = $_POST['d_number'];
    $email = $_POST['d_email'];
    $date = $_POST['d_date'];
    $pickup = $_POST['d_pickup'];
    $drop = $_POST['d_drop'];
    $distance = $_POST['distance'];


    // Input validation should be added here

$tk = '';
$id = '';
$total_tk = 0;
$BusyDate='';


if(isset($_GET['tk']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $tk = intval($_GET['tk']);
    $total_tk = $distance * $tk;
}
 $check="SELECT * FROM personal_shifting";

    $result=mysqli_query($database_connection,$check);
    $row=mysqli_num_rows($result);

    if ($row > 0) {
      while ($data=mysqli_fetch_assoc($result)) {
      	 $BusyDate=$data['dates'];

        }

   


if($date==$BusyDate) {
	 $error_message= " Right Now This driver Not avalabile!";
}

else{
$insert_data = "INSERT INTO personal_shifting(names, phone_number, email, dates, pickup, drops, distance, notification, driver_id, total_tk) 
               VALUES ('$name', '$number', '$email', '$date', '$pickup', '$drop', $distance, 1, $id, $total_tk)";

     $runQuery=mysqli_query($database_connection,$insert_data);

           if ($runQuery==true) {
           	?>
  <script>
    window.location.replace('payment.php?tk=<?php echo $total_tk; ?>');
  </script>
  <?php
           }
else{
	?>
  
	<script>
		alert("Something is wrong!");
	</script>
<?php

}}}}



?>
