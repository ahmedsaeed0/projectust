
 <?php
 include ('../connect.php');
 $n1=$_GET['n1'];
 $n2=$_GET['n2'];
 
  $sql="SELECT * FROM  admin WHERE username='$n1' AND 	Password='$n2'";
 $result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $count = $result->num_rows;
}
if ($count >0) {
    
  session_start();
  $_SESSION['username']=$n1;
 
    header('location:http://localhost/R/Menger.php');

    exit();
}

    $n1=$_GET['n1'];
 $n2=$_GET['n2'];
 
  $sql="SELECT * FROM  employee WHERE username='$n1' AND 	Password='$n2'";
 $result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $count = $result->num_rows;
}
  if ($count >0) {
    session_start();
  $_SESSION['Password']=$n2;
  header('location:http://localhost/R/Menger2.php');
  exit();
}
?>
<!-- login