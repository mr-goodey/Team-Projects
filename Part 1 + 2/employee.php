<?php
	session_start();
	$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<style>
	.nav-link{
		color: white;
		transition: 0.3s;
		border-style: none;
	}

	.nav-link:hover{
		background-color: aqua;
		color: black;
		border-style: none;
	}
	.logOutButton{
		background-color: red;
		border-radius: 5px;
		float: right;
		margin-right: 15px;
		margin-top: 30px;
		height: 25px;
		font-size: 11px;
		border-style: none;
		transition: 0.3s;
	}

	.logOutButton:Hover{
		background-color:  #7E0404;
	}

</style>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 	
		<link rel="stylesheet" href="stylesheet.css">
		<title>Manager</title>
		<form  id="logout" action = "newAccess.html" method = "post" style="display:inline; position: absolute; top: 0; right: 0;">
			<input class = "logOutButton" type="submit" name="submit" value="Log Out">
		</form>
	</head>
	<body>
		<h1 style="text-align: center;">Make-It-All</h1>
		<p style="text-align: center;">Welcome <?php echo $email?>!</p>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-td-tab" data-bs-toggle="tab" data-bs-target="#nav-td" type="button" role="tab" aria-controls="nav-td" aria-selected="true">To-Do List</button>
				<button class="nav-link" id="nav-p-tab" data-bs-toggle="tab" data-bs-target="#nav-p" type="button" role="tab" aria-controls="nav-p" aria-selected="false">Posts</button>
				<button class="nav-link" id="nav-v-tab" data-bs-toggle="tab" data-bs-target="#viewTeam" type="button" role="tab" aria-controls="nav-p" aria-selected="false">View Your Teams</button>
				<button class="nav-link" id="nav-yT-tab" data-bs-toggle="tab" data-bs-target="#teamKanbanBoard" type="button" role="tab" aria-controls="nav-p" aria-selected="false">Your Team's Kanban Board</button>
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
			<div class="tab-pane fade" id="teamKanbanBoard" role="tabpane2" aria-labelledby="nav-yT-tab">
				<div id = "kanbanBoard">
					<?php include "kanbanBoard.php"; ?>
				</div>
			</div>
		</div>
	</body>
</html>