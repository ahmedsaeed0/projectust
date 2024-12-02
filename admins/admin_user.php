<?php
include '../db.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    $username = $_SESSION['admin_username'];
} else {
    header("Location: ../admin.php");
    exit();
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../admin.php");
    exit();
}

// جلب بيانات المستخدمين
$students = [];
$companies = [];

try {
    // جلب الطلاب
    $studentQuery = $conn->query("SELECT * FROM students");
    while ($row = $studentQuery->fetch_assoc()) {
        $students[] = $row;
    }

    // جلب الشركات
    $companyQuery = $conn->query("SELECT * FROM companies");
    while ($row = $companyQuery->fetch_assoc()) {
        $companies[] = $row;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// حذف المستخدم
if (isset($_GET['delete_student'])) {
    $id = intval($_GET['delete_student']);
    $conn->query("DELETE FROM students WHERE id = $id");
    header("Location: admin_user.php");
    exit();
}

if (isset($_GET['delete_company'])) {
    $id = intval($_GET['delete_company']);
    $conn->query("DELETE FROM companies WHERE id = $id");
    header("Location: admin_user.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين</title>
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
            padding: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        .table-title {
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #343a40;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
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
                <a href="#">إدارة الطلبات</a>
                <a href="#">إعدادات</a>
                <!-- زر تسجيل الخروج -->
                <a href="?logout=true" class="logout-btn">تسجيل الخروج</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="mb-4">إدارة المستخدمين</h2>

                <!-- Students Section -->
                <div class="table-container">
                    <h3 class="table-title">الطلاب</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>اسم المستخدم</th>
                                <th> البريد الاكتروني</th>
                                <th> رقم الجوال </th>
                                <th>  المستوي التعليمي </th>
                                <th>   السيرة الذاتية </th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student) : ?>
                                <tr>
                                    <td><?php echo $student['id']; ?></td>
                                    <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['username']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($student['academic_level']); ?></td>
                                    <td>
                                            <?php if (!empty($student['uploaded_file'])) : ?>
                                                <a href="../<?php echo htmlspecialchars($student['uploaded_file']); ?>" target="_blank">عرض الملف</a>
                                            <?php else : ?>
                                                لا يوجد ملف
                                            <?php endif; ?>
                                        </td>
                                    
                                    
                                    

                                    <td>
                                        <a href="?delete_student=<?php echo $student['id']; ?>" class="btn-delete">حذف</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Companies Section -->
                <div class="table-container">
                    <h3 class="table-title">الشركات</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>اسم المستخدم</th>
                                <th> التخصص</th>
                                <th> رقم الموبايل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $company) : ?>
                                <tr>
                                    <td><?php echo $company['id']; ?></td>
                                    <td><?php echo htmlspecialchars($company['Name']); ?></td>
                                    <td><?php echo htmlspecialchars($company['UserName']); ?></td>
                                    <td><?php echo htmlspecialchars($company['Specialization']); ?></td>
                                    <td><?php echo htmlspecialchars($company['Phone']); ?></td>
                                    <td>
                                        <a href="?delete_company=<?php echo $company['id']; ?>" class="btn-delete">حذف</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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

</html>
