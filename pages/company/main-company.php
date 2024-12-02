<?php
include '../../db.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] !== 'company') {
    header("Location: ../login.php");
    exit();
}

$company_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $company_name = $_POST['company_name'];
    $UserName = $_POST['UserName'];
    $phone = $_POST['phone'];

    $update_query = "UPDATE companies SET Name = ?, UserName = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $company_name, $UserName, $phone, $company_id);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم تحديث بياناتك بنجاح.";
    
}

$query = "SELECT * FROM companies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();
$stmt->close();

// إضافة وظيفة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_job'])) {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $salary = $_POST['salary'];

    $insert_query = "INSERT INTO jobs (company_id, job_title, job_description, salary) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("issd", $company_id, $job_title, $job_description, $salary);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم إضافة الوظيفة بنجاح.";
}

// $applications_query = "SELECT job_applications.*, jobs.job_title 
//                        FROM job_applications 
//                        JOIN jobs ON job_applications.job_id = jobs.id 
//                        WHERE jobs.company_id = ?";
// $stmt = $conn->prepare($applications_query);
// $stmt->bind_param("i", $company_id);
// $stmt->execute();
// $applications = $stmt->get_result();
// $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - الشركة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
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
            <label for="test" class="form-label"> اسم المستخدم </label>
            <input type="test" name="UserName" id="UserName" class="form-control" value="<?php echo htmlspecialchars($company['UserName']); ?>">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($company['Phone']); ?>">
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">تحديث</button>
    </form>

    <!-- إضافة وظيفة جديدة -->
    <h3>إضافة وظيفة</h3>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="job_title" class="form-label">عنوان الوظيفة</label>
            <input type="text" name="job_title" id="job_title" class="form-control">
        </div>
        <div class="mb-3">
            <label for="job_description" class="form-label">وصف الوظيفة</label>
            <textarea name="job_description" id="job_description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="salary" class="form-label">الراتب</label>
            <input type="number" name="salary" id="salary" class="form-control">
        </div>
        <button type="submit" name="add_job" class="btn btn-success">إضافة</button>
    </form>

    <!-- الطلبات الواردة -->
    <h3>الطلبات الواردة</h3>
    <table class="table">
        <thead>
        <tr>
            <th>اسم المتقدم</th>
            <th>البريد الإلكتروني</th>
            <th>رقم الهاتف</th>
            <th>عنوان الوظيفة</th>
            <th>الحالة</th>
            <th>إجراء</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($application = $applications->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($application['applicant_name']); ?></td>
                <td><?php echo htmlspecialchars($application['applicant_email']); ?></td>
                <td><?php echo htmlspecialchars($application['applicant_phone']); ?></td>
                <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                <td><?php echo htmlspecialchars($application['status']); ?></td>
                <td>
                    <a href="accept_application.php?id=<?php echo $application['id']; ?>" class="btn btn-sm btn-success">قبول</a>
                    <a href="reject_application.php?id=<?php echo $application['id']; ?>" class="btn btn-sm btn-danger">رفض</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>

</html>
