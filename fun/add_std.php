<?php
include ('../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $fullname = $_POST['FullName'] ?? null;
        $username = $_POST['UserName'] ?? null;
        $phone = $_POST['Phone'] ?? null;
        $password = $_POST['Password'] ?? null;
        $email = $_POST['Email'] ?? null;
        $birthdate = $_POST['Birthdate'] ?? null;
        $academic = $_POST['Academic'] ?? null;
        $gender = $_POST['Gender'] ?? null;

        if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
            $uploadedFile = $_FILES['uploadedFile']['name'];
            $targetDir = "uploads/";
            $targetFile = "../uploads/" . basename($uploadedFile);
            $targetFileUpload = $targetDir. basename($uploadedFile);

            if (!move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $targetFile)) {
                throw new Exception("Failed to upload the file.");
            }
        } else {
            throw new Exception("No file uploaded or an error occurred during the upload.");
        }

        $sql = "INSERT INTO students (full_name, username, phone, password, email, birthdate, academic_level, gender, uploaded_file) 
                VALUES ('$fullname', '$username', '$phone', '$password', '$email', '$birthdate', '$academic', '$gender', '$targetFileUpload')";

        if ($conn->query($sql) === TRUE) {
            echo "Student added successfully!";
            header("Location: ../pages/login.php"); 
        } else {
            throw new Exception("Database error: " . $conn->error);
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>
