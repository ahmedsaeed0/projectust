<?php
include 'db.php';
session_start();



if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  $logged_in = $_SESSION['logged_in'];
  $account_type = $_SESSION['account_type'];
  $username = $_SESSION['username'];
  $user_id = $_SESSION['user_id'];

} 
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Index</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Styles -->
  <link href="css/style.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }

    .carousel-item img {
      width: 100%;
      height: 400px;
      object-fit: cover;
    }

    .card {
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: scale(1.05);
    }

    footer {
      background-color: rgb(11, 11, 11);
      color: white;
    }

    .footer img {
      width: 100%;
      height: auto;
    }
  </style>
</head>

<body>
  <div class="container-xxl bg-white p-0">
    <!-- Include Navbar -->
    <?php include 'pages/navbar.php'; ?>

    <!-- Carousel Section -->
    <div class="banner_section layout_padding">
      <div id="my_slider" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="img/s1.jpg" alt="Slide 1">
          </div>
          <div class="carousel-item">
            <img src="img/s2.jpg" alt="Slide 2">
          </div>
          <div class="carousel-item">
            <img src="img/s3.jpg" alt="Slide 3">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#my_slider" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#my_slider" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
      </div>
    </div>

    <!-- Cards Section -->
    <div class="container my-5">
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="card">
            <img src="img/computer.png" class="card-img-top" alt="Computer">
            <div class="card-body text-center">
              <h5 class="card-title">حاسوب</h5>
              <a href="tsnif.html" class="btn btn-primary">عرض</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <img src="img/dectot.jpg" class="card-img-top" alt="Medicine">
            <div class="card-body text-center">
              <h5 class="card-title">طب</h5>
              <a href="tsnif.html" class="btn btn-primary">عرض</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <img src="img/transleat.jpg" class="card-img-top" alt="Translation">
            <div class="card-body text-center">
              <h5 class="card-title">ترجمة</h5>
              <a href="tsnif.html" class="btn btn-primary">عرض</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <img src="img/picter.png" class="card-img-top" alt="Engineering">
            <div class="card-body text-center">
              <h5 class="card-title">هندسة</h5>
              <a href="tsnif.html" class="btn btn-primary">عرض</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <img src="img/picter.png" class="card-img-top" alt="Photography">
            <div class="card-body text-center">
              <h5 class="card-title">تصوير</h5>
              <a href="tsnif.html" class="btn btn-primary">عرض</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <img src="img/محامي.png" class="card-img-top" alt="Law">
            <div class="card-body text-center">
              <h5 class="card-title">قانون</h5>
              <a href="tsnif.html" class="btn btn-primary">عرض</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="text-center">
      <div class="container p-4">
        <section>
          <div class="row">
            <div class="col-lg-2">
              <img src="img/img-8.png" alt="">
            </div>
            <div class="col-lg-2">
              <img src="img/im0.png" alt="">
            </div>
            <div class="col-lg-2">
              <img src="img/im3.png" alt="">
            </div>
            <div class="col-lg-2">
              <img src="img/im4.png" alt="">
            </div>
            <div class="col-lg-2">
              <img src="img/im9.png" alt="">
            </div>
            <div class="col-lg-2">
              <img src="img/im3.png" alt="">
            </div>
          </div>
        </section>
        <p class="mt-4">&copy; 2024 SAVIOUR. All rights reserved.</p>
      </div>
    </footer>
  </div>

  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
