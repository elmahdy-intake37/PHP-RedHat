<?php
	session_start();
	$group_names = explode(PHP_EOL, shell_exec("cut -f1 -d: /etc/group"));
	$group_names_count = count($group_names) - 1;
	$all_users = explode(PHP_EOL, shell_exec("cut -f1 -d: /etc/passwd"));
	$all_users_count = count($all_users);
	unset($all_users[$all_users_count - 1]);
	$all_users_count -= 1;
	$group_and_members = array();

	$admin = "delete_manager";
	$user = "nothing";
//	$admin = $_SESSION['groupname'];
//	$user = $_SESSION['username'];	
	for($i = 0; $i < $group_names_count; $i++) {
		$group_names[$i] = trim($group_names[$i]);
	}
	
	for($i = 0; $i < $all_users_count; $i++) {
		$all_users[$i] = trim($all_users[$i]);
	}
	
	for($i = 0; $i < $group_names_count; $i++) {
		$members = explode(" ", shell_exec("members ".$group_names[$i]));
		$members_count = count($members);
		if(!empty($members[0])) {				
			for($j = 0; $j < $members_count; $j++) {
				$group_and_members[$group_names[$i]][trim($members[$j])] = 1;
			}
		}
		else {
			$group_and_members[$group_names[$i]] = array();
		}
	}
	
	function addUserToGroup($user_group) {
		$user_group = (string)$user_group;
		echo gettype($user_group);
		echo $user_group."<br>";
		$group_user = preg_split("/[\s:]+/", $user_group);
		echo $group_user[0]." ".$group_user[1]." aaa<br>";
		echo "adding ".$user_group."<br>";
	}

	function deleteUserFromGroup($user_group) {
		$user_group = (string)$user_group;
		$group_user = explode(':', $user_group);
		echo "deleting ".$user_group;
	}
?>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="node_modules/bootstrap/dist/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="node_modules/bootstrap/dist/css/bootstrap-theme.min.css" />
		<script src="node_modules/tether/dist/js/tether.min.js"></script>
		<script src="node_modules/jquery/dist/jquery.min.js"></script>
		<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="node_modules/jquery/external/jquery.redirect.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<?php
			include('header.php');
		?>
		<div class="jumbotron">
			<div class="container">
				<h1>Groups & Members</h1>
				<h3 id='deletefailed' class='text-danger'></h3>
			</div>
		</div>
		<div>
			<div class="row">
				
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<ul class="nav navbar-inverse nav-tabs nav-stacked" role="tab-list">
							<?php
								for($i = 0; $i < $group_names_count; $i++) {
									echo "<li role='presentation'><a id='group-".$group_names[$i]."' href='#".$group_names[$i]."' onclick='addEditDeleteGroupOption(id)' aria-controls='profile' role='tab' data-toggle='tab'>".$group_names[$i]."</a></li>";
								}
							?>
						</ul>
						<?php if($admin == "poweruser" || $admin == "edit_manager") { ?>						
						<a class='btn btn-lg btn-primary btn-block' href='create_group.php' style='margin-top: 5px'>
							<span class='glyphicon glyphicon-plus'></span> Create new group
						</a>
						<?php } ?>
					</div>
					<div class="col-md-9">
						<div class="tab-content">
							<?php
								for($i = 0; $i < $group_names_count; $i++) {
									echo "<div role='tabpanel' class='tab-pane' id='".$group_names[$i]."'>";
									echo "<ul class='nav navbar nav-stacked'>";									
									for($j = 0; $j < $all_users_count; $j++) {
										$exist = isset($group_and_members[$group_names[$i]][$all_users[$j]]);										
										//echo $exist." ".$group_names[$i]." ".$all_users[$j]." ".$group_and_members[]." ".$i." ".$j."<br>";
										$group_membership_manage = ">".$all_users[$j];
										if($admin == 'poweruser' || $admin == 'edit_manager')
											$group_membership_manage = "id='".$group_names[$i]."s3pudZsGN4GQc5iQC0FE".$all_users[$j]."' onclick='".($exist == 1 ? "deleteFromGroup(id)" : "addToGroup(id)")."'>".$all_users[$j]."<span class='pull-right ".($exist == 1 ? "glyphicon glyphicon-minus text-danger" : "glyphicon glyphicon-plus text-primary")."'></span>";
										echo "<li role='presentation'><a href='#'".$group_membership_manage."</a></li>";
									}
									echo "</ul>";
									echo "</div>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			function addToGroup(id) {
				document.querySelector("#deletefailed").innerHTML = "";
				$.ajax({
					type: 'POST',
					url: 'action.php',
					data: {
						'add': ' ',
						'info': id,
						'remote_user': "<?php echo $user; ?>",
						'remote_group': "<?php echo $admin; ?>"
					},
					success: function(msg) {
						$("#" + id + " span").removeClass("glyphicon glyphicon-plus text-primary");
						$("#" + id + " span").addClass("glyphicon glyphicon-minus text-danger");
						$("#" + id).attr("onclick", "deleteFromGroup(id)");
					}
				});
			}
			
			function deleteFromGroup(id) {
				document.querySelector("#deletefailed").innerHTML = "";
				$.ajax({
					type: 'POST',
					url: 'action.php',
					data: {
						'delete': ' ',
						'info': id,
						'remote_user': "<?php echo $user; ?>",
						'remote_group': "<?php echo $admin; ?>"
					},
					success: function(msg) {
						$("#" + id + " span").removeClass("glyphicon glyphicon-minus text-danger");
						$("#" + id + " span").addClass("glyphicon glyphicon-plus text-primary");
						$("#" + id).attr("onclick", "addToGroup(id)");
					}
				});
			}
			
			function addEditDeleteGroupOption(id) {
				document.querySelector("#deletefailed").innerHTML = "";
				var groupName = id.split("-")[1];
				$(".group-option").remove();
				editButton = "<?php if($admin == 'poweruser' || $admin == 'edit_manager') { ?><button class='traverse' onclick='editThisGroup(\"" + groupName + "\")'><span class=' glyphicon glyphicon-edit text-primary'></span></button><?php } ?>";
				deleteButton = "<?php if($admin == 'poweruser' || $admin == 'delete_manager') { ?></button><button class='traverse' onclick='deleteThisGroup(\"" + groupName + "\")'><span class=' glyphicon glyphicon-minus text-danger'></span></button><?php } ?>";
				$("#" + id).append("<div class='group-option btn-group pull-right'>" + editButton + deleteButton + "</div>");           
			}

			function deleteThisGroup(group) {
				document.querySelector("#deletefailed").innerHTML = "";
				$.ajax( {
					type: 'POST',
					url: 'delete.php',
					data: {
						'group_name': group,
						'remote_user': "<?php echo $user; ?>",
						'remote_group': "<?php echo $admin; ?>"
					},
					success: function(msg) {
						if(msg.trim() == "0") {					
							$("#group-" + group).remove();
							$("#" + group).remove();
						}
						else {
							document.querySelector("#deletefailed").innerHTML = "Error " + msg + ": Cannot delete group '" + group + "' Please refer to online resources (Hint most likely group's primary user exists";
						}
					}
				});
			}

			function editThisGroup(group) {
				$.redirect('updategroup.php', {'group_name': group, 'remote_user': "<?php echo $user; ?>", 'remote_group': "<?php echo $admin; ?>"});
			}
		</script>
	</body>
</html>
