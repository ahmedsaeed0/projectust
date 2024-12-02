<?php
 include ('../db.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountType = $_POST['accountType'];
    $username = $_POST['UserName'];
    $password = $_POST['pass'];


   

    if ($accountType === 'company') {
        $sql = "SELECT * FROM companies WHERE username = ? AND password = ?";
    } elseif ($accountType === 'student') {
        $sql = "SELECT * FROM students WHERE username = ? AND password = ?";
    } else {
        $error = "Invalid account type.";
        $conn->close();
        goto showError;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['logged_in'] = true;
        $_SESSION['account_type'] = $accountType;
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; 

        if ($accountType === 'company') {
            header('Location: ../index.php');
        } elseif ($accountType === 'student') {
            header('Location: ../index.php');
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
} else {
    $error = "Unauthorized access.";
}

showError:
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Error</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .error-container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container error-container text-center">
        <h1 class="text-danger">Login Failed</h1>
        <p class="text-muted"><?php echo isset($error) ? $error : 'An unknown error occurred.'; ?></p>
        <a href="login.php" class="btn btn-primary">Go Back to Login</a>
    </div>
</body>

</html>
