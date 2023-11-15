<?php include "navigation.php" ?>




<?php
 include 'db_connect.php'; 

$show_num=" ";
$show_em="";
$show_li="";

$is_error=0;

if (isset($_POST['submit'])) {


  $name = $_POST['d_name'];
  $number = $_POST['d_number'];
  $email = $_POST['d_email'];
  $address = $_POST['d_address'];
  $license = $_POST['d_license'];

  $license_photo = $_FILES['d_license_photo']['name'];
  $temp_name = $_FILES['d_license_photo']['tmp_name'];
  $upload_folder = 'upload_license_image/';
  $uniq_image_name = uniqid();
  move_uploaded_file($temp_name, $upload_folder . "$uniq_image_name.jpg");




  $truck_size = $_POST['d_truck_size'];

  $truck_photo = $_FILES['d_truck_photo']['name'];
  $temp_name2 = $_FILES['d_truck_photo']['tmp_name'];
  $upload_folder2 = "upload_truck_image/";
  $uniq_image_name_for_truck = uniqid();

  move_uploaded_file($temp_name2, $upload_folder2."$uniq_image_name_for_truck.jpg");



  $truck_weight = $_POST['d_truck_weight'];
  $charge_tk = $_POST['d_charge_tk'];
  $password = $_POST['d_password'];
  $authentication_pwd=md5(sha1($password));


  $check=" SELECT * FROM driver_registration WHERE phone_number = '$number' OR  email = '$email' OR driving_license = '$license'";

  $result=mysqli_query($database_connection, $check );
   $row=mysqli_num_rows($result);
  if ($row > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
      if ($data['phone_number'] == $number) {
        $show_num = 'Number Already Exists. Try with another one!';
        break;
      }
      else if($data['email']==$email){
        $show_em = 'Number Already Exists. Try with another one!';
        break;
      }
      else if($data['driving_license']==$license){
        $show_li = 'Number Already Exists. Try with another one!';
      }
    }
  }
  

//   if($check=" SELECT * FROM driver_registration WHERE phone_number = '$number'"){
//     $result=mysqli_query($database_connection, $check );
//     $row=mysqli_num_rows($result);
//      if ($row > 0) {
//       $show_num = 'Number Already Exists. Try with another one!';
//     }
//   }
//  else if($check=" SELECT * FROM driver_registration WHERE email = '$email'"){
//     $result=mysqli_query($database_connection, $check );
//     $row=mysqli_num_rows($result);
//     if ($row > 0) {
//       $show_em = 'Number Already Exists. Try with another one!';
//     }

//   }

//   else if($check=" SELECT * FROM driver_registration WHERE driving_license = '$license'"){
//     $result=mysqli_query($database_connection, $check );
//     $row=mysqli_num_rows($result);
//     if ($row > 0) {
//       $show_li = 'Number Already Exists. Try with another one!';
//     }

//   }
    
else {

 
    
  $data_insert = "INSERT INTO driver_registration (names, phone_number, email, stand_address, driving_license, license_photo, truck_size, truck_photo, weight_capacity, tk, passwords)
  VALUES ('$name', '$number', '$email', '$address', '$license','$uniq_image_name.jpg', '$truck_size', '$uniq_image_name_for_truck.jpg', '$truck_weight', '$charge_tk', '$authentication_pwd')";
  

    if (mysqli_query($database_connection, $data_insert) == true) {
      echo "<script>
      alert('Successfully')
      </script>";
      header("location:driver_login.php");
    } else {
      echo "Some thing wrong";
    }
  }
  
}






?>




<!DOCTYPE html>
<html>

