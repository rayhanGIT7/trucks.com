<?php 


include 'db_connection.php';

$show = "";
$user_id ='rayhan7700';
$correct_password = 'rayhan7700';

if (isset($_POST['submit'])) {
    $user_number = $_POST['user_number'];
    $password = $_POST['password'];
   
    if ($user_number == $user_id && $password == $correct_password) {
        header("Location: user_information.php");
        exit();
    } else {
        $show = "Invalid username or password!";
    }
}
?>









<!DOCTYPE html>
<html>
<head>
	<title>admin_login</title>
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
 
 
  color: white;
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

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
  background-color:#F5F6FC ;
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
<body background="index.jpg">
<div >

<form action ="#"  method="POST">
  <h1>Admin Login</h1>
  <div class="form-group">
    <label for="username">User-Id</label>
    <input type="text"  name="user_number" required>
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

 
  <p></p>
  <button type="submit" name="submit">Login</button>
  

</form>

     </div>
</body>


 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"crossorigin="anonymous">
          
        </script> 
</html>

