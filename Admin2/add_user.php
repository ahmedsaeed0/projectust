<?php 
include('connection.php');
$FullName = $_POST['FullName'];
$Email = $_POST['Email'];
$Phone = $_POST['Phone'];
$Password = $_POST['Password'];
$Birthdate = $_POST['Birthdate'];
$Academic = $_POST['Academic'];
$Gender = $_POST['Gender'];
$UserName = $_POST['UserName'];



$sql = "INSERT INTO way (FullName,Email,Phone,Password,Birthdate,Academic,Gender,UserName) values ('$FullName','$Email','$Phone','$Password','$Birthdate','$Academic', '$Gender','$UserName')";
$query= mysqli_query($con,$sql);
$lastId = mysqli_insert_id($con);
if($query ==true)
{
   
    $data = array(
        'status'=>'true',
       
    );

    echo json_encode($data);
}
else
{
     $data = array(
        'status'=>'false',
      
    );

    echo json_encode($data);
} 

?>

