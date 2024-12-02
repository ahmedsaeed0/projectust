<?php 
include('connection.php');
$Name = $_POST['Name'];
$Specialization = $_POST['Specialization'];
$Password = $_POST['Password'];
$Phone = $_POST['Phone'];
$UserName = $_POST['UserName'];


$id = $_POST['id'];

$sql = "UPDATE std SET  Name='$username' , Specialization= '$Specialization', Password='$Password',  Phone='$Phone' , UserName:'$UserName'     WHERE id='$id' ";
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