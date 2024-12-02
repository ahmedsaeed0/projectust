<?php
$servername = '127.0.0.1';
$name = 'root';
$Password = '';
$dbname = "mcf";

$conn = new mysqli($servername, $name, $Password, $dbname);
// $conn->set_charset("utf-8");

if ($conn->connect_error) {
    die("error: " . $conn->connect_error);
}

echo "";
?>
