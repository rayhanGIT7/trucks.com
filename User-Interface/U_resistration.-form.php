<?php include "navigation.php" ?>
<!DOCTYPE html>
<html>
<head>
  <title>user resistation</title>

<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/"> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

 

<script>

function validateshifting() {

  var name = document.getElementById('name').value;
  var number = document.getElementById('number').value;
  var email = document.getElementById('email').value;
  var gender = document.querySelector('input[name="gender"]:checked');
  var password = document.getElementById('password').value;


  var nameError = '';
  var numberError = '';
  var emailError = '';
  var genderError = '';
  var passwordError = '';


  if (name== '') {
    nameError = 'Name is required';
  }


  if (number== '') {
    numberError = ' Valid Phone number is required';
  } else if (!/^[0-9]{11}$/.test(number)) {
    numberError = 'Phone number must be 10 digits';
  }

  if (email== '') {
    emailError = 'Email is required';
  } 

 
  if (gender) {
    genderError = 'Gender is required';
  }

 
  if (password == '') {
    passwordError = 'Password is required';
  } else if (password.length < 6) {
    passwordError = 'Password must be at least 6 characters';
  }else if (!password.match(/[A-Z]/)) {
   passwordError  = "Password must contain at least one uppercase letter!";
 }


  document.getElementById('err_name').innerText = nameError;
  document.getElementById('err_number').innerText = numberError;
  document.getElementById('err_email').innerText = emailError;
  document.getElementById('err_gender').innerText = genderError;
  document.getElementById('err_password').innerText = passwordError;

  
  return !(nameError || numberError || emailError || genderError || passwordError);
}


 
   function showpass()
    {
        var pass = document.getElementById('password');
        if (pass.type=="password") {
          pass.type="text";
        } else{
          pass.type="password";
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
<body>
  <div style="background-color: #E0F1F1;">
<div class="form">

<form  action="#" method="POST" style="padding: 5px;">
  <h3>User Registration Here</h3>
  <div class="form-group">

    <label>Enter Your  Name</label>
    <label style="color: red;">*</label>
    <input type="text" class="form-control" placeholder="Enter Company Name" id="name" name="u_name">
    <span id="err_name" style="color: red; font-size: 12px;"></span>
  </div>
<div class="form-group">
  <label>Enter your Phone Number</label>
  <label style="color: red; font-size: 20px;">*</label>
  <input type="tel" class="form-control" placeholder="Enter Phone Number" id="number" name="u_number">
  <label style="color: red; font-size: 12px;" id="err_number"></label>
</div>

 

  <div class="form-group">
    <label>Enter Your Email</label>
    <label style="color: red;">*</label>
    <input type="email" class="form-control"  placeholder="Enter Email" id="email" name="u_email">
    <label id="err_email" style="color: red; font-size: 12px;"></label>
  </div>
  <div class="form-group">
 <label>Gender:</label>
  <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio"id="male" value="Male" name="u_gender">Male
   
  </div>
  <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio"  id="female" value="Female" name="u_gender">Female
  </div>
  <label style="color:red;font-size:12px;" id="err_gender"></label>
 </div>


<div class="form-group">
    <label>Enter the Password</label>
    <label style="color:red;font-size:20px;">*</label>
    <input type="Password" class="form-control"  placeholder="Enter Password" id="password" name="u_password">
    <label style="color:red;font-size:12px;" id="err_password"></label>
</div>



<div class="form-group">
    
    <input type="checkbox" onclick="showpass()"> Show password
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
  $name = $_POST['u_name'];
  $number = $_POST['u_number'];
  $email = $_POST['u_email'];
  $gender = $_POST['u_gender']; 
  $password = $_POST['u_password'];
  $authentication_pwd=md5(sha1($password));

  $insert_data = "INSERT INTO user_registration (names, phone_number, email, gender, passwords) 
                  VALUES ('$name', '$number', '$email', '$gender', '$authentication_pwd')";

  if (mysqli_query($database_connection, $insert_data)) {
     
     ?>
 
<script>
    window.location.replace('user_login.php');
</script>

<?php
  } else {
    echo "Something went wrong while inserting into the database: ";
  }
}



  
?>