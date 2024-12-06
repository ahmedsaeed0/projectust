<?php
include '../../db.php';
session_start();

// التحقق من تسجيل الدخول وصلاحيات الشركة
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] !== 'company') {
    header("Location: ../login.php");
    exit();
}


$company_id = $_SESSION['user_id'];
// echo $company_id;
$success_message = "";

// تحديث حالة الطلب
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $application_id = $_GET['id'];

    if ($action === 'accept') {
        $new_status = 1; // مقبول
    } elseif ($action === 'reject') {
        $new_status = 2; // مرفوض
    } elseif ($action === 'delete') {
        $delete_query = "DELETE FROM job_applications WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $stmt->close();
        $success_message = "تم حذف الطلب بنجاح.";
    }

    if (isset($new_status)) {
        // تحديث الحالة إذا كانت الحالة الحالية "معلقة" فقط
        $status_check_query = "SELECT status FROM job_applications WHERE id = ?";
        $stmt = $conn->prepare($status_check_query);
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        // echo $result;
        $stmt->close();

        if ($result['status'] == 0) { // فقط إذا كانت معلقة
            $update_query = "UPDATE job_applications SET status = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $new_status, $application_id);
            $stmt->execute();
            $stmt->close();
            $success_message = "تم تحديث حالة الطلب بنجاح.";
        } else {
            $success_message = "لا يمكن تغيير حالة الطلب بعد معالجته.";
        }
    }
}

// جلب الطلبات الخاصة بالشركة
$applications_query = "
    SELECT job_applications.*, jobs.job_title, students.full_name AS applicant_name, 
           students.email AS applicant_email, students.phone AS applicant_phone,
           students.uploaded_file AS applicant_cv
    FROM job_applications
    JOIN jobs ON job_applications.job_id = jobs.id
    JOIN students ON job_applications.student_id = students.id
    WHERE jobs.company_id = ?
";
$stmt = $conn->prepare($applications_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$applications = $stmt->get_result();
// print_r($applications);
$stmt->close();
// echo $application['applicant_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلبات الواردة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .btn-primary, .btn-success, .btn-danger, .btn-warning {
            margin-top: 10px;
        }

        .alert-success {
            margin-top: 10px;
        }

        .table-container {
            margin-top: 20px;
        }
        .link-control {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .link-control a {
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
        <a href="profail.php">الملف الشخصي</a>
        <a href="job.php"> الوظائف</a>
        <a href="ads.php"> الاعلانات</a>
        <a href="request.php"> الطلبات</a>
        <a href="../../index.php"> الرئيسة</a>
    </div>
<div class="container mt-4">
    <h1>إدارة الطلبات الواردة</h1>

    <!-- رسالة نجاح -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- قائمة الطلبات -->
    <h3>الطلبات الواردة</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>اسم المتقدم</th>
            <th>البريد الإلكتروني</th>
            <th>رقم الهاتف</th>
            <th>السيرة الذاتية</th>
            <th>عنوان الوظيفة</th>
            <th>الحالة</th>
            <th>الإجراء</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($application = $applications->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($application['applicant_name']); ?></td>
                <td><?php echo htmlspecialchars($application['applicant_email']); ?></td>
                <td><?php echo htmlspecialchars($application['applicant_phone']); ?></td>
                <td>
                    <?php if (!empty($application['applicant_cv'])): ?>
                        <a href="../../<?php echo htmlspecialchars($application['applicant_cv']); ?>" target="_blank">عرض السيرة الذاتية</a>
                    <?php else: ?>
                        لا يوجد
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                <td>
                    <?php 
                    if ($application['status'] == '0') {
                        echo "معلقة";
                    } elseif ($application['status'] == '1') {
                        echo "مقبولة";
                    } elseif ($application['status'] == '2') {
                        echo "مرفوضة";
                    }
                    ?>
                </td>
                <td>
                    <?php if ($application['status'] == 0): ?>
                        <a href="?action=accept&id=<?php echo $application['id']; ?>" class="btn btn-sm btn-success">قبول</a>
                        <a href="?action=reject&id=<?php echo $application['id']; ?>" class="btn btn-sm btn-danger">رفض</a>
                    <?php endif; ?>
                    <a href="?action=delete&id=<?php echo $application['id']; ?>" class="btn btn-sm btn-warning">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
