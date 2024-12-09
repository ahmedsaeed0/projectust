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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_student'])) {
    $id = intval($_POST['id']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $academic_level = $conn->real_escape_string($_POST['academic_level']);

    $conn->query("UPDATE students SET full_name = '$full_name', username = '$username', email = '$email', phone = '$phone', academic_level = '$academic_level' WHERE id = $id");
    header("Location: admin_user.php");
    exit();
}
if (isset($_POST['add_student'])) {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $academic_level = $_POST['academic_level'];
    $gender =  $_POST['gender']; 

    if (!empty($_FILES['uploaded_file']['name'])) {
        $file_name = $_FILES['uploaded_file']['name'];
        $file_tmp = $_FILES['uploaded_file']['tmp_name'];
        $destination = "uploads/" . $file_name;
        $move = "../uploads/" . $file_name;
        move_uploaded_file($file_tmp, $move);
    } else {
        $destination = null;
    }

    $query = "INSERT INTO students (full_name, username, email, phone, academic_level, uploaded_file,gender) VALUES ('$full_name', '$username', '$email', '$phone', '$academic_level', '$destination','$gender')";
    $conn->query($query);

    header("Location: admin_user.php");
    exit();
}
// اضافة شركة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_company'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password=mysqli_real_escape_string($conn, $_POST['password']);

    // إدخال البيانات في قاعدة البيانات
    $query = "INSERT INTO companies (Name, UserName, Specialization, Phone,password) 
              VALUES ('$name', '$username', '$specialization', '$phone','$password')";

    if ($conn->query($query)) {
        echo "<script>alert('تمت إضافة الشركة بنجاح');</script>";
        header("Location: admin_user.php");
        exit();
    } else {
        echo "حدث خطأ أثناء الإضافة: " . $conn->error;
    }
}
// تحديث بيانات الشركة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_company'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $query = "UPDATE companies 
              SET Name = '$name', UserName = '$username', Specialization = '$specialization', Phone = '$phone' 
              WHERE id = $id";

    if ($conn->query($query)) {
        echo "<script>alert('تم تعديل بيانات الشركة بنجاح');</script>";
        header("Location: admin_user.php");
        exit();
    } else {
        echo "حدث خطأ أثناء التعديل: " . $conn->error;
    }
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

    .btn-warring {
        background-color: #ffc107;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 0.9rem;

    }
    .sidebar {
    height: 100vh;
    background-color: #343a40;
    color: #fff;
    padding: 20px;
    position: fixed; /* لتثبيت الشريط الجانبي أثناء التمرير */
    top: 0;
    left: 0;
    overflow-y: auto; /* لتفعيل التمرير عند امتلاء المحتوى */
    width: 18%; /* لضبط عرض الشريط الجانبي */
}

