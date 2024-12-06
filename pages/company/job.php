
<?php
include '../../db.php';
session_start();

// التحقق من تسجيل الدخول وصلاحيات الشركة
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] !== 'company') {
    header("Location: ../login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$success_message = "تم اضافه الوظيفة بنجاح";

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

// إضافة وظيفة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_job'])) {
    $job_title = $_POST['job_title'];
    $specialization = $_POST['specialization'];
    $salary = $_POST['salary'];
    $job_description = $_POST['job_description'];
    $qualifications = $_POST['qualifications'];

    $insert_query = "INSERT INTO jobs (company_id, job_title, specialization, salary, job_description, qualifications) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("issdss", $company_id, $job_title, $specialization, $salary, $job_description, $qualifications);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم إضافة الوظيفة بنجاح.";
}

// تعديل وظيفة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_job'])) {
    $job_id = $_POST['job_id'];
    $job_title = $_POST['job_title'];
    $specialization = $_POST['specialization'];
    $salary = $_POST['salary'];
    $job_description = $_POST['job_description'];
    $qualifications = $_POST['qualifications'];

    $update_query = "UPDATE jobs SET job_title = ?, specialization = ?, salary = ?, job_description = ?, qualifications = ? WHERE id = ? AND company_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdssii", $job_title, $specialization, $salary, $job_description, $qualifications, $job_id, $company_id);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم تعديل الوظيفة بنجاح.";
}

// حذف وظيفة
if (isset($_GET['delete_job'])) {
    $job_id = $_GET['delete_job'];

    $delete_query = "DELETE FROM jobs WHERE id = ? AND company_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $job_id, $company_id);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم حذف الوظيفة بنجاح.";
}

// جلب الوظائف الخاصة بالشركة
$jobs_query = "SELECT * FROM jobs WHERE company_id = ?";
$stmt = $conn->prepare($jobs_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$jobs = $stmt->get_result();
$stmt->close();

// جلب قائمة التخصصات
$specializations = [];
$query = "SELECT * FROM specializations";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specializations[] = $row;
    }
}




?><!DOCTYPE html>
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
        <h1>لوحة التحكم</h1>

        <!-- رسالة نجاح -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- إضافة وظيفة جديدة -->
        <h3>إضافة وظيفة</h3>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="job_title" class="form-label">عنوان الوظيفة</label>
                <input type="text" name="job_title" id="job_title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="specialization" class="form-label">التخصص</label>
                <select name="specialization" id="specialization" class="form-control" required>
                    <option value="" disabled selected>اختر التخصص</option>
                    <?php foreach ($specializations as $specialization): ?>
                        <option value="<?php echo htmlspecialchars($specialization['id']); ?>">
                            <?php echo htmlspecialchars($specialization['specialization_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">الراتب</label>
                <input type="number" name="salary" id="salary" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="job_description" class="form-label">وصف الوظيفة</label>
                <textarea name="job_description" id="job_description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="qualifications" class="form-label">المؤهلات المطلوبة</label>
                <textarea name="qualifications" id="qualifications" class="form-control" required></textarea>
            </div>
            <button type="submit" name="add_job" class="btn btn-success">إضافة</button>
        </form>

        <!-- عرض الوظائف -->
        <h3>قائمة الوظائف</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>عنوان الوظيفة</th>
                    <th>التخصص</th>
                    <th>الراتب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
    <?php
    // جلب الوظائف المرتبطة بالشركة
    $jobs_query = "SELECT * FROM jobs WHERE company_id = ?";
    $stmt = $conn->prepare($jobs_query);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $jobs = $stmt->get_result();
    $stmt->close();

$specializations_query = "SELECT * FROM specializations"; 
$result = $conn->query($specializations_query); 
$specializations = []; 
while ($row = $result->fetch_assoc()) {
    $specializations[$row['id']] = $row['specialization_name']; 
}


    foreach ($jobs as $job): 
        $specialization_name = isset($specializations[$job['specialization']]) ? $specializations[$job['specialization']] : 'غير محدد';
    ?>
        <tr>
            <td><?php echo $job['id']; ?></td>
            <td><?php echo htmlspecialchars($job['job_title']); ?></td>
            <td><?php echo htmlspecialchars($specialization_name); ?></td>
            <td><?php echo htmlspecialchars($job['salary']); ?></td>
            <td>
                <!-- زر التعديل -->
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editJobModal<?php echo $job['id']; ?>">تعديل</button>
                <!-- زر الحذف -->
                <a href="?delete_job=<?php echo $job['id']; ?>" class="btn btn-danger btn-sm">حذف</a>
            </td>
        </tr>

        <!-- نافذة التعديل -->
        <div class="modal fade" id="editJobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="editJobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editJobModalLabel<?php echo $job['id']; ?>">تعديل الوظيفة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                            <div class="mb-3">
                                <label for="job_title" class="form-label">عنوان الوظيفة</label>
                                <input type="text" name="job_title" id="job_title" class="form-control" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="specialization" class="form-label">التخصص</label>
                                <select name="specialization" id="specialization" class="form-control" required>
                                    <?php foreach ($specializations as $specialization_id => $specialization_name): ?>
                                        <option value="<?php echo $specialization_id; ?>" <?php echo $job['specialization'] == $specialization_id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($specialization_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="salary" class="form-label">الراتب</label>
                                <input type="number" name="salary" id="salary" class="form-control" value="<?php echo htmlspecialchars($job['salary']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="job_description" class="form-label">وصف الوظيفة</label>
                                <textarea name="job_description" id="job_description" class="form-control" required><?php echo htmlspecialchars($job['job_description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="qualifications" class="form-label">المؤهلات المطلوبة</label>
                                <textarea name="qualifications" id="qualifications" class="form-control" required><?php echo htmlspecialchars($job['qualifications']); ?></textarea>
                            </div>
                            <button type="submit" name="update_job" class="btn btn-success">حفظ التعديلات</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</tbody>

        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
