
<?php
  session_start();
  include_once "log/LogsFunctions.php";

$show_shell=explode(PHP_EOL, shell_exec('cat /etc/shells'));
$shell_len=count($show_shell) -1;
$code = 0;
$user_name = $_POST['user_name'];
$user_pass = $_POST['password'];
$conf_pass=$_POST['conpasswd'];
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$shell=$_POST['shell'];
$ret_useradd = 0;
$ret_passwd = 0;

$admin= $_SESSION['groupname'];
$user=$_SESSION['username'];

if(isset($_POST['create'])){
        exec('sudo useradd -m -p '.$user_pass." -c '".$first_name." ".$last_name."' -s ".$shell." ".$user_name,$ret_useradd,$code);

        if($code==0)
          infolog("Successfully added userto the system","Success");
        else
          errlog("Error  unable to create user ");







}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script type="text/javascript">
  function validate() {
      var firstname = document.getElementById("user_name");
       var alpha = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/
      if (firstname.value == "") {
          alert('Please enter Name');
          return false;
      }
      else if (!username.value.match(alpha)) {
          alert('Invalid ');
          return false;
     }
     else
     {
      return true;
     }
  }
function check(){
  var username=document.getElementById("user_name");
  var firstname=document.getElementById("first_name");
  var lastname=document.getElementById("last_name");
  var password=document.getElementById("txtPassword");
  var conpasswd=document.getElementById("txtConfirmPassword");
    if(username.value=="" || firstname.value=="" || lastname.value==""||password.value==""||conpasswd.value==""){
      document.querySelector("#invalidinput").innerHTML = "Please fill all fields";
	  return false;
    }else{
      return true;
    }
  }
     function Validate() {
         var password = document.getElementById("txtPassword").value;
         var confirmPassword = document.getElementById("txtConfirmPassword").value;
         if (password != confirmPassword) {
			document.querySelector("#mismatch").innerHTML = "Password mismatch please re-enter password";             
			return false;
         }
         return true;
     }
 </script>
</head>
<body  onsubmit="return Validate() && check();">
  <?php include('header.php'); ?>
  <div class="jumbotron">
    <h1>User creation</h1>
    <?php
      if($code != 0) {
        ?>
        <h3 class='text-danger'> <?php if($code != 0) { ?> Error occured please check <a target='_blank' href="http://www.google.com/?q=exit+error+<?=$code?>+bash">Google search</a> <?php } ?></h3>
		<h3 class="text-danger" id='mismatch'></h3>		
		<h3 class="text-danger" id='invalidinput'></h3>        
		<?php
      }
    ?>
  </div>


<div class="container">
  <div class="row">
    <div class="col-md-4">
    </div>
    <div class='col-md-4'>
  <h2>Welcome</h2>
  <form method="POST" action="createanddelete.php">
    <div class="form-group">
      <label class="control-label" for="user_name">Username</label>
      <input type="text" class="form-control" id="user_name" name="user_name" placeholder="UserName">
    <div class="form-group">
      <label class="control-label" for="user_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="first name" />

        <div class="form-group">
          <label class="control-label" for="user_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="last name" />

    </div>
    <div class="form-group">
      <label class="control-label" for="pwd">Password</label>
        <input type="password" name="password"  class="form-control" id="txtPassword" placeholder="Enter password">
<div class="form-group">
  <label class="control-label" for="pwd">Confirm Password</label>
        <input type="password" name="conpasswd"  class="form-control" id="txtConfirmPassword" placeholder="Repeat password">
           <p id="password_status"> <p>
    </div>

<div class="form-group">
      <label class='control-label' for="shel1">Default Shell</label>
      <select name="shell" class="form-control" id="shel1">
<?php
        for($i=1;$i<$shell_len;$i++)
        {
          echo "<option ";
          if($i == 1)
            echo "selected";
          echo ">".$show_shell[$i]."</option>";
        }
          ?>
      </select>
  </div>
</div>

        <button type="submit" id="submit" class="btn btn-primary btn-block" name="create">CreateUser</button>
      </form>
</div>
</div>
</div>

</body>
</html>
