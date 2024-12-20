<?php
include '../db.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    $username = $_SESSION['admin_username'];
} else {
    header("Location: ../index.php");
    exit();
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $insert_query = "INSERT INTO specializations (specialization_name) VALUES (?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
   
    $stmt->close();
    $success_message = "تمت إضافة التصنيف بنجاح.";
   
}

// تعديل التصنيف
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $update_query = "UPDATE specializations SET specialization_name = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $category_name, $category_id);
    $stmt->execute();
    $stmt->close();
    $success_message = "تم تعديل التصنيف بنجاح.";
}

// حذف تصنيف
if (isset($_GET['delete_category'])) {
    $category_id = intval($_GET['delete_category']);
    $delete_query = "DELETE FROM specializations WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->close();
    $success_message = "تم حذف التصنيف بنجاح.";
}

// جلب التصنيفات
$categories = [];
$query = "SELECT * FROM specializations";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// جلب الوظائف
$jobs_query = "SELECT jobs.*, companies.Name as company_name FROM jobs JOIN companies ON jobs.company_id = companies.id";
$jobs_result = $conn->query($jobs_query);
$jobs = [];
while ($row = $jobs_result->fetch_assoc()) {
    $jobs[] = $row;
}
// جلب الطلبات
$applications_query = "
    SELECT 
        job_applications.id AS application_id, 
        job_applications.status AS application_status, 
        students.full_name AS student_name, 
        jobs.job_title AS job_title, 
        jobs.salary AS job_salary,
        ads.id AS ad_id, 
        ads.ad_date AS ad_date
    FROM job_applications
    JOIN students ON job_applications.student_id = students.id
    JOIN ads ON job_applications.ad_id = ads.id
    JOIN jobs ON ads.job_id = jobs.id
";

$applications_result = $conn->query($applications_query);

$applications = [];
while ($row = $applications_result->fetch_assoc()) {
    $applications[] = $row;
}

$ad_query = "SELECT ads.*,companies.Name AS z ,jobs.job_title AS x FROM ads 
    JOIN companies ON ads.company_id = companies.id 
    JOIN jobs ON ads.job_id = jobs.id ";
$ad_result = $conn->query($ad_query);
$ad = [];
while ($row = $ad_result->fetch_assoc()) {
    $ad[] = $row;
}
// print_r($ad);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            overflow-y: auto; /* لإضافة شريط تمرير للشريط الجانبي عند الحاجة */
            position: fixed; /* تثبيت الشريط الجانبي */
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .sidebar a:hover {
            color: #fff;
        }

        .sidebar .username {
            margin-bottom: 20px;
            font-size: 1.2rem;
            color: #ffc107;
        }

        .logout-btn {
            background-color: #dc3545;
            border: none;
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            font-size: 0.9rem;
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .main-content {
            margin-left: 18%; /* ترك مساحة للشريط الجانبي */
            padding: 20px;
            max-height: 100vh; /* تحديد ارتفاع المحتوى */
            overflow-y: auto; /* إضافة شريط تمرير عند الحاجة */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
            height: 150px; /* تثبيت الارتفاع */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        p {
            margin: 2px;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-white mb-4">القائمة</h4>
                <div class="username">مرحباً، <?php echo $username; ?></div>
                <a href="admin_dashboard.php">الصفحة الرئيسية</a>
                <a href="admin_user.php">إدارة المستخدمين</a>
                <a href="manage_requests.php">إدارة الطلبات</a>
                <!-- <a href="#">إعدادات</a> -->
                <a href="../index.php">العودة</a>

                <!-- زر تسجيل الخروج -->
                <a href="?logout=true" class="logout-btn">تسجيل الخروج</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="row">
                    <div class="container mt-4">
                        <h1>إدارة الطلبات</h1>

                        <!-- رسالة نجاح -->
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>

                        <!-- إضافة تصنيف جديد -->
                        <h3>إضافة تصنيف جديد</h3>
                        <form method="POST" class="mb-4">
                            <div class="mb-3">
                                <label for="category_name" class="form-label">اسم التصنيف</label>
                                <input type="text" name="category_name" id="category_name" class="form-control" required>
                            </div>
                            <button type="submit" name="add_category" class="btn btn-success">إضافة</button>
                        </form>

                        <!-- قائمة التصنيفات -->
                        <h3>قائمة التصنيفات</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم التصنيف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?php echo $category['id']; ?></td>
                                        <td><?php echo htmlspecialchars($category['specialization_name']); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                                <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['specialization_name']); ?>" required>
                                                <button type="submit" name="update_category" class="btn btn-sm btn-primary">تعديل</button>
                                            </form>
                                            <a href="?delete_category=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- قائمة الوظائف -->
                        <h3>قائمة الوظائف</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان الوظيفة</th>
                                    <th>وصف الوظيفة</th>
                                    <th>الراتب</th>
                                    <th>اسم الشركة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jobs as $job): ?>
                                    <tr>
                                        <td><?php echo $job['id']; ?></td>
                                        <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                                        <td><?php echo htmlspecialchars($job['job_description']); ?></td>
                                        <td><?php echo htmlspecialchars($job['salary']); ?></td>
                                        <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <!-- قائمة طلبات التقديم -->
    <h3 class="text-center mb-4">طلبات التقديم</h3>
    <table class="table table-striped table-hover table-bordered text-center">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>اسم الطالب</th>
                <th>عنوان الوظيفة</th>
                <th>حالة الطلب</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($applications)): ?>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['application_id']); ?></td>
                        <td><?php echo htmlspecialchars($application['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                        <td>
                            <?php 
                            // echo '<pre>';
                            // print_r($applications);
                            // echo '</pre>';
                                if ($application['application_status'] == 0) {
                                    echo '<span class="badge bg-warning text-dark">معلقة</span>';
                                } elseif ($application['application_status'] == 1) {
                                    echo '<span class="badge bg-success">مقبولة</span>';
                                } elseif ($application['application_status'] == 2) {
                                    echo '<span class="badge bg-danger">مرفوضة</span>';
                                } else {
                                    echo '<span class="badge bg-secondary">غير معروف</span>'; // في حال كانت القيمة غير متوقعة
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">لا توجد طلبات تقديم حتى الآن</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
                    </div>
                    <!-- جلب الاعانات -->
                    <h3 class="text-center mb-4">اعلانات </h3>
    <table class="table table-striped table-hover table-bordered text-center">
        <thead class="table-primary">
            <tr>
                <!-- <th>#</th> -->
                <th> رقم الاعلان</th>
                <th>اسم  الشركة</th>
                <th> اسم الوظيفة</th>
                <!-- <th> اسم الوظيفة</th> -->
                <th>  تاريخ الاعلان</th>
                <th>  تاريخ بدا التقديم</th>
                <th>  تاريخ انتهاء التقديم</th>



            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ad)): ?>
                <?php foreach ($ad as $ad): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ad['id']); ?></td>
                        <td><?php echo htmlspecialchars($ad['z']); ?></td>
                        <td><?php echo htmlspecialchars($ad['x']); ?></td>
                        <td><?php echo htmlspecialchars($ad['ad_date']); ?></td>
                        <td><?php echo htmlspecialchars($ad['apply_start_date']); ?></td>
                        <td><?php echo htmlspecialchars($ad['apply_end_date']); ?></td>
                        
                        <td>
                         
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">لا توجد  اعلانات حتى الآن</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
                    </div>
                </div>
                <div class="footer">
                    <p>© 2024 تصميم لوحة القيادة | جميع الحقوق محفوظة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
