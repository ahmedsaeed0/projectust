<?php 
include('connection.php');
$name = $_POST['name'];
$age = $_POST['age'];
$time = $_POST['time'];
$info = $_POST['info'];

$sql = "INSERT INTO `children` (`name`,`age`,`time`,`info`) values ('$name', '$age', '$time', '$info' )";
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