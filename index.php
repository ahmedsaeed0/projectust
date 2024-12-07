<?php
include 'db.php';
session_start();



if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  $logged_in = $_SESSION['logged_in'];
  $account_type = $_SESSION['account_type'];
  $username = $_SESSION['username'];
  $user_id = $_SESSION['user_id'];

} 
$specializations = [];
$query = "SELECT * FROM specializations";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specializations[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Portal</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
<style>
    body {
      font-family: 'Heebo', sans-serif;
      margin: 0;
      padding: 0;
    }
    #home{

    }

    .hero-section {
      position: relative;
      color: #fff;
      text-align: center;
    }

    .carousel-item img {
      width: 100%;
      height: 80vh;
      object-fit: cover;
    }

    .carousel-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1;
    }

    .carousel-caption {
      position: absolute;
      z-index: 2;
      top: 50%;
      transform: translateY(-50%);
    }

    .carousel-caption h1 {
      font-size: 3rem;
      font-weight: bold;
    }

    .carousel-caption p {
      font-size: 1.2rem;
    }

    .carousel-caption .btn {
      margin-top: 20px;
    }

    footer {
      background-color: rgb(11, 11, 11);
      color: white;
      padding: 20px;
    }

    .icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 60px;
    background-color: rgba(0, 123, 255, 0.1);
    border-radius: 50%;
    margin: 0 auto;
  }

  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }

  .card-title {
    font-weight: 600;
    font-size: 1.1rem;
  }

  .btn-outline-primary {
    border-radius: 20px;
    font-weight: 500;
  }
  .list-unstyled li i {
    font-size: 1.2rem;
  }

  .btn-success {
    border-radius: 20px;
    font-weight: 500;
  }

  .img-fluid {
    object-fit: cover;
  }

  .rounded {
    border-radius: 10px;
  }

  .shadow-sm {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  .border-12{
    
    margin-top:30px;
  }
  #job{
    margin-bottom:120px;
  }
  .bk{
    background: rgb(11, 11, 11);
    padding: 40px;
    color:white;
  }
  .next{
    margin-top: 90px;
  }
  h5 {
    margin-top: 10px;
    font-weight: 600;
  }

  .text-muted {
    font-size: 0.9rem;
  }

  .text-primary {
    font-weight: 500;
  }
</style>
</head>

<body>
  <!-- Navbar -->
  <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">SAVIOUR</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Jobs</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav> -->
<?php include 'pages/navbar.php'; ?>


  <div class="hero-section" id="home">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="carousel-overlay"></div>
          <img src="img/s1.jpg" alt="Slide 1">
          <div class="carousel-caption">
            <h1>Find Your Dream Job</h1>
            <p>Thousands of opportunities await you.</p>
            <a href="#job" class="btn btn-primary">Explore Categories</a>
          </div>
        </div>
        <div class="carousel-item">
          <div class="carousel-overlay"></div>
          <img src="img/s2.jpg" alt="Slide 2">
          <div class="carousel-caption">
            <h1>Step Towards Success</h1>
            <p>Join us today and make a difference.</p>
            <a href="#job" class="btn btn-success">Get Started</a>
          </div>
        </div>
        <div class="carousel-item">
          <div class="carousel-overlay"></div>
          <img src="img/s3.jpg" alt="Slide 3">
          <div class="carousel-caption">
            <h1>Unlock Your Potential</h1>
            <p>Discover amazing job opportunities.</p>
            <a href="#job" class="btn btn-warning">Learn More</a>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>

  <!-- Categories Section -->
  <div class="container" id="job">
    <h2 class="text-center next mb-4">Popular Job Categories</h2>
    <p class="text-center text-muted">2020 jobs live - 293 added today.</p>
    <div class="row g-4 justify-content-center">
      <?php foreach ($specializations as $category): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <div class="card text-center shadow-sm border-0 p-3">
            <div class="card-body">
              <!-- أيقونة التصنيف -->
              <div class="icon mb-3">
                <i class="bi bi-briefcase text-primary" style="font-size: 2rem;"></i>
              </div>
              <h5 class="card-title text-dark mb-2">
                <?php echo htmlspecialchars($category['specialization_name']); ?>
              </h5>
              <p class="text-muted mb-3">
                <?php
                // عرض عدد الوظائف المفتوحة (ب ارقام  عشوائية هنا )
                echo rand(2, 100) . " open positions";
                ?>
              </p>
              <a href="pages/category.php?id=<?php echo $category['id']; ?>" class="btn btn-outline-primary btn-sm">Explore</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
</div>
<div class="bk" id="about">
<div class="container my-5">
  <div class="row align-items-center">
    <!-- جزء الصور -->
    <div class="col-lg-6">
      <div class="row g-3">
        <div class="col-6">
          <img src="img/s3.jpg" alt="Image 1" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-6">
          <img src="img/s1.jpg" alt="Image 2" class="img-fluid rounded shadow-sm mb-3">
          <img src="img/s2.jpg" alt="Image 3" class="img-fluid rounded shadow-sm">
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <h2 class="mb-4">We Help To Get The Best Job And Find A Talent</h2>
      <p class=" mb-4">
        Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. 
        Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet.
      </p>
      <ul class="list-unstyled">
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Tempor erat elitr rebum at clita</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Aliqu diam amet diam et eos</li>
        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Clita duo justo magna dolore erat amet</li>
      </ul>
      <a href="#about" class="btn btn-success mt-3">Read More</a>
    </div>
  </div>
</div>
</div>
<div class="container my-5" id="contact">
  <div class="row text-center">
    <h2 class="mb-5">Contact Us</h2>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="d-flex flex-column align-items-center">
        <div class="icon mb-3">
          <!-- <i class="bi bi-geo-alt-fill text-primary" style="font-size: 2rem;"></i>
            -->
            <i class="fa-solid fa-location-dot"></i>
        </div>
        <h5>Address</h5>
        <p class="text-muted">329 Queensberry Street, North Melbourne VIC 3051, Australia.</p>
      </div>
    </div>

    <!-- الهاتف -->
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="d-flex flex-column align-items-center">
        <div class="icon mb-3">
          <!-- <i class="bi bi-telephone-fill text-primary" style="font-size: 2rem;"></i> -->
          <i class="fa-solid fa-phone"></i>
          
          
        </div>
        <h5>Call Us</h5>
        <p class="text-muted">
          <a href="tel:1234567890" class="text-primary text-decoration-none">123 456 7890</a>
        </p>
      </div>
    </div>

    <!-- البريد الإلكتروني -->
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="d-flex flex-column align-items-center">
        <div class="icon mb-3">
          <!-- <i class="bi bi-envelope-fill text-primary" style="font-size: 2rem;"></i> -->
          <i class="fa-solid fa-envelope"></i>
        </div>
        <h5>Email</h5>
        <p class="text-muted">
          <a href="mailto:contact.london@example.com" class="text-primary text-decoration-none">contact.ust@example.com</a>
        </p>
      </div>
    </div>
  </div>
</div>








  <!-- Footer -->
  <footer class="text-center">
    <p>&copy; 2024 SAVIOUR. All rights reserved.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
