<?php
session_start();
$username=$_SESSION['login']=$_POST['username'];
$line=explode(":", exec('grep '.$username.' /etc/passwd'));
//print_r($line);
$passwd=$line[1];
$uid=$_SESSION['uid']=$line[2];
$gid=$_SESSION['gid']=$line[3];
$primary_group=$_SESSION['primary_group']=exec('getent group '.$gid.' | cut -f1 -d:');
$secondary_groups=$_SESSION['secondary_groups']=explode(" ", trim(shell_exec('groups '.$username.' | cut -f2 -d:')));
//print_r( $secondary_groups);
$comment=$_SESSION['comment']=$line[4];
$home=$_SESSION['home']=$line[5];
$default_shell=$_SESSION['default_shell']=$line[6];
//add back button

$remote_group = $_SESSION['groupname'];
$remote_group = "poweruser";
$remote_user =$_SESSION['username'];

?>
<!DOCTYPE html>
<html>
<head>
	<title><?= $username ?></title>
	<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
</head>
<body>
	<?php
		include('header.php');
	?>
<div >

<table class="table table-responsive table-bordered table-hover">
	<tr class="bg-info">
		<th>username</th><td><?= $username ?></td>

	</tr>
	<tr class="bg-danger">
		<th>password</th><td><?= $passwd ?></td>

	</tr>
	<tr class="bg-primary">
		<th>uid</th><td><?= $uid ?></td>

	</tr>
	<tr class="bg-warning">
		<th>group</th><td><?= $primary_group ?></td>

	</tr>
	<tr class="bg-info">
		<th>comment</th><td><?= $comment ?></td>

	</tr>
	<tr class="bg-success">
		<th>home</th><td><?= $home ?></td>

	</tr>
	<tr class="bg-success">
		<th>default shell</th><td><?= $default_shell ?></td>
	</tr>


</table> <br>
<div class="form-group">
		<div class='container'>
			<div class='row'>
						<div class="col-md-1">
						<?php
								if($remote_group == "poweruser" || $remote_group == "edit_manager") { ?>
						<a href="edit-user.php" class="btn btn-success" >Edit User</a></div><?php } ?>
				<div class="col-md-1">

						<?php
				
						 if($remote_group == "poweruser" || $remote_group == "delete_manager") { ?>
						<form class="" action="deleteuser.php" method="post">
								<input type="hidden" name="remote_group" value="<?= $remote_group ?>">
								<input type="hidden" name="remote_user" value="<?=$remote_user ?>">
								<button type="submit" name="delete_user" value="<?=$username?>" class="btn btn-danger">Delete User</button>
						</form>
						<?php } ?>
			</div>
		</div>
</div>
</div>
</div>
<div>
<table class="table table-responsive table-bordered table-hover">
	<tr>
		<th>Secondary groups</th>
		<?php
			foreach ($secondary_groups as $grp ) {
				if($grp != $primary_group){
				?>
				<td>
					<?= $grp ?>
				</td>
				<?php
				}
			}
		?>
	</tr>
</table>
</div>
</body>
</html>