<head>
  <title>driver signup</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">



  <script> 
    //phone number verifivation
    function validatePhoneNumber(phoneNumber) {
      var phonePattern = /^\d{11}$/;
      return phonePattern.test(phoneNumber);
    }


    function validateForm() {


      var name = document.getElementById("name").value;
      var phone = document.getElementById("number").value;
      var email = document.getElementById("email").value;
      var address = document.getElementById("address").value;
      var license = document.getElementById("license").value;
      var license_photo = document.getElementById("license_photo").value;
      var trucksize = document.getElementById("trucksize").value;
      var truckweigth = document.getElementById("truckweigth").value;
      var truck_photo = document.getElementById("truck_photo").value;
      var chargeTK = document.getElementById("chargeTK").value;
      var password = document.getElementById("password").value;

      // Check if fields are empty


      
      if (name == "") {
        document.getElementById('err_name').innerHTML = "Please enter your name!";
      } else if (!validatePhoneNumber(phone)) {
        document.getElementById('err_phone').innerHTML = "Please enter a valid phone number!";
      } 
      else if (email == "") {
        document.getElementById('err_email').innerHTML = "Please enter your email address!";
      } else if (address == "") {
        document.getElementById('err_address').innerHTML = "Please enter address!";
      } else if (license == "") {
        document.getElementById('err_license').innerHTML = "Please enter your license number!";
      } else if (license_photo == "") {
        document.getElementById('err_license_photo').innerHTML = "Please upload a photo of your license!";
      } else if (trucksize == "Choose option") {
        document.getElementById('err_trucksize').innerHTML = "Please select your truck size!";
      } else if (truckweigth == "Choose option") {
        document.getElementById('err_truckweight').innerHTML = "Please select your truck Weight!";
      } else if (truck_photo == "") {
        document.getElementById('err_truck_photo').innerHTML = "Please upload your truck's photo!"
      } else if (chargeTK == "") {
        document.getElementById('err_charge').innerHTML = "Please upload a photo of your truck!";
      } else if (password == "") {
        document.getElementById('err_password').innerHTML = "Please enter a password!";
      } else if (password.length < 8) {
        document.getElementById('err_password').innerHTML = "Password must be at least 8 characters long!";
      } else if (!password.match(/[A-Z]/)) {
        document.getElementById('err_password').innerHTML = "Password must contain at least one uppercase letter!";


      } else {
        return true;
      }

      return false;
    }


    function show() {
      var pass = document.getElementById('password');
      if (pass.type == "password") {
        pass.type = "text";
      } else {
        pass.type = "password";
      }


    }
  </script>
 



  <style>
    .form {
      width: 700px;
      height: 97%;

      text-align: center;
      margin-left: 420px;
      margin-top: 20px;
      background-color:#DADFF6 ;
      font-size: 20px;
      border: solid 2px;



    }

    .form button {
      margin-top: 50px;
      width: 150px;

    }

    .form h1 {
      text-underline-position: under;
    }

    .form label {
      margin-top: 10px;
    }
  </style>


</head>

