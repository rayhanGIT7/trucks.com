<?php include "navigation.php" ?>
<!DOCTYPE html>
<html>
<head>
	<title>notification</title>
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>

<h1 style="text-align: center;">Shifting Information</h1>
<?php
include 'db_connect.php';

if (isset($_COOKIE["current_driver"])) {
    $number = $_COOKIE["current_driver"];
    $check = "SELECT * FROM driver_registration WHERE phone_number=$number";

    $result = mysqli_query($database_connection, $check);
    $row = mysqli_num_rows($result);

    if ($row > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            $driver_id = $data['ID'];
        }
    }

    $notification = "SELECT * FROM personal_shifting WHERE driver_id= 47 AND notification=1";

    $stmt = mysqli_prepare($database_connection, $notification);

    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result_set = mysqli_stmt_get_result($stmt);
               $count=1;
        if ($result_set) {
            while ($data = mysqli_fetch_assoc($result_set)) {
                echo $count . ". Number Customer<br>";
                    $count++; 
                ?>
                <br>
            <table style="border-collapse: collapse; width: 100%; border: 2px solid #C0D8C6;">

    <thead>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">Name</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['names']; ?></td>
        </tr>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">Phone Number</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['phone_number']; ?></td>
        </tr>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">User Email</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['email']; ?></td>
        </tr>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">Date and Time</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['time & date']; ?></td>
        </tr>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">Pickup</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['pickup']; ?></td>
        </tr>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">Stand Address</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['drops']; ?></td>
        </tr>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">Total Distance</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['distance']; ?></td>
        </tr>
        <tr>
            <th style="border: 2px solid #C0D8C6; padding: 8px; text-align: left;">Total Taka</th>
            <td style="border: 2px solid #C0D8C6; padding: 8px;"><?php echo $data['total_tk']; ?></td>
        </tr>
    </thead>
</table>

                <?php
            }
        } else {
            // Handle query result error
        }
    } else {
        // Handle prepared statement error
    }
}
?>


</table>