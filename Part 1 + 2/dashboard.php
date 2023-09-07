<head>
	<style>
	.managerKBoard{
		display: flex;
		flex-direction: row;
	}

	.addTaskFormPage{
		margin: 10px;
	}

	.inputForm{
		border-radius: 3px;
		padding: 1px;

	}
	.submitBtns{
		background-color: aqua;
		border-radius: 5px;
		border-style: none;
		transition: 0.3s;
	}

	.submitBtns:Hover {
		background-color: #0C9DA4;
	}

	.viewTeamButtons{
		background-color: yellow;
		border-radius: 5px;
		border-style: none;
		transition: 0.3s;
		color: black;
	}
	.viewTeamButtons:hover{ 
		background-color: #B5B90B; 
		border-style: none;

	}

	.deleteButtons{
		background-color: red;
		border-radius: 5px;
		border-style: none;
		transition: 0.3s;
		color: black;
	}
	
	.deleteButtons:hover{ 
		background-color: #7E0404; 
		border-style: none;

	}

	#kanbanBoard2 #zones > .zone > .tasks > tbody > *, #kanbanBoard2 #zones h1{
			position: relative;
			z-index: 100;
		}

	.zone{
        position:relative;
        z-index: 100;
        background-color: #2d2d2d;
        margin: 5px;
        border-radius: 5px;
        padding: 10px;
    }

	#taskForms{
		text-align:left;
	}

	#addTaskForm, #editTaskForm, #teamInfo{
		background-color: #2d2d2d;
        margin: 5px;
        border-radius: 5px;
        padding: 10px;
		display: inline-block;
	}



	</style>
