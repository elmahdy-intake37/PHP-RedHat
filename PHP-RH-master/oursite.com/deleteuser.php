<?php
    include('check_request.php');
    include_once "log/LogsFunctions.php";

    session_start();
    $user_name= $_POST['delete_user'];
    //echo " $user_name";
    $remote_group = $_POST['remote_group'];
  	$remote_user = $_POST['remote_user'];

    exec("sudo userdel '$user_name'", $out, $code);
  	echo $code;
  	if($code == 0) {
  		infolog($remote_group, $remote_user, "Successfully deleted user '".$user_name."'", "Success");
  	}
  	else {
  		errlog($remote_group, $remote_user, "Error ".$code.": unable to delete user '".$user_name."'");
  	}

    exec("sudo rm -rf /home/'$user_name'",$out, $code1);
    echo $code1;
    if($code1 == 0) {
      infolog($remote_group, $remote_user, "Successfully deleted home directory for user '".$user_name."'", "Success");
    }
    else {
      errlog($remote_group, $remote_user, "Error ".$code1.": unable to delete home directory for user '".$user_name."'");
    }
    header('Location: index.php');
 ?>
