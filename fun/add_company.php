<?php
include ('../db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['Name'];
    $username = $_POST['UserName'];
    $specialization = $_POST['Specialization'];
    $password = $_POST['Password'];
    $phone = $_POST['Phone'];

    $sql = "INSERT INTO companies (name, username, specialization, password, phone) 
            VALUES ('$name', '$username', '$specialization', '$password', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "Company added successfully!";
        header("Location: ../pages/login.php"); 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>?>