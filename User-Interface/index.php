

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Truck.com</title>

     <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/"> 
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous"> 
    <!-- Bootstrap core CSS -->
    

   <style>
     .img{
     width:100%;
     height:800px;
  
     }

     .bg-banner{
      background-image: url('logo/1.jpg');
      height: 100vh;
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
     }

    </style>  
	
</head>
<body>
  

  
<!-- <marquee direction="right">Wellcome to Truck.com </marquee> -->

<?php include "navigation.php" ?>
<div class="img">

  <div id="carouselExampleCaptions" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
  <div class="carousel-item active">
      <img src="truck_image/truck1.jpg" class="d-block w-100" alt="...">
  <div class="carousel-caption d-none d-md-block">
        <h5 style="font-size: 60px;">ব্যবসায়িক মালামাল পরিবহনের এক ট্যাপ সমাধান</h5>
       
   </div>
    </div>
    <div class="carousel-item">
      <img src="truck_image/truck2.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5 style="font-size: 60px;" >
          উপভোগ করুন ঝামেলাহীন শিফটিং</h5>
       
    </div>
    </div>
    <div class="carousel-item">
     <img src="truck_image/truck3.jpg" class="d-block w-100" alt="" > 
    <div class="carousel-caption d-none d-md-block">
        <h5 style="font-size: 60px; margin-top:50%;" >
              আয় করুন আপনার ট্রাকের মাধ্যমে</h5>
        
  </div>
  </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>







<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"crossorigin="anonymous"></script> 
</body>
</html>
<?php include "footer.html" ?>