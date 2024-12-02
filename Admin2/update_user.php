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


$id = $_POST['id'];

$sql = "UPDATE way SET  FullName='$FullName' , Email= '$Email', Phone='$Phone',  Password='$Password' , Birthdate:'$Birthdate',    Academic:'$Academic' ,  Gender :'$Gender', UserName:'$UserName'    WHERE id='$id' ";
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