</head>
<body>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
		google.charts.load('current', {'packages':['corechart']});
		
		</script>
	<script>

		let currentTeamNo = 0;
		let prevId = -1;
		let prevStatus = "";
		
		function dragStart3(event){
		event.dataTransfer.setData("text", event.target.id);
		$(`#kanbanBoard2 #zones h1, #kanbanBoard2 #zones > .zone > .tasks > tbody > *:not(tr[id="${event.target.id}"])`).css({
		"z-index": "-1",
		});
		}



		function dragEnd3(event){
		$(`#kanbanBoard2 #zones h1, #kanbanBoard2 #zones > .zone > .tasks > tbody > *:not(tr[id="${event.target.id}"])`).css({
		"z-index": "100",
		});
		}

		function ondragover(event){
			event.preventDefault();
		}

		function allowDrop(event) {
			event.preventDefault();
		}

		function drop3(event, target){
			event.preventDefault();
			let id = event.dataTransfer.getData("text");
			console.log("ID"+id);
			let dest = event.target.id;
			let src = $(`#kanbanBoard2 tr#${id}`).closest('table').attr('id');
			console.log("SRC"+src);
			console.log("DEST"+dest);
			let email = $(`#kanbanBoard2 tr#${id}`).attr('class');
			console.log("EMAIL"+email);
			if(statuses.includes(src) && statuses.includes(dest)){
				console.log("MOVING!");
				moveTask3(id,src,dest,email);
			}
		}

		function moveTask3(id,src,dest,email){
			console.log("kanban task");
			//`.tasks#${src} > tbody > tr#${id} > td#taskDesc`
			let desc = $(`#kanbanBoard2 .tasks#${src} > tbody > tr#${id}`).children("#taskDesc").html(); //gets the taskDesc data of that table row
			console.log("desc"+desc);
			//let date = $(`.tasks#${src} > tbody > #${id} > #dueDate`).html(); doesnt work?
			let date = $(`#kanbanBoard2 .tasks#${src} > tbody > tr#${id}`).children("#dueDate").html(); //gets the due date data of that table row
			console.log("date"+date);
			let hours = $(`#kanbanBoard2 .tasks#${src} > tbody > tr#${id}`).children("#manHours").html();
			$.post("alterTaskStatus.php", {'taskId': id, "status": dest}, function(responseData){
				$(`#kanbanBoard2 .tasks#${src} > tbody > tr#${id}`).remove();
				let style = "background-color : transparent;"
				if(id==prevId){
					style = "background-color : blue;"
					prevStatus = dest;
				}
				$(`#kanbanBoard2 .tasks#${dest} > tbody`).append(`<tr id=${id} class = "${email}" style = "${style}" draggable = "true" ondragstart="dragStart3(event)" ondragend = "dragEnd3(event)" onclick="event.preventDefault();trClicked1('${dest}',${id})">
					<td id='taskDesc'>${desc}</td>
					<td id='dueDate'>${date}</td>
					<td id='manHours'>${hours}</td>
				</tr>`);
			});
			/*
			console.log("called"+id);
			console.log(i);
			$.post("completedTask.php", {'taskId': id}, function(responseData){
				console.log(responseData);
				$(`#toDoList > tbody > #${i}`).remove();
			});
			*/
			if(dest=="complete"){
				console.log("testing123123");
				removeFromTeam3(email);
			}
			getChartData(currentTeamNo);
		}

		function removeFromTeam3(email){
			$.post("removeFromTeam.php", {'teamNo' : currentTeamNo, 'email':email}, function(responseData){
				console.log("remove "+responseData);
				if(responseData==currentTeamNo){
					/*
                	$(`#kanbanBoard2 .tasks#complete > tbody > tr`).each(function(){
						if(this.className==email){
							this.remove();
						}
					})
					*/
					prevId=-1;
					prevStatus="";
					$(`#teamInfo ul li[id="${email}"]`).remove();
            	}
			})
		}

			function viewEmployeeTasks(){
				console.log("test2");
				$.get("managersDashboard.php", function(responseData){
					console.log("ajax");
					console.log("response"+responseData);
					len = responseData.length;
					console.log(len);
					for(let i=0; i<len; i++){
						let team = responseData[i].teamNo;
						let teamLeaderEmail = responseData[i].teamLeader;
						$('#employeesTasks').append(`<tr id = ${team}>
							<td class = "teamNo">${team}</td>
							<td class = "teamLeader">${teamLeaderEmail}</td>
							<td><button type="button" id = ${team} class = "viewTeamButtons" onclick = "buttonPressed(this.id)">View Team</button></td>
							<td><button type="button" id = ${team} class = "deleteButtons" onclick = "delbuttonPressed(this.id)">Delete Team</button></td>
							</tr>`);
					}
				}, "json");
			}

		function editTask3(status,taskDesc,dueDate,manHours,id){
			$.post("editTask.php", {'taskId' : id, 'taskDesc' : taskDesc, 'dueDate' : dueDate, 'manHours' : manHours}, function(responseData){
				console.log("res"+responseData);
				if(taskDesc!=''){
					$(`#kanbanBoard2 .tasks#${status} > tbody > tr#${id}`).children("#taskDesc").html(taskDesc);
				}
				
				if(dueDate!=''){
					$(`#kanbanBoard2 .tasks#${status} > tbody > tr#${id}`).children("#dueDate").html(dueDate);
				}

				if(manHours !=''){
					$(`#kanbanBoard2 .tasks#${status} > tbody > tr#${id}`).children("#manHours").html(manHours);
				}
			})
		}

		function delbuttonPressed(teamId) {
			$.get("removeTeam.php", {'teamId' : teamId}, function(responseData){
					if(responseData==teamId){
						console.log("TRUE");
						$(`#employeesTasks tr#${teamId}`).remove();
						if(responseData==currentTeamNo){
							$("#dashboard #kanbanBoard2 .tasks td").parent().remove(); //removes every table row except the header
							prevId=-1;
							prevStatus="";
							//remove the pie chart
							$("#piechart").hide();
							//hide view team members
							$("#teamInfo").hide();
						}
					}else{
						alert("Cannot delete team. \nThey still have tasks to complete")
					}
				});
			}


		function buttonPressed(teamNo){
			prevId=-1;
			prevStatus="";
			currentTeamNo = teamNo;
			console.log("curr "+currentTeamNo);
			collectTeamInfo(teamNo);
			getKanbanBoard(teamNo);
		}

		function collectTeamInfo(teamNo){
			console.log("CALLED");
			$.get("getTeamMembers.php", {'teamId' : teamNo}, function(responseData){
					console.log("response"+responseData);
					len = responseData.length;
					console.log(len);
					$("#teamInfo").show();
					$('#teamInfo ul').remove();
					$('#teamInfo').append(`<ul>`);
					for(let i=0; i<len; i++){
						let email = responseData[i].email;
						console.log(email);
						$('#teamInfo ul').append(`<li id = "${email}">${email}</li>`);
					}
					$('#teamInfo').append(`</ul>`);
				}, "json");
		}

		function getKanbanBoard(teamNo){
			$("#dashboard #kanbanBoard2 .tasks td").parent().remove(); //removes every table row except the header
			$.get("retriveTeamTasks.php", {'teamNo' : teamNo}, function(responseData){
				console.log(responseData);
				let len = responseData.length;
				for(let i=0; i<len; i++){
					let desc = responseData[i].taskDesc;
					let date = responseData[i].dueDate;
					let id = responseData[i].taskId;
					let hours = responseData[i].manHours;
					let status = responseData[i].taskStatus;
					let email = responseData[i].email;
					console.log(`#dashboard #kanbanBoard2 .tasks#${status}`);
					$(`#dashboard #kanbanBoard2 .tasks#${status}`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart3(event)" ondragend = "dragEnd3(event)" onclick = "event.preventDefault();trClicked1('${status}',${id})">
					<td id='taskDesc'>${desc}</td>
					<td id='dueDate'>${date}</td>
					<td id='manHours'>${hours}</td>
					</tr>`);
				}
			}, "json")
			getChartData(teamNo);
		}

		function getChartData(teamNo){
			$("#piechart").show();
			console.log(teamNo);
			$.get("getChartData.php", {'teamNo' : teamNo}, function(responseData){
				console.log("data");
				console.log(responseData);
				let len = responseData.length;
				let chartData = [];
				chartData[0] = ['TaskStatus', 'No of Tasks'];
				for(let i =0; i<len; i++){
					chartData[i+1] = [responseData[i].taskStatus, parseInt(responseData[i].noOfTasks)];
					
				}
				console.log("CHART");
				console.log(chartData);
				google.charts.setOnLoadCallback(function () {
				var data = google.visualization.arrayToDataTable(chartData);

				var options = {
				title: 'Task Summary'
				};

				var chart = new google.visualization.PieChart(document.getElementById('piechart'));

				chart.draw(data, options);
			});
			}, "json");
		}

		/*
		$("#addTaskForm .forms").submit(function(event)){
			event.preventDefault();
			let desc = (`#addTaskForm .forms#${status} > #taskDesc`).val();
			let date = (`#addTaskForm  > #dueDate`).val();
			let taskEmail = (`#addTaskForm} > #email`).val();
			let teamNo = (`#addTaskForm  > #team`).val();
				$.post("addTeamTask.php", {'taskDesc' : desc, 'dueDate' : date, 'email' : taskEmail, 'team' : teamNo}, function(responseData){
					let id = parseInt(responseData);

				});
			}

		*/

		function trClicked1(status,id){
			if(prevId!=-1 && prevStatus != ""){
				$(`#kanbanBoard2 .tasks#${prevStatus} > tbody > tr#${prevId}`).css({
				"background-color" : "transparent",
				});
			}
			
			$(`#kanbanBoard2 .tasks#${status} > tbody > tr#${id}`).css({
				"background-color" : "blue",
			});
			prevId = id;
			prevStatus = status;
		}

		$(document).ready(function() {
			$("#teamInfo").hide();
			$("#addTeamTask").submit(function(event){
				event.preventDefault();
				let taskDesc = $("#addTeamTask > #taskDesc").val();
				let dueDate = $("#addTeamTask > #dueDate").val();
				let email = $("#addTeamTask > #email").val();
				let teamNo = $("#addTeamTask > #team").val();
				let manHours = $("#addTeamTask > #manHours").val();
				let teamLeader = $(`#employeesTasks > tbody > tr#${teamNo} > td.teamLeader`).text();
				console.log("tl "+teamLeader)
				console.log("manHours"+manHours);
				$.post("managerAddTeamTask.php",{'taskDesc' : taskDesc, 'dueDate' : dueDate, 'email' : email, 'team' : teamNo, 'manHours' : manHours, 'teamLeader' : teamLeader}, function(responseData){
					console.log("DONE!!!!!");
					console.log(responseData);
					console.log(responseData[0]);
					if(responseData[0]=="1"){
						alert("New Task added to new team!");
						$("#employeesTasks").append(`<tr id = ${team}>
							<td class = "teamNo">${teamNo}</td>
							<td class = "teamLeader">${email}</td>
							<td><button type="button" id = ${teamNo} class = "viewTeamButtons" onclick = "buttonPressed(this.id)">View Team</button></td>
							<td><button type="button" id = ${teamNo} class = "deleteButtons" onclick = "delbuttonPressed(this.id)">Delete Team</button></td>
							</tr>`);
					}else{
						alert(`New Task added to Team ${teamNo}`);

					}
					console.log("current team is "+currentTeamNo);
					if(currentTeamNo == teamNo){ //if kanbanboard youre currently viewing is the same as the one youre adding to
						let id = responseData[1];
						$(`#kanbanBoard2 .tasks#backlog > tbody`).append(`<tr id=${id} class = "${email}" draggable = "true" ondragstart="dragStart3(event)" ondragend = "dragEnd3(event)" onclick="event.preventDefault();trClicked1('backlog',${id})">
							<td id='taskDesc'>${taskDesc}</td>
							<td id='dueDate'>${dueDate}</td>
							<td id='manHours'>${manHours}</td>
						</tr>`);
						getChartData(currentTeamNo)
						let exists = false;
						$("#teamInfo ul li").each(function(){
							console.log("id "+this.id);
							if(this.id == email){
								exists=true;
							}
						})
						if(!exists){
							$("#teamInfo ul").append(`<li id="${email}">${email}</li>`)
						}
						
					}
				}, "json");
			})

			$("#editTeamTask").submit(function(event){
				event.preventDefault();
				if(prevId !=-1 && prevStatus !=""){
					let taskDesc = $("#editTeamTask > #taskDesc").val();
					let dueDate = $("#editTeamTask > #dueDate").val();
					let manHours = $("#editTeamTask > #manHours").val();
					console.log("dueDate is "+dueDate);
					console.log("mh "+manHours);
                	if(taskDesc!='' || dueDate != '' || manHours !=''){
						console.log("editing");
                    	editTask3(prevStatus,taskDesc,dueDate,manHours,prevId);
                	}

				}else{
					alert("Error. No task is currently selected.");
				}
			})

			$("#createTeam").submit(function(event){
				event.preventDefault();
				let teamLeader = $("#createTeam > #email").val();
				let teamNo = $("#createTeam > #team").val();
				let exists = false;
				$("#employeesTasks > tbody > tr").each(function(){
					if(teamNo == this.id){
						console.log(teamNo);
						console.log(this.id);
						exists=true;
					}
				})
				if(exists){
					alert("Team already exists.");
				}else{
					$.post("createTeam.php", {"teamLeader" : teamLeader, "teamNo" : teamNo}, function(responseData){
						console.log("RES"+responseData);
						if(responseData==teamNo){
							$('#employeesTasks').append(`<tr id = ${teamNo}>
							<td class = "teamNo">${teamNo}</td>
							<td class = "teamLeader">${teamLeader}</td>
							<td><button type="button" id = ${teamNo} class = "viewTeamButtons" onclick = "buttonPressed(this.id)">View Team</button></td>
							<td><button type="button" id = ${teamNo} class = "deleteButtons" onclick = "delbuttonPressed(this.id)">Delete Team</button></td>
							</tr>`);
						}
					})
				}
			})
				
			

			$.get("getEmployees.php",function(responseData){
				let len = responseData.length;
					for(let i = 0; i<len; i++){
						console.log(responseData[i].email);
						$("select#email").append(`<option value = ${responseData[i].email}>${responseData[i].email}</option>`);
					}
			}, "json")
			viewEmployeeTasks();

		});


	</script>
					
					<div style='overflow-x:auto;'>
						<table id ='employeesTasks' class='table table-dark table-striped'>
							<tr>
								<th>Team Number</th>
								<th>Team Leader</th>
								<th></th>
								<th></th>	
							</tr>				
						</table>
						<ul>
						</ul>
					</div>



				<div id = "createTeamForm">
						<form class = "forms" id="createTeam">
							<h4>Create a team:</h4>
							<label for="email">Team Leader:</label>
							<select id ="email" name ="email">

							</select><br>

							<label for="team">Team Number:</label>
							<input type="number" min = "1" id="team" name="team" class="inputForm" required><br>

							<input type="submit" name="backlog" class = "submitBtns"><br>

						</form>
				</div>
				<div id = "taskForms">
					<div id="addTaskForm" class="addTaskFormPage">
						<h4>Add a task for this team:</h4>
						<form class = "forms" id="addTeamTask">

							<label for="taskDesc">Task Description:</label>
							<input type="text" id="taskDesc" name="taskDesc" class="inputForm"required><br>

							<label for="dueDate">Due Date:</label>
							<input type="date" id="dueDate" name="dueDate" class="inputForm" required><br>

							<label for="email">Who is this task for?</label>
							<select id ="email" name ="email">

							</select><br>

							<label for="team">Team Allocation for task:</label>
							<input type="number" min = "1" id="team" name="team" class="inputForm" required><br>

							<label for="manHours">Man Hours:</label>
							<input type="number" min="0" id="manHours" name="manHours" class="inputForm" required><br>

							<input type="submit" name="backlog" class = "submitBtns"><br>

						</form>
					</div>
					<div id="editTaskForm" class="editTaskFormPage">
						<h4>Edit a task:</h4>
						<form class = "forms" id="editTeamTask">

							<label for="taskDesc">Task Description:</label>
							<input type="text" id="taskDesc" name="taskDesc" class="inputForm"><br>

							<label for="dueDate">Due Date:</label>
							<input type="date" id="dueDate" name="dueDate" class="inputForm"><br>

							<label for="manHours">Man Hours:</label>
							<input type=number min=0 id="manHours" name="manHours" class="inputForm"><br>

							<button type="submit">Edit</button><br>

						</form>
					</div>
					</div>
					<div id = "teamInfo">
						<h4>Team Members:</h4>
					</div>
					<div id = "kanbanBoard2">
					<div id = "zones" class = "managerKBoard" ondrop = "drop3(event)" ondragover = "allowDrop(event)">
						<div class = "zone" id = "backlog">
							<table class = "tasks" id ="backlog">
							<h1 id = "backlog">Backlog</h1>
								<tr>
									<th>Task Description</th>
									<th>Date Due</th>
									<th>Man Hours</th>
								</tr>
							</table>
						</div>
						<div class = "zone" id = "pending">
							<table class = "tasks" id ="pending">
							<h1 id = "pending">Pending</h1>
								<tr>
									<th>Task Description</th>
									<th>Date Due</th>
									<th>Man Hours</th>
								</tr>
							</table>
						</div>
						<div class = "zone" id = "progress">
							<table class = "tasks" id ="progress">
							<h1 id = "progress">Progress</h1>
								<tr>
									<th>Task Description</th>
									<th>Date Due</th>
									<th>Man Hours</th>
								</tr>
							</table>
						</div>
						<div class = "zone" id = "complete">
							<table class = "tasks" id ="complete">
							<h1 id = "complete">Complete</h1>
								<tr>
									<th>Task Description</th>
									<th>Date Due</th>
									<th>Man Hours</th>
								</tr>
							</table>
						</div>
					</div>
					</div>
					<div id="piechart" style="width: 600px; height: 300px;"></div>
	</body>

					
						


			

		
