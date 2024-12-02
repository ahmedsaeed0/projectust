<?php 
include('connection.php');
$name = $_POST['name'];
$age = $_POST['age'];
$time = $_POST['time'];
$info = $_POST['info'];
$id = $_POST['id'];

$sql = "UPDATE `children` SET  `name`='$name' , `age`= '$age', `time`='$time',  `info`='$info' WHERE id='$id' ";
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