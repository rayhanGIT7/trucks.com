<?php 

 
 include 'db_connect.php'; 
$show="";

if(isset($_REQUEST['submit'])){

    $user_number=$_POST['user_number'];
   $password =$_POST['password'];
   
    $authentication_pwd=md5(sha1($password));

 
    
  $insert_data = "INSERT INTO user_login (numbers, password) 
                  VALUES ( '$user_number',  '$authentication_pwd')";


  if (mysqli_query($database_connection, $insert_data)) {

    $read_number_and_password="SELECT * FROM user_registration WHERE phone_number='$user_number' AND passwords='$authentication_pwd' ";

    $run= mysqli_query($database_connection, $read_number_and_password);
    $count=mysqli_num_rows($run);
    
    if($run==true){
      if($count>0){
       
    $expiration_time = time() + (86400 * 7); 


setcookie("current_user", "$user_number", $expiration_time);
setcookie("current_user_id", "value2", $expiration_time);




           setcookie("current_user",$user_number,time()+(86400*7));
           header("Location:truck-catagory.php");
        
        
    }} else {

        $show= "Invalid username or password!";
    }
}
}


?>

<?php include "navigation.php" ?>
<!DOCTYPE html>
<html>
<head>
	<title>user_login</title>
	 <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>
    	form {
  width: 300px;
  margin: 0 auto;
  padding: 20px;
  border: 2px solid orange;
  border-radius: 5px;
  text-align: center;
  margin-top: 50px;
  margin-bottom: 200px;
  background-color: #dddd;
}

h2 {
  margin-top: 0;
}

.form-group {
  margin: 10px 0;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input[type="tel"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

button[type="submit"] {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background-color: #007bff;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

button[type="submit"]:hover {
  background-color: #0062cc;
}

    </style>
</head>
<body>
<div >

<form action ="#"  method="POST">
  <h2>User's Login</h2>
  <div class="form-group">
    <label for="username">User Number</label>
    <input type="tel"  name="user_number" required>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password"  name="password" id="password_show">
    <label style="color:red;"><?php echo $show; ?></label>
  </div>

  <div class="form-group">
    <input type="checkbox" onclick="showpass()"> Show password

    <script>
       function showpass()
    {
        var pass = document.getElementById('password_show');
        if (pass.type=="password") {
          pass.type="text";
        } else{
          pass.type="password";
        }
        
        
    }
    </script>
</div>

  <a href="pass_recovery.php">Forgat Password?</a>
  <p></p>
  <button type="submit" name="submit">Login</button>
  <li class="nav-item dropdown" style="list-style: none;">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Not Number? Signup
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" aria-current="page" href="D_resistation-form.php">Driver</a></li>
         
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item"  aria-current="page" href="U_resistration.-form.php">User</a></li>
          </ul>
        </li>


</form>

     </div>
</body>


 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"crossorigin="anonymous">
          
        </script> 
</html>

<?php include "footer.html" ?>