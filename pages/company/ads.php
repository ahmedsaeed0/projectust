<?php
include '../../db.php';
session_start();

// التحقق من تسجيل الدخول وصلاحيات الشركة
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] !== 'company') {
    header("Location: ../login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$success_message = "";

// إضافة إعلان جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_ad'])) {
    $job_id = $_POST['job_id'];
    $ad_date = $_POST['ad_date'];
    $apply_start_date = $_POST['apply_start_date'];
    $apply_end_date = $_POST['apply_end_date'];

    $insert_query = "INSERT INTO ads (job_id, company_id, ad_date, apply_start_date, apply_end_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iisss", $job_id, $company_id, $ad_date, $apply_start_date, $apply_end_date);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم إضافة الإعلان بنجاح.";
}

// تعديل إعلان
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_ad'])) {
    $ad_id = $_POST['ad_id'];
    $job_id = $_POST['job_id'];
    $ad_date = $_POST['ad_date'];
    $apply_start_date = $_POST['apply_start_date'];
    $apply_end_date = $_POST['apply_end_date'];

    $update_query = "UPDATE ads SET job_id = ?, ad_date = ?, apply_start_date = ?, apply_end_date = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("isssi", $job_id, $ad_date, $apply_start_date, $apply_end_date, $ad_id);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم تعديل الإعلان بنجاح.";
}

// حذف إعلان
if (isset($_GET['delete_ad'])) {
    $ad_id = $_GET['delete_ad'];

    $delete_query = "DELETE FROM ads WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $ad_id);
    $stmt->execute();
    $stmt->close();

    // $success_message = "تم حذف الإعلان بنجاح.";
}

// جلب الإعلانات
$ads_query = "SELECT * FROM ads WHERE company_id = ?";
$stmt = $conn->prepare($ads_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$ads = $stmt->get_result();
$stmt->close();

// جلب الوظائف المرتبطة بالشركة
$jobs_query = "SELECT * FROM jobs WHERE company_id = ?";
$stmt = $conn->prepare($jobs_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$jobs = $stmt->get_result();
$jobs_data = [];
while ($job = $jobs->fetch_assoc()) {
    $jobs_data[] = $job;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الإعلانات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .btn-primary,
        .btn-success {
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
    <h1>إدارة الإعلانات</h1>

    <!-- رسالة نجاح -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- إضافة إعلان جديد -->
    <h3>إضافة إعلان جديد</h3>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="job_id" class="form-label">رقم الوظيفة</label>
            <select name="job_id" id="job_id" class="form-control" required>
                <option value="" disabled selected>اختر الوظيفة</option>
                <?php foreach ($jobs_data as $job): ?>
                    <option value="<?php echo $job['id']; ?>"><?php echo htmlspecialchars($job['job_title']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="ad_date" class="form-label">تاريخ الإعلان</label>
            <input type="date" name="ad_date" id="ad_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="apply_start_date" class="form-label">تاريخ بدء التقديم</label>
            <input type="date" name="apply_start_date" id="apply_start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="apply_end_date" class="form-label">تاريخ انتهاء التقديم</label>
            <input type="date" name="apply_end_date" id="apply_end_date" class="form-control" required>
        </div>
        <button type="submit" name="add_ad" class="btn btn-success">إضافة</button>
    </form>

    <!-- قائمة الإعلانات -->
    <h3>قائمة الإعلانات</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الوظيفة</th>
            <th>تاريخ الإعلان</th>
            <th>تاريخ بدء التقديم</th>
            <th>تاريخ انتهاء التقديم</th>
            <th>الإجراءات</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($ad = $ads->fetch_assoc()): ?>
            <tr>
                <td><?php echo $ad['id']; ?></td>
                <td><?php echo htmlspecialchars($ad['job_id']); ?></td>
                <td><?php echo htmlspecialchars($ad['ad_date']); ?></td>
                <td><?php echo htmlspecialchars($ad['apply_start_date']); ?></td>
                <td><?php echo htmlspecialchars($ad['apply_end_date']); ?></td>
                <td>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAdModal<?php echo $ad['id']; ?>">تعديل</button>
                    <a href="?delete_ad=<?php echo $ad['id']; ?>" class="btn btn-sm btn-danger">حذف</a>
                </td>
            </tr>
            <!-- تعديل إعلان -->
            <div class="modal fade" id="editAdModal<?php echo $ad['id']; ?>" tabindex="-1" aria-labelledby="editAdModalLabel<?php echo $ad['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAdModalLabel<?php echo $ad['id']; ?>">تعديل الإعلان</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <input type="hidden" name="ad_id" value="<?php echo $ad['id']; ?>">
                                <div class="mb-3">
                                    <label for="job_id_<?php echo $ad['id']; ?>" class="form-label">رقم الوظيفة</label>
                                    <select name="job_id" id="job_id_<?php echo $ad['id']; ?>" class="form-control" required>
                                        <?php foreach ($jobs_data as $job): ?>
                                            <option value="<?php echo $job['id']; ?>" <?php echo $job['id'] == $ad['job_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($job['job_title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="ad_date_<?php echo $ad['id']; ?>" class="form-label">تاريخ الإعلان</label>
                                    <input type="date" name="ad_date" id="ad_date_<?php echo $ad['id']; ?>" class="form-control" value="<?php echo $ad['ad_date']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apply_start_date_<?php echo $ad['id']; ?>" class="form-label">تاريخ بدء التقديم</label>
                                    <input type="date" name="apply_start_date" id="apply_start_date_<?php echo $ad['id']; ?>" class="form-control" value="<?php echo $ad['apply_start_date']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apply_end_date_<?php echo $ad['id']; ?>" class="form-label">تاريخ انتهاء التقديم</label>
                                    <input type="date" name="apply_end_date" id="apply_end_date_<?php echo $ad['id']; ?>" class="form-control" value="<?php echo $ad['apply_end_date']; ?>" required>
                                </div>
                                <button type="submit" name="update_ad" class="btn btn-success">حفظ التعديلات</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
