<?php include "navigation.php" ?>
<?php
include 'db_connect.php';


$smtp_host = '';
$smtp_port = 25;
$from_email = 'rrrayhan273@gmail.com';

if (isset($_POST['submit'])) {
  $email = $_POST['email'];

  $sql = "SELECT * FROM driver_registration WHERE email='$email'";
  $run_query = mysqli_query($database_connection, $sql);
  $row = mysqli_fetch_array($run_query);

  $email_count = $row['email'];
  $password = $row['passwords'];
  $body = "Email recovery";
  $msg = "Your password is: $password";
  $header = "From:$from_email";

  ini_set('SMTP', $smtp_host);
  ini_set('smtp_port', $smtp_port);

  if (mail($email_count, $body, $msg, $header)) {
    echo "<script>alert(Successfully send mail )</script>";
  }
}

?>




<!DOCTYPE html>
<html>

<head>
  <title>driver_login</title>
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
  <div>

    <form action="#" method="POST">
      <h4>Password Recover</h4>
      <div class="form-group">
        <label for="username">UserEmail</label>
        <input type="email" name="email" required>
      </div>

      <button type="submit" name="submit">recovery</button>

    </form>

  </div>
</body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">

</script>

</html>

<?php include "footer.html" ?>