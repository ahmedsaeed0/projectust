<?php include('connection.php');

$output= array();
$sql = "SELECT * FROM way ";

$totalQuery = mysqli_query($con,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'id',
	1 => 'FullName',
	2 => 'Email',
	3 => 'Phone',
	4 => 'Password',
	5 => 'Birthdate',
	6 => 'Academic',
	7 => 'Gender',
	8 => 'UserName',



);

if(isset($_POST['search']['value']))
{
	$search_value = $_POST['search']['value'];
	$sql .= " WHERE FullName like '%".$search_value."%'";
	$sql .= " OR Email like '%".$search_value."%'";
	$sql .= " OR Phone like '%".$search_value."%'";
	$sql .= " OR Password like '%".$search_value."%'";
	$sql .= " OR Birthdate like '%".$search_value."%'";
	$sql .= " OR Academic like '%".$search_value."%'";
	$sql .= " OR Gender like '%".$search_value."%'";
	$sql .= " OR UserName like '%".$search_value."%'";

}

if(isset($_POST['order']))
{
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .= " ORDER BY ".$columns[$column_name]." ".$order."";
}
else
{
	$sql .= " ORDER BY id desc";
}

if($_POST['length'] != -1)
{
	$start = $_POST['start'];
	$length = $_POST['length'];
	$sql .= " LIMIT  ".$start.", ".$length;
}	

$query = mysqli_query($con,$sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while($row = mysqli_fetch_assoc($query))
{
	$sub_array = array();
	$sub_array[] = $row['id'];
	$sub_array[] = $row['FullName'];
	$sub_array[] = $row['Email'];
	$sub_array[] = $row['Phone'];
	$sub_array[] = $row['Birthdate'];
	$sub_array[] = $row['Academic'];
	$sub_array[] = $row['Gender'];
	$sub_array[] = $row['UserName'];

	$sub_array[] = '<a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn" >Edit</a>  <a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtn" >Delete</a>';
	$data[] = $sub_array;
}

$output = array(
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=>   $total_all_rows,
	'data'=>$data,
);
echo  json_encode($output);
