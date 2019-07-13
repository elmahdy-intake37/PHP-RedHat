<?php	
	session_start();
	include_once "log/LogsFunctions.php";
	include('check_request.php');
	$group_name= $_POST['group_name'];
	$remote_group = $_POST['remote_group'];
	$remote_user = $_POST['remote_user'];
	exec("sudo groupdel '$group_name'", $out, $code);
	echo $code;
	if($code == 0) {
		infolog("Successfully deleted group '".$group_name."'", "Success");
	}
	else {
		errlog("Error ".$code.": unable to delete group '".$group_name."'");
	}
?>
