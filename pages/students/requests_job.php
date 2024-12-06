<?php
include '../../db.php';
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

// حذف الطلب
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $application_id = $_GET['id'];

    $delete_query = "DELETE FROM job_applications WHERE id = ? AND student_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $application_id, $user_id);

    if ($stmt->execute()) {
        $success_message = "تم حذف الطلب بنجاح.";
    } else {
        $error_message = "حدث خطأ أثناء حذف الطلب.";
    }

    $stmt->close();
}

// جلب الطلبات الخاصة بالمستخدم
$applications_query = "
    SELECT job_applications.*, jobs.job_title, companies.Name AS company_name
    FROM job_applications
    JOIN jobs ON job_applications.job_id = jobs.id
    JOIN companies ON jobs.company_id = companies.id
    WHERE job_applications.student_id = ?
";
$stmt = $conn->prepare($applications_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$applications = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .alert {
            margin-top: 20px;
        }

        .btn-danger {
            margin-left: 10px;
        }
        .link-control{
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
             

        }
        .link-control a{
            text-decoration: none;
            color: white;
            background: #0d6efd;
            padding: 8px 10px;
            border-radius: 18px;
        }
    </style>
</head>

<body>
<div class="link-control">
        <a href="student_profile.php">الملف الشخصي</a>
        <a href="rest_password.php"> تغير كلمة السر</a>
        <a href="requests_job.php"> الطلبات</a>
        <a href="../../index.php"> الرئيسة</a>
    </div>
<div class="container">
    <h2 class="mb-4">طلباتي</h2>

    <!-- رسالة نجاح أو خطأ -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- قائمة الطلبات -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>الشركة</th>
            <th>عنوان الوظيفة</th>
            <th>حالة الطلب</th>
            <th>إجراءات</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($applications->num_rows > 0): ?>
            <?php while ($application = $applications->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $application['id']; ?></td>
                    <td><?php echo htmlspecialchars($application['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                    <!-- <td><?php echo htmlspecialchars($application['status']); ?></td> -->
                    <td>
                        <?php
                        if ($application['status'] === '0') {
                            echo "معلق";
                        } elseif ($application['status'] === '1') {
                            echo "مقبول";
                        } elseif ($application['status'] === '2') {
                            echo "مرفوض";
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($application['status'] === '0'): ?>
                            <a href="?action=delete&id=<?php echo $application['id']; ?>" class="btn btn-sm btn-danger">حذف الطلب</a>
                        <?php else: ?>
                            <span>لا يمكن حذف الطلب</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">لا توجد طلبات حالياً.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
