<?php
include('check_request.php');
include_once "log/LogsFunctions.php";

session_start();
$old_gName= $_POST['group_name'];
$remote_group = $_POST['remote_group'];
$remote_user = $_POST['remote_user'];

if(!isset($_POST['group_name']))
	$old_gName = $_SESSION['old_gName'];
else
	$_SESSION['old_gName'] = $old_gName;

if(isset($_POST['edit']) && isset($_POST['group_name_f'])){
    $new_gName= $_POST['group_name_f'];
    if(!empty($new_gName)){
        exec("sudo groupmod -n $new_gName ".$_SESSION['old_gName'],$out,$code);

				if($code == 0) {
					infolog($remote_group, $remote_user, "Successfully change group name to  '".$new_gName."'", "Success");
				}
				else {
					errlog($remote_group, $remote_user, "Error ".$code.": unable to change group name to '".$new_gName."'");
				}
        header('Location: groups.php');
    }else{
        echo "Please Enter Group Name";
    }
}

 ?>
<html>
    <header>

         <title> update_group </title>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </header>
    <body>
			<?php
				include('header.php');
			?>
      <div class="jumbotron">
        <h1>Updating Group</h1>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-4">

          </div>
          <div class="col-md-4">
        <form action="updategroup.php" method="POST">
          <div class="form-group">
            <label> Enter new group name : </label>
            <input class="form-control" type="text" name="group_name_f">
          </div>
            <button type="submit" name="edit" class="btn btn-primary pull-right">Edit</button>
        </form>
      </div>
    </div>
  </div>
    </div>
    </body>
</html>
