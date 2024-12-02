<?php
include ('../connect.php');






$name =$_GET['name'];
$sql="SELECT * FROM  bringchild WHERE name='$name' ";
 $result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $count = $result->num_rows;
}
  
  if ($count >0) {
   
    header('location:http://localhost/eshopper/imp1.php?q1=1');
    exit();
  }

 

$address=$_GET['address'];
$Phone=$_GET['Phone'];

$info=$_GET['info'];

 
$sql="INSERT INTO bringchild (name,address,Phone,info)
VALUES('$name','$address','$Phone','$info')";
$result=$conn->query($sql);
header('Location:http://localhost/eshopper/login2.php');


?> 