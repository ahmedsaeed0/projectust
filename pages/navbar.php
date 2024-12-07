<?php
include 'db.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  $logged_in = $_SESSION['logged_in'];
  $account_type = $_SESSION['account_type'];
  $username = $_SESSION['username'];
  $user_id = $_SESSION['user_id'];
} else {
  $logged_in = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar</title>

  <!-- Bootstrap Stylesheet -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    .navbar-brand h1 {
      font-size: 2rem;
      color: #007bff;
    }

    .navbar {
      background-color: #fff;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: fixed; /* تثبيت النافبار */
      top: 0;
      left: 0;
      width: 100%; /* جعلها تمتد على عرض الشاشة بالكامل */
      z-index: 1000; /* ضمان ظهورها فوق باقي العناصر */
    }

    body {
      padding-top: 80px; /* إزاحة المحتوى للأسفل بسبب النافبار الثابت */
    }

    .nav-link {
      color: #343a40 !important;
      font-weight: 500;
    }

    .nav-link.active {
      color: #007bff !important;
    }

    .nav-item .dropdown-menu {
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <h1>SAVIOUR</h1>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="#home" class="nav-link">Home</a></li>
          <li class="nav-item "><a class="nav-link " href="#job" >Jobs</a></li>
          <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
          <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
          <?php if ($logged_in): ?>
            <?php if ($account_type === 'student'): ?>
              <li class="nav-item"><a href="pages/students/student_profile.php" class="nav-link">Profile</a></li>
            <?php elseif ($account_type === 'company'): ?>
              <li class="nav-item"><a href="pages/company/profail.php" class="nav-link">Control</a></li>
            <?php elseif ($account_type === 'admin'): ?>
              <li class="nav-item"><a href="admins/admin_dashboard.php" class="nav-link">Admin Panel</a></li>
            <?php endif; ?>
            <li class="nav-item"><a href="pages/logout.php" class="nav-link">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a href="pages/login.php" class="nav-link">Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
