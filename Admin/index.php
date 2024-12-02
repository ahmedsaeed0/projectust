<?php include('connection.php'); ?>
<!doctype html>


<head>
<meta charset="utf-8">
    <title> </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description"> <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/datatables-1.10.25.min.css" />
  
  <style type="text/css">
    .btnAdd {
      text-align: right;
      width: 83%;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <!-- Page Header Start -->
  <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Welcom Manager</h1>
    <!-- Page Header End -->




      <!-- Button trigger modal -->

  <div class="container-fluid">
    
    
    <div class="row">
      <div class="container">
        <div class="btnAdd">
          <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">Add Student</a>
        </div>
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <table id="example" class="table">
              <thead>
                <th>Id</th>
                <th>Name</th>

                <th>Specialization</th>
                <th>Password</th>
                <th>Phone</th>

                <th>UserName</th>


                <th>Options</th>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- Optional JavaScript; choose one of the two! -->
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#example').DataTable({
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        'order': [],
        'ajax': {
          'url': 'fetch_data.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [5]
          },

        ]
      });
    });
    $(document).on('submit', '#addUser', function(e) {
      e.preventDefault();
      var Name = $('#addNameField').val();
      var Specialization = $('#addUserField').val();
      var Password = $('#addSpecializationField').val();
      var Phone = $('#addPhoneField').val();
      var UserName = $('#addUserNameField').val();

      if (Name != '' && Specialization != '' && Password != '' && Phone != '' && UserName != '') {
        $.ajax({
          url: "add_user.php",
          type: "post",
          data: {
            Name: Name,
            Specialization: Specialization,
            Password: Password,
            Phone: Phone,
            UserName:UserName
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#example').DataTable();
              mytable.draw();
              $('#addUserModal').modal('hide');
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Fill all the required fields');
      }
    });
    $(document).on('submit', '#updateUser', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var Name = $('#NameField').val();
      var Specialization = $('#SpecializationField').val();
      var Password = $('PasswordField').val();
      var Phone = $('#PhoneField').val();
      var UserName = $('#UserNameField').val();

      var trid = $('#trid').val();
      var id = $('#id').val();
      if (Name != '' && Specialization != '' && Password != '' && Phone != '' && UserName != '') {
        $.ajax({
          url: "update_user.php",
          type: "post",
          data: {
            Name: Name,
            Specialization: Specialization,
            Password: Password,
            Phone: Phone,
            UserName:UserName,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#example').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(Password);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(address);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-info btn-sm editbtn">Edit</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Delete</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id, Name, Specialization, Password, Phone,UserName, button]);
              $('#exampleModal').modal('hide');
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Fill all the required fields');
      }
    });
    $('#example').on('click', '.editbtn ', function(event) {
      var table = $('#example').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModal').modal('show');

      $.ajax({
        url: "get_single_data.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#NameField').val(json.Name);
          $('#SpecializationField').val(json.Specialization);
          $('#PasswordField').val(json.Password);
          $('#PhoneField').val(json.Phone);
          $('#UserNameField').val(json.UserName);

          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

   
    $(document).on('click', '.deleteBtn', function(event) {
      var table = $('#example').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Are you sure want to delete this User ? ")) {
        $.ajax({
          url: "delete_user.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }


    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUser">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            <div class="mb-3 row">
              <label for="nameField" class="col-md-3 form-label">Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="nameField" name="name">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="SpecializationField" class="col-md-3 form-label">Specialization</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="emailField" name="Specialization">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="PasswordField" class="col-md-3 form-label">Password</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="mobileField" name="Password">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="PhoneField" class="col-md-3 form-label">Phone</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="cityField" name="Phone">
              </div>
            </div>   
            <div class="mb-3 row">
              <label for="UserNameField" class="col-md-3 form-label">UserName</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="cityField" name="UserName">
              </div>
            </div>                                                                                                        
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUser" >
            <div class="mb-3 row">
              <label for="addNamerField" class="col-md-3 form-label">Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addNamerField" name="Name">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addSpecializationField" class="col-md-3 form-label">Specialization</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addSpecializationField" name="Specialization">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addPasswordField" class="col-md-3 form-label">Password</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addPasswordField" name="Password">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addPhoneField" class="col-md-3 form-label">Phone</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addPhoneField" name="Phone">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addUserNameField" class="col-md-3 form-label">UserName</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addUserNameField" name="UserName">
              </div>
            </div>
           
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

















  
</body>

</html>
<script type="text/javascript">
  //var table = $('#example').DataTable();
</script>