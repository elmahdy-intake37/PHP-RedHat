<?php
session_start();
$admin = "poweruser";
$user = "nothing";
// $_SESSION['groupname'] = $admin;
// $_SESSION['username'] = $user;
// $_SESSION['projectNum'] = 1;
$f=fopen("/etc/passwd", "r");
if(!$f){
echo "sorry can not open file";
exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>User Mangement System</title>
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

<div class="table-responsive">
<form action="userdetails.php" method="post">
<table class="table table-responsive table-bordered table-hover">
	<tr>
		<th>username</th>
		<th>comment</th>
	</tr>

		<?php
	while (! feof($f)) {
	//$l=fgets($f,100);
	$line=explode(':', fgets($f));

	if (strpos($line[6],'nologin'))
    	$rowstyle="color:red;" ;
	else
	    $rowstyle="";


	?>

	<tr style="<?= $rowstyle ?>" >
		<td>
		<input type="submit" class="traverse" name="username" value="<?= $line[0] ?>" />

		</td>

		<td>
			<?= $line[4] ?>
		</td>

	</tr>

	<?php
	}
	fclose($f);
	?>
</table>
</form>
</div>
</body>
</html>