.main-content {
    margin-left: 19%; /* لإزاحة المحتوى الرئيسي لتجنب التداخل مع الشريط الجانبي */
    padding: 20px;
    overflow-y: auto; /* لتفعيل التمرير في المحتوى الرئيسي عند امتلائه */
    max-height: 90vh; /* لضبط ارتفاع المحتوى الرئيسي */
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
                <h2 class="mb-4">إدارة المستخدمين</h2>

                <!-- Students Section -->
                <div class="table-container">
                    <h3 class="table-title">الطلاب</h3>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        إضافة طالب جديد
                    </button>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>اسم المستخدم</th>
                                <th> البريد الاكتروني</th>
                                <th> رقم الجوال </th>
                                <th> المستوي التعليمي </th>
                                <th> السيرة الذاتية </th>
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
                                    <a href="../<?php echo htmlspecialchars($student['uploaded_file']); ?>"
                                        target="_blank">عرض الملف</a>
                                    <?php else : ?>
                                    لا يوجد ملف
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="?delete_student=<?php echo $student['id']; ?>" class="btn-delete">حذف</a>
                                </td>
                                <td>
                                    <a href="#" class="btn-warring" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?php echo $student['id']; ?>">تعديل</a>
                                </td>

                                <!-- *! edit std table -->
                                <div class="modal fade" id="editModal<?php echo $student['id']; ?>" tabindex="-1"
                                    aria-labelledby="editModalLabel<?php echo $student['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="editModalLabel<?php echo $student['id']; ?>">تعديل بيانات الطالب
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="POST">
                                                    <input type="hidden" name="id"
                                                        value="<?php echo $student['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="full_name" class="form-label">الاسم</label>
                                                        <input type="text" class="form-control" id="full_name"
                                                            name="full_name"
                                                            value="<?php echo htmlspecialchars($student['full_name']); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">اسم المستخدم</label>
                                                        <input type="text" class="form-control" id="username"
                                                            name="username"
                                                            value="<?php echo htmlspecialchars($student['username']); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">البريد الإلكتروني</label>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                            value="<?php echo htmlspecialchars($student['email']); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                                        <input type="text" class="form-control" id="phone" name="phone"
                                                            value="<?php echo htmlspecialchars($student['phone']); ?>">
                                                    </div>
                                                    <button type="submit" name="update_student"
                                                        class="btn btn-primary">حفظ التعديلات</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </tr>
                            <!-- ? * Add std data -->

                            <?php endforeach; ?>


                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">إضافة طالب جديد</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <!-- الاسم -->
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">الاسم الكامل</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                            placeholder="أدخل الاسم الكامل" required>
                                    </div>
                                    <!-- اسم المستخدم -->
                                    <div class="mb-3">
                                        <label for="username" class="form-label">اسم المستخدم</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="أدخل اسم المستخدم" required>
                                    </div>
                                    <!-- البريد الإلكتروني -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="أدخل البريد الإلكتروني" required>
                                    </div>
                                    <!-- رقم الهاتف -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            placeholder="أدخل رقم الهاتف" required>
                                    </div>
                                    <!-- الجنس -->
                                    <div class="mb-3">
                                        <label class="form-label">الجنس</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="male"
                                                value="male" required>
                                            <label class="form-check-label" for="male">ذكر</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="female"
                                                value="female" required>
                                            <label class="form-check-label" for="female">أنثى</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="uploaded_file" class="form-label">السيرة الذاتية</label>
                                            <input type="file" class="form-control" id="uploaded_file"
                                                name="uploaded_file" accept=".pdf,.doc,.docx">
                                        </div>
                                    </div>
                                    <!-- زر الحفظ -->
                                    <button type="submit" name="add_student" class="btn btn-primary">حفظ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Companies Section -->
                <div class="table-container">
                    <h3 class="table-title">الشركات</h3>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                        إضافة شركة جديدة
                    </button>

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
                                <td>
                                <a href="#" class="btn-warring" data-bs-toggle="modal" data-bs-target="#editCompanyModal<?php echo $company['id']; ?>">تعديل</a>                                </td>   
                            </tr>
                            
                    <div class="modal fade" id="editCompanyModal<?php echo $company['id']; ?>" tabindex="-1" aria-labelledby="editCompanyModalLabel<?php echo $company['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCompanyModalLabel<?php echo $company['id']; ?>">تعديل بيانات الشركة</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $company['id']; ?>">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">اسم الشركة</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($company['Name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">اسم المستخدم</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($company['UserName']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="specialization" class="form-label">التخصص</label>
                                            <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo htmlspecialchars($company['Specialization']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">رقم الهاتف</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($company['Phone']); ?>" required>
                                        </div>
                                        <button type="submit" name="update_company" class="btn btn-primary">حفظ التعديلات</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- نافذة التعديل (Modal) -->

                </tbody>
                </table>
            </div>
            <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyModalLabel">إضافة شركة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الشركة</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="specialization" class="form-label">التخصص</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                     <div class="mb-3">
                        <label for="password" class="form-label">كلمة السر </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="add_company" class="btn btn-primary">إضافة</button>
                </form>
            </div>
        </div>
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

</html>