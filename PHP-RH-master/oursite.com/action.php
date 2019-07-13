<?php 
	session_start();
	include_once "log/LogsFunctions.php";
	include('check_request.php');
	$group_user = explode('s3pudZsGN4GQc5iQC0FE', $_POST['info']);
	$remote_group = $_POST['remote_group'];
	$remote_user = $_POST['remote_user'];
	if(isset($_POST['add'])) {
		exec("sudo usermod -a -G ".$group_user[0]." ".$group_user[1], $out, $code);
		if($code == 0) 
			infolog("Successfully added user '".$group_user[1]."' to group '".$group_user[0]."'", "Success");
		else 
			errlog("Error ".$code.": unable to delete user '".$group_user[1]." from group '".$group_user[0]."'");
	}
	else if(isset($_POST['delete'])) {
		exec("sudo gpasswd -d ".$group_user[1]." ".$group_user[0], $out, $code);
		if($code == 0)
			infolog("Successfully deleted user '".$remote_user."' from group '".$remote_group."'", "Success");
		else 
			errlog("Error ".$code.": unable to delete user '".$remote_user."' from group '".$remote_group."'");
	} 
?>
