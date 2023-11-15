<style>
  .nav ul li a {
    font-style: bold;

  }

  a:hover {
    background-color: #B5BBD5;
  }

  .nav {
    background-color:;
  }
</style>
<div class="nav">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <h1 class="navbar-brand" style="font-size: 20px;">TRUCK.COM</h1>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-size: 25px; margin-left: 600px;">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Sign-up
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" aria-current="page" href="D_resistation-form.php">Driver</a></li>

              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" aria-current="page" href="U_resistration.-form.php">User</a></li>
            </ul>
          </li>
       
    
            <li class="nav-item">
          <a class="nav-link active" aria-current="page"href="truck-catagory.php">Truck Catagory</a>
        </li>
          <li class="nav-item">
            <?php if (!isset($_COOKIE['current_user'])) {
              echo '<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Login
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" aria-current="page" href="driver_login.php">Driver</a></li>
           
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item"  aria-current="page" href="user_login.php">User</a></li>
            </ul>
          </li>';
            } else {
              echo ' <a class="nav-link active" aria-current="page"  href="logout.php">Logout</a>';
            ?>

          <li class="nav-item">
            <?php
            if (isset($_COOKIE['current_driver']))  {
           
            
              echo '<a class="nav-link active" aria-current="page"  href="profile.php">My Profile</a>';
              echo '<a class="nav-link active" aria-current="page"  href="logout.php">Logout</a>';

            }else{
                    echo ' <a class="nav-link active" aria-current="page"  href="logout.php">Logout</a>';
            }
            ?>
          </li>

        <?php }  ?>

       
        </li>
        </ul>

      </div>
    </div>
  </nav>

</div>