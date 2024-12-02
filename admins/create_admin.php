<?php
include '../db.php';
// Start session for admin authentication
session_start();


// Check if admin is logged in (only admin can create another admin)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Connect to the database

// Check the connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['UserName'];
    $password = $_POST['pass'];
    $confirmPassword = $_POST['confirmPass'];

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert new admin into the database
        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            $success = "Admin created successfully.";
        } else {
            $error = "Failed to create admin. Please try again.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .create-admin-container {
            margin-top: 100px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
        }

        .form-control {
            border-radius: 5px;
            height: 45px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <section class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 create-admin-container">
                    <h1 class="text-center mb-4">Create Admin</h1>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php elseif (isset($success)): ?>
                        <div class="alert alert-success text-center"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label" for="UserName">Username</label>
                            <input class="form-control" type="text" name="UserName" id="UserName" placeholder="Enter admin username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pass">Password</label>
                            <input class="form-control" type="password" name="pass" id="pass" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="confirmPass">Confirm Password</label>
                            <input class="form-control" type="password" name="confirmPass" id="confirmPass" placeholder="Confirm password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="admin_dashboard.php" class="text-decoration-none">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
