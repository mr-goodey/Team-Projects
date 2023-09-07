<?php
	session_start();
	$email = $_SESSION['email'];
	$_SESSION['email'] = $email;
	echo $email;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<title>Team Leader</title>
	</head>
	<body>
		<h1 style="text-align: center;">Make-It-All</h1>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-td-tab" data-bs-toggle="tab" data-bs-target="#nav-td" type="button" role="tab" aria-controls="nav-td" aria-selected="true">To-Do List</button>
				<button class="nav-link" id="nav-p-tab" data-bs-toggle="tab" data-bs-target="#nav-p" type="button" role="tab" aria-controls="nav-p" aria-selected="false">Posts</button>
				<button class="nav-link" id="nav-v-tab" data-bs-toggle="tab" data-bs-target="#viewTeam" type="button" role="tab" aria-controls="nav-p" aria-selected="false">View Your Team</button>
				<button class="nav-link" id="nav-i-tab" data-bs-toggle="tab" data-bs-target="#nav-i" type="button" role="tab" aria-controls="nav-i" aria-selected="false">Invite</button>
			</div>
		</nav>
		
		<div class="tab-content" id="nav-tabContent">
			<div  class="tab-pane fade show active" id="nav-td" role="tabpanel" aria-labelledby="nav-td-tab">
				<div id=toDoList>
					<?php include "toDoList.php"; ?>
				</div>
			</div>
			<div class="tab-pane fade" id="nav-p" role="tabpanel" aria-labelledby="nav-p-tab">
				<div id = "posts">
					<?php include "messages.php"; ?>
				</div>
			</div>
			<div class="tab-pane fade" id="viewTeam" role="tabpane2" aria-labelledby="nav-v-tab">
				<div id = "teamDashboard">
					<?php include "teamDashboard.php"; ?>
				</div>
			</div>
			<div class="tab-pane fade" id="nav-i" role="tabpanel" aria-labelledby="nav-i-tab">
				<div id="invite">
					<form id="inviteForm" action="invite.php" onsubmit="return invValidation()" style="text-align: center;" method="post">
						<label for="email1">Email:</label>
						<input type="text" id="email1" name="email1"><br><br>
						<label for="type1">Type:</label>
						<select id="type1" name="type1">
							<option value="emp">Employee</option>
							<option value="mgr">Manager</option>
						</select><br><br>
						<input type="submit" value="Invite"><br><br>
						<div id="Response1"></div>
					</form>
					<script>
						function invValidation() {
							let email = document.forms["inviteForm"]["email"].value;
							let result = email.endsWith("@make-it-all.co.uk");
							if (result == false) {
								return false;
							}
						}
						var frm1 = $('#inviteForm');
						frm1.submit(function (e) {
							e.preventDefault();
							$.ajax({
								type: frm1.attr('method'),
								url: frm1.attr('action'),
								data: frm1.serialize(),
								success: function (data1) {
									if(data1.status == 'correct'){
										$("#Response1").html("Invite sent");
									}else if(data1.status == 'incorrect'){
										$("#Response1").html("Cannot send invite")
									}
								},
								error: function (data1) {
									$("#Response1").html("An error occurred");
								},
							});
						});
					</script>
				</div>
			</div>
		</div>
	</body>
</html>