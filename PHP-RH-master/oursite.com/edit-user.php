<?php
session_start();
//include('check_request.php');
// $_SESSION['username'] = "Logger";
// $_SESSION['groupname'] = "Group1";
// $_SESSION['projectNum'] = "1";

extract($_SESSION);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
        <title><?= $login ?> - User Edit</title>
    </head>
    <body>
        <?php
        include 'header.php';
        require("./log/LogsFunctions.php");
        $show_shell = explode(PHP_EOL, shell_exec('cat /etc/shells'));
        $shell_len = count($show_shell) - 1;
        $show_group = explode(PHP_EOL, shell_exec('cat /etc/group|cut -d: -f1'));
        array_pop($show_group);
//        $remote_user = $_SESSION['username'];
//        $remote_group = $_SESSION['groupname'];

        $exec_string = "";
        $success_str = "";
        $str_prefix = "sudo usermod ";
        $str_suffix = $login;

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
            if (isset($_POST['login']) && !empty($_POST['login'] && $_POST['login'] != $login)) {
                exec("sudo pkill -u " . $login);
                exec("sudo pkill -9 -u " . $login);
                $exec_string.="-l " . $_POST['login'] . " ";
            }
            if (!empty($_POST['fname'])) {
                $fname = $_POST['fname'];
                if (!empty($_POST['lname'])) {
                    $lname = $_POST['lname'];
                    $exec_string.="-c '" . $fname . " " . $lname . "' ";
                } else {
                    $exec_string.="-c '" . $fname . "' ";
                }
            }
            if (!empty($_POST['uid'])) {
                if (ctype_digit($_POST['uid'])) {
                    $exec_string .= "-u " . $_POST['uid'] . " ";
                }
                else{
                    errlog("Error '".$_POST['uid']."' is not a valid user id.");
                }
                
            }
            if (!empty($_POST['shell'])) {
                $shell = $_POST['shell'];
                $exec_string .= "-s '" . $shell . "' ";
            }
            if (!empty($_POST['passwd'])) {
                $passwd = $_POST['passwd'];
                // $login = $login;
                $pass_string = "echo '" . $login . ":'" . $passwd . "''|sudo chpasswd -c SHA512";
                exec($pass_string, $out, $pcode);
                if ($pcode == 0) {
                    infolog("Successfully changed the password of user  '" . $login . "'", "Success");
                } else {
                    errlog("Error " . $code . ": unable to cahnge password for user '" . $login . "'");
                }
            }
            if (!empty($_POST['prigroup'])) {
                $prigroup = $_POST['prigroup'];
                $exec_string .= "-g '" . $prigroup . "' ";
            }
//            
            $exec_string = $str_prefix . $exec_string . $str_suffix;
            exec($exec_string, $out, $code);
            if ($code == 0) {
                infolog("Successfully changed the data of user  '" . $login . "'", "Success");
                header("Location:index.php");
                exit();
            } else {
                errlog("Error " . $code . ": unable to cahnge data for user '" . $login . "'");
            }
        }
        ?>
        <div class="jumbotron">
            <div class="container">
                <h2>Edit user data</h2>
            </div>
        </div>
        <div class="container">
            <form class="form-horizontal" action="edit-user.php" method="post">
                <div class="form-group">
                    <label for="login" class="col-sm-2 control-label">Login-Name:</label>
                    <div class="col-sm-10">
                        <input id="login" type="text" class="form-control" name="login" value="<?= $login ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uid" class="col-sm-2 control-label">UID</label>
                    <div class="col-sm-10"><input id="uid" type="text" class="form-control" name="uid" value="<?= $uid ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label">First Name</label>
                    <div class="col-sm-10"><input id="fname" type="text" class="form-control" name="fname" value="<?= $comment ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lname" class="col-sm-2 control-label">Last Name</label>
                    <div class="col-sm-10">
                        <input id="lname" type="text" class="form-control" name="lname">
                    </div>
                </div>
                <div class="form-group">
                    <label for="shell" class="col-sm-2 control-label">Shell</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="shell" name="shell">
                            <?php
                            for ($i = 1; $i < $shell_len; $i++) {
                                echo "<option";
                                if ($show_shell[$i] == $default_shell) {
                                    echo " selected";
                                };
                                echo ">" . $show_shell[$i] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="passwd" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input id="passwd" type="password" class="form-control" name="passwd">
                    </div>
                </div>
                <div class="form-group">
                    <label for="prigroup" class="col-sm-2 control-label">Primary Group</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="prigroup" name="prigroup">
                            <?php
                            foreach ($show_group as $gname) {
                                echo "<option";
                                if ($gname == $primary_group) {
                                    echo " selected";
                                };
                                echo ">" . $gname . "</option>";
                            }
                            ?>
                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-success" value="Save changes">
                        <a href="index.php"><button type="button" class="btn btn-danger">Cancel</button></a>
                    </div>
                </div>

            </form>
        </div>
    </body>
</html>
