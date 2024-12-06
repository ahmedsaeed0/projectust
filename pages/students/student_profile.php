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

// جلب بيانات المستخدم الحالية
$query = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// تحديث بيانات المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $uploaded_file = $_FILES['cv']['name'];

    // إذا لم يكن هناك أخطاء
    if (empty($error_message)) {
        // تحديث البيانات الأساسية
        $update_query = "UPDATE students SET full_name = ?, email = ?, phone = ?, username = ?";
        $params = [$full_name, $email, $phone, $username];
        $types = "ssss";

        // تحديث السيرة الذاتية إذا تم رفعها
        if (!empty($uploaded_file)) {
            $target_dir = "../../uploads/";
            $target_file = $target_dir . basename($_FILES["cv"]["name"]);
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // التحقق من نوع الملف
            if (!in_array($file_type, ["pdf", "doc", "docx", "jpg", "jpeg", "png", "gif"])) {
                $error_message = "فقط ملفات PDF أو DOC أو DOCX أو الصور (JPG, JPEG, PNG, GIF) مسموح بها.";
            } else {
                if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
                    $relative_path = "uploads/" . basename($_FILES["cv"]["name"]);
                    $update_query .= ", uploaded_file = ?";
                    $params[] = $relative_path;
                    $types .= "s";
                } else {
                    $error_message = "حدث خطأ أثناء رفع الملف.";
                }
            }
        }

        $update_query .= " WHERE id = ?";
        $params[] = $user_id;
        $types .= "i";

        // تنفيذ الاستعلام
        if (empty($error_message)) {
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute()) {
                $success_message = "تم تحديث بياناتك بنجاح.";
            } else {
                $error_message = "حدث خطأ أثناء تحديث البيانات.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البيانات الشخصية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    <h2 class="mb-4">تعديل البيانات الشخصية</h2>

    <!-- رسالة نجاح أو خطأ -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="full_name" class="form-label">الاسم الكامل</label>
            <input type="text" name="full_name" id="full_name" class="form-control"
                   value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">اسم المستخدم</label>
            <input type="text" name="username" id="username" class="form-control"
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" class="form-control"
                   value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="cv" class="form-label">السيرة الذاتية</label>
            <input type="file" name="cv" id="cv" class="form-control">
            <?php if (!empty($user['uploaded_file'])): ?>
                <a href="../../<?php echo htmlspecialchars($user['uploaded_file']); ?>" target="_blank">عرض السيرة الذاتية الحالية</a>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