<body>
  <div style="background-color: #E0F1F1;">

    <div class="form">

      <form action="" method="POST" enctype="multipart/form-data">
        <h1>Driver Registration Here</h1>
        <div class="container text-center">
          <div class="row">
            <div class="col-6">

              <div class="form-group">

                <label>Enter Your Name</label>
                <label style="color:red;font-size:20px;">*</label>
                <input type="text" class="form-control" placeholder="Enter Name " id="name" name="d_name">
                <label style="color:red;font-size:12px;" id="err_name"> </label>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label>Enter your Phone Number</label>
                <label style="color:red;font-size:20px;">*</label>
                <input type="tel" class="form-control" placeholder="Enter Phone Number"id="number" name="d_number">
                <label style="color:red;font-size:12px;" id="err_phone"><?php echo $show_num; ?> </label>
              </div>
            </div>
          </div>
        </div>
        <div class="container text-center">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Enter your Email</label>
                <label style="color:red;font-size:20px;">*</label>
                <input type="email" class="form-control" placeholder="Enter Email" id="email" name="d_email">
                <label style="color:red;font-size:12px;" id="err_email"><?php  echo $show_em; ?>  </label>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label>Truck Stand Address</label>
                <label style="color:red;font-size:20px;">*</label>
                <input type="text" class="form-control" placeholder="Enter Address" id="address" name="d_address">
                <label style="color:red;font-size:11px;" id="err_address"> </label>
              </div>
            </div>
          </div>


          <div class="container text-center">
            <div class="row">
              <div class="col-6">

                <div class="form-group">
                  <label>Driving License </label>
                  <label style="color:red;font-size:20px;">*</label>
                  <input type="text" class="form-control" placeholder="Enter Driving License Number" id="license" name="d_license">
                  <label style="color:red;font-size:12px;" id="err_license"><?php  echo $show_li; ?> </label>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <label> Upload Driving License Image </label>
                  <label style="color:red;font-size:20px;">*</label>
                  <input type="file" class="form-control" id="license_photo" name="d_license_photo">
                  <label style="color:red;font-size:12px;" id="err_license_photo"> </label>
                </div>
              </div>
            </div>
          </div>


          <div class="container text-center">
            <div class="row">
              <div class="col-6">

                <div class="form-group">
                  <label>Select Truck Size</label>
                  <label style="color:red;font-size:20px;">*</label>
                  <select class="form-select" id="trucksize" name="d_truck_size">
                    <option value="Choose option">Choose option</option>
                    <option value="7 Feet">7 Feet</option>
                    <option value="9 Feet">9 Feet</option>
                    <option value="12 Feet">12 Feet</option>
                    <option value="15 Feet">15 Feet</option>
                    <option value="16 Feet">16 Feet</option>
                    <option value="18 Feet">18 Feet</option>
                    <option value="20 Feet">20 Feet</option>
                    <option value="23 Feet">23 Feet</option>
                    <option value="26 Feet">26 Feet</option>
                  </select>
                  </select>
                  <label style="color:red;font-size:12px;" id="err_trucksize"></label>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <label>Upload Your Truck Image</label>
                  <label style="color:red;font-size:20px;">*</label>
                  <input type="file" class="form-control" id="truck_photo" name="d_truck_photo">
                  <label style="color:red;font-size:12px;" id="err_truck_photo"></label>
                </div>
              </div>
            </div>
          </div>

          <div class="container text-center">
            <div class="row">
              <div class="col-6">

                <div class="form-group">
                  <label>Select Weight capacity</label>
                  <label style="color:red;font-size:20px;">*</label>
                  <select class="form-select" id="truckweigth" name="d_truck_weight">
                    <option value="Choose option">Choose option</option>
                    <option value="1 Ton">1 Ton</option>
                    <option value="1.5 Ton">1.5 Ton</option>
                    <option value="2 Ton">2 Ton</option>
                    <option value="3.5 Ton">3.5 Ton</option>
                    <option value="7.5 Ton">7.5 Ton</option>
                    <option value="15 Ton">15 Ton</option>
                    <option value="25 Ton">25 Ton</option>
                  </select>

                  <label style="color:red;font-size:12px;" id="err_truckweight"></label>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <label>Charge per kilometer ?(TK)</label>
                  <label style="color:red;font-size:20px;">*</label>
                  <input type="tel" class="form-control" id="chargeTK" name="d_charge_tk">
                  <label style="color:red;font-size:12px;" id="err_charge"></label>
                </div>
              </div>
            </div>
          </div>

          <div class="container text-center">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Enter the Password</label>
                  <label style="color:red;font-size:20px;">*</label>
                  <input type="Password" class="form-control" placeholder="Enetr Password" id="password" name="d_password">
                  <label style="color:red;font-size:12px;" id="err_password"></label>
                </div>
              </div>

              <div class="col-4">
                <div class="form-group">
                  <br>
                  <input type="checkbox" onclick="show()"> Show password
                </div>

              </div>
            </div>
          </div>

          <button type="submit" name="submit" class="btn btn-primary" onclick=" return validateForm()" style="width: 300px; margin-left: 20px;">Submit</button>
      </form>

    </div>


  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">

  </script>
</body>

</html>

<?php include "footer.html";

// <input type="tel" class="form-control" 
//   if($is_error==1){ echo "placeholder='$show'"; }else{
// echo "placeholder='Enter Phone Number'";
//   }

//   id="number" name="d_number">

?>


