<?php
include ('../connect.php');

$name =$_GET['name'];

$Address=$_GET['Address'];
$chaildNo=$_GET['chaildNo'];
$sql="SELECT * FROM  adoption WHERE chaildNo='$chaildNo' ";
 $result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $count = $result->num_rows;
}
  
  if ($count >0) {
   
    header('location:http://localhost/eshopper/imp2.php?q1=1');
    exit();
  }

$NationalNumber=$_GET['NationalNumber'];

 
$sql="INSERT INTO adoption (name,	Address,chaildNo,NationalNumber)
VALUES('$name','$Address','$chaildNo','$NationalNumber')";
$result=$conn->query($sql);
header('Location:http://localhost/eshopper/MFC.php');


?> 