<?php
include '../db.php';
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  $logged_in = $_SESSION['logged_in'];
  $account_type = $_SESSION['account_type'];
  $username = $_SESSION['username'];
  $user_id = $_SESSION['user_id'];
} else {
  $logged_in = false;
}
$student_id = $_SESSION['user_id'];

// التحقق من التصنيف
$specialization_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($specialization_id <= 0) {
    header("Location: index.php");
    exit();
}

// جلب اسم التصنيف
$query = "SELECT specialization_name FROM specializations WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $specialization_id);
$stmt->execute();
$result = $stmt->get_result();
$specialization = $result->fetch_assoc();
$stmt->close();

if (!$specialization) {
    header("Location: index.php");
    exit();
}

$specialization_name = $specialization['specialization_name'];

// التحقق من تقديم الطلب
if (isset($_GET['apply_ad_id']) && isset($_GET['job_id'])) {
    $ad_id = intval($_GET['apply_ad_id']); // رقم الإعلان
    $job_id = intval($_GET['job_id']); // رقم الوظيفة
    $student_id = $_SESSION['user_id']; // رقم الطالب من الجلسة
    $status = 0; // الحالة الافتراضية

    // التحقق من وجود تقديم مسبق
    $check_query = "SELECT * FROM job_applications WHERE student_id = ? AND ad_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $student_id, $ad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // إذا كان هناك تقديم مسبق
        $success_message = "تم التقديم مسبقًا لهذا الإعلان.";
    } else {
        // إدخال طلب جديد
        $insert_query = "INSERT INTO job_applications (student_id, job_id, ad_id, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        // التحقق من أن القيم متوفرة
        if ($stmt) {
            $stmt->bind_param("iiis", $student_id, $job_id, $ad_id, $status);
            if ($stmt->execute()) {
                $success_message = "تم التقديم بنجاح.";
            } else {
                $success_message = "حدث خطأ أثناء التقديم: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $success_message = "حدث خطأ في إعداد الاستعلام.";
        }
    }
}

// جلب الإعلانات مع البيانات المرتبطة
$ads = [];
$query = "
    SELECT 
        ads.*, 
        jobs.salary, 
        jobs.job_title 
    FROM ads
    JOIN jobs ON ads.job_id = jobs.id
    WHERE jobs.specialization = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $specialization_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $ads[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($specialization_name); ?> - Ads</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Bootstrap Stylesheet -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Stylesheet -->
  <link href="../css/style.css" rel="stylesheet">
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
          <li class="nav-item"><a href="../index.php" class="nav-link">Home</a></li>
          <li class="nav-item "><a class="nav-link " href="../index.php?#job" >Jobs</a></li>
          <li class="nav-item"><a href="../index.php?#about" class="nav-link">About</a></li>
          <li class="nav-item"><a href="../index.php?#contact" class="nav-link">Contact</a></li>
          <?php if ($logged_in): ?>
            <?php if ($account_type === 'student'): ?>
              <li class="nav-item"><a href="students/student_profile.php" class="nav-link">Profile</a></li>
            <?php elseif ($account_type === 'company'): ?>
              <li class="nav-item"><a href="company/profail.php" class="nav-link">Control</a></li>
            <?php elseif ($account_type === 'admin'): ?>
              <li class="nav-item"><a href="../admins/admin_dashboard.php" class="nav-link">Admin Panel</a></li>
            <?php endif; ?>
            <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container-xxl bg-white p-0">
    <!-- Navbar -->

    <!-- Ads Section -->
    <div class="container my-5">
      <div id="tab-1" class="tab-pane fade show p-0 active">
        <?php if (!empty($success_message)): ?>
          <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <h2 class="text-center mb-4">إعلانات التصنيف: <?php echo htmlspecialchars($specialization_name); ?></h2>
        <div class="tab-content">
          <?php if (!empty($ads)): ?>
            <?php foreach ($ads as $ad): ?>
              <div class="job-item p-4 mb-4">
                <div class="row g-4">
                  <div class="col-sm-12 col-md-8 d-flex align-items-center">
                    <img class="flex-shrink-0 img-fluid border rounded" src="img/com-logo-1.jpg" alt="" style="width: 80px; height: 80px;">
                    <div class="text-start ps-4">
                      <h5 class="mb-3"><?php echo htmlspecialchars($ad['job_title']); ?></h5>
                      <span class="text-truncate me-3"><i class="fa fa-money-bill-alt text-primary me-2"></i> ريال<?php echo htmlspecialchars($ad['salary']); ?> </span>
                      <span class="text-truncate me-3"><i class="far fa-calendar-alt text-primary me-2"></i>تاريخ الإعلان: <?php echo htmlspecialchars($ad['ad_date']); ?></span>
                      <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>ينتهي في: <?php echo htmlspecialchars($ad['apply_end_date']); ?></span>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                    <div class="d-flex mb-3">
                      <a class="btn btn-light btn-square me-3" href="#"><i class="far fa-heart text-primary"></i></a>
                      <a class="btn btn-primary" href="?id=<?php echo $specialization_id; ?>&apply_ad_id=<?php echo $ad['id']; ?>&job_id=<?php echo $ad['job_id']; ?>">قدم الآن</a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-center">لا توجد إعلانات مرتبطة بهذا التصنيف.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-white" style="background-color:rgb(11, 11, 11);">
      <div class="container p-4">
        <section class="">
          <div class="row">
            <div class="col-lg-2 col-md-12 mb-4 mb-md-0"><img src="img/im0.png" class="w-100"></div>
            <div class="col-lg-2 col-md-12 mb-4 mb-md-0"><img src="img/im3.png" class="w-100"></div>
            <div class="col-lg-2 col-md-12 mb-4 mb-md-0"><img src="img/im4.png" class="w-100"></div>
          </div>
        </section>
      </div>
      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">© 2024 Copyright</div>
    </footer>
  </div>

  <!-- JavaScript Libraries -->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
