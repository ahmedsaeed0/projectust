<?php
include '../../db.php';
session_start();

// التحقق من تسجيل الدخول وصلاحيات الشركة
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] !== 'company') {
    header("Location: ../login.php");
    exit();
}

$company_id = $_SESSION['user_id'];

// تحديث بيانات الشركة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $company_name = $_POST['company_name'];
    $UserName = $_POST['UserName'];
    $phone = $_POST['phone'];

    $update_query = "UPDATE companies SET Name = ?, UserName = ?, Phone = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $company_name, $UserName, $phone, $company_id);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم تحديث بياناتك بنجاح.";
}

// جلب بيانات الشركة
$query = "SELECT * FROM companies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();
$stmt->close();

// إضافة وظيفة جديدة




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - الشركة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .btn-primary, .btn-success {
            margin-top: 10px;
        }

        .table-container {
            margin-top: 20px;
        }

        .alert-success {
            margin-top: 10px;
        }

        textarea {
            resize: none;
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
        <a href="profail.php">الملف الشخصي</a>
        <a href="job.php"> الوظائف</a>
        <a href="ads.php"> الاعلانات</a>
        <a href="request.php"> الطلبات</a>
        <a href="../../index.php"> الرئيسة</a>

    </div>
<div class="container mt-4">
    <h1>لوحة التحكم</h1>

    <!-- رسالة نجاح -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- تحديث البيانات الشخصية -->
    <h3>تحديث بيانات الشركة</h3>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="company_name" class="form-label">اسم الشركة</label>
            <input type="text" name="company_name" id="company_name" class="form-control" value="<?php echo htmlspecialchars($company['Name']); ?>">
        </div>
        <div class="mb-3">
            <label for="UserName" class="form-label">اسم المستخدم</label>
            <input type="text" name="UserName" id="UserName" class="form-control" value="<?php echo htmlspecialchars($company['UserName']); ?>">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($company['Phone']); ?>">
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">تحديث</button>
    </form>

    <!-- إضافة وظيفة جديدة -->
    
</div>
</body>

</html>
