<select name="teams" id="teams">
    <option value = ""></option>
</select>
<?php
    $email = $_SESSION['email'];
    $_SESSION['email'] = $email;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kanban Board</title>
<style>

    #kanbanBoard .tasks{
        padding-top: 15px;
    }

    #kanbanBoard .forms > span{
        padding-left: 25px;
        padding-top: 5px;
        padding-bottom: 5px;
        display:flex;
        justify-content: center; 
    }

    #kanbanBoard .tasks tr:not(:first-child):hover{
        cursor: pointer;
        background-color: #ccc;
    }

    #kanbanBoard #container{
        width: 100%;

    }

    #kanbanBoard #backlog{
        justify-conent: center;
    }

    #kanbanBoard .zone{
        padding: 15px;
        flex-wrap: wrap;
    }

    #kanbanBoard #zones{
        display: flex;
        flex-direction: row;
    }

    #kanbanBoard #pageContainer {
        display: flex;
        justify-content: center;
       
    }
    .zone{
        position:relative;
        z-index: 100;
        background-color: #2d2d2d;
        margin: 5px;
        border-radius: 5px;
        padding: 10px;
    }
    .dropDownMenu{
        background-color:  #2d2d2d;
        color: white;
    }

    #kanbanBoard #zones > .zone > .tasks > tbody > *, #kanbanBoard #zones h1{
        position: relative;
        z-index: 100;
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

</style>
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

    let tlrPromise;
    let currentStatus;
    let currentTaskId;
    let prevId=-1;
    let prevStatus="";

    function dragStart2(event){
        event.dataTransfer.setData("text", event.target.id);
        $(`#kanbanBoard #zones h1, #kanbanBoard #zones > .zone > .tasks > tbody > *:not(tr[id="${event.target.id}"])`).css({
            "z-index": "-1",
        });
    }



    function dragEnd2(event){
        $(`#kanbanBoard #zones h1, #kanbanBoard #zones > .zone > .tasks > tbody > *:not(tr[id="${event.target.id}"])`).css({
            "z-index": "100",
        });
    }

    function ondragover(event){
        event.preventDefault();
    }

    function allowDrop(event) {
        event.preventDefault();
    }

    function drop2(event, target){
        event.preventDefault();
        let id = event.dataTransfer.getData("text");
        console.log("ID"+id);
        let dest = event.target.id;
        let src = $(`#kanbanBoard tr#${id}`).closest('table').attr('id');
        console.log("SRC"+src);
        console.log("DEST"+dest);
        let email = $(`#kanbanBoard tr#${id}`).attr('class');
        console.log("EMAIL"+email);
        if(statuses.includes(src) && statuses.includes(dest)){
            console.log("MOVING!");
            moveTask2(id,src,dest,email);
        }
    }

    function trClicked2(email,status,id){
        tlrPromise.then(result => {
            if(result == "<?php echo $email?>"){ //if team leader is the person thats logged in
                if(prevId!=-1 && prevStatus != ""){
                    $(`#kanbanBoard .tasks#${prevStatus} > tbody > tr#${prevId}`).css({
                    "background-color" : "transparent",
                    });
                }
                
                $(`#kanbanBoard .tasks#${status} > tbody > tr#${id}`).css({
                    "background-color" : "blue",
                });
                prevId = id;
                prevStatus = status;
                }
        })
    }

    async function prepareTeamLeader(teamNo){
        return await $.ajax({
            type: 'GET',
            url: 'getTeamLeader.php',
            data: {
                'teamNo' : teamNo
            }
        });
    }

    function moveTask2(id,src,dest,email){
        console.log("kanban task");
        //`.tasks#${src} > tbody > tr#${id} > td#taskDesc`
        let desc = $(`#kanbanBoard .tasks#${src} > tbody > tr#${id}`).children("#taskDesc").html(); //gets the taskDesc data of that table row
        console.log("desc"+desc);
        //let date = $(`.tasks#${src} > tbody > #${id} > #dueDate`).html(); doesnt work?
        let date = $(`#kanbanBoard .tasks#${src} > tbody > tr#${id}`).children("#dueDate").html(); //gets the due date data of that table row
        console.log("date"+date);
        let hours = $(`#kanbanBoard .tasks#${src} > tbody > tr#${id}`).children("#manHours").html();
        $.post("alterTaskStatus.php", {'taskId': id, "status": dest}, function(responseData){
            $(`#kanbanBoard .tasks#${src} > tbody > tr#${id}`).remove();
            let style = "background-color : transparent;"
            if(id==prevId){
                style = "background-color : blue;"
                prevStatus = dest;
            }
            $(`#kanbanBoard .tasks#${dest} > tbody`).append(`<tr id=${id} class = "${email}" style = "${style}" draggable = "true" ondragstart="dragStart2(event)" ondragend = "dragEnd2(event)" onclick="event.preventDefault();trClicked2(this.className,'${dest}',${id})">
                <td id='taskDesc'>${desc}</td>
                <td id='dueDate'>${date}</td>
                <td id='manHours'>${hours}</td>
            </tr>`);
        });
        if(dest=="complete"){
            console.log("testing123123");
            removeFromTeam2(id,email);
            tlrPromise.then(result => {
            if(result != "<?php echo $email?>"){
                console.log("not a team leader");
                $.get("checkCompletion.php", {'teamNo' : $('select#teams').find(":selected").val()},function(responseData){
                    console.log("RESS"+responseData);
                    if(responseData==0){
                        removeTeam($('select#teams').find(":selected").val());
                    }
                })
            }
        })
            
        }
        /*
        console.log("called"+id);
        console.log(i);
        $.post("completedTask.php", {'taskId': id}, function(responseData){
            console.log(responseData);
            $(`#toDoList > tbody > #${i}`).remove();
        });
        */
    }

    function removeFromTeam2(id,email){
        let teamNo = $('select#teams').find(":selected").val();
        $.post("removeFromTeam.php", {'email' : email, 'teamNo' : teamNo}, function(responseData){
            if(responseData==teamNo){
                /*
                $(`#kanbanBoard .tasks#complete > tbody > tr`).each(function(){
                    if(this.className==email){
                        this.remove();
                    }
				})
                */
            }
        })
    }

    function removeTeam(teamNo){
            $("select#teams").val("");
            console.log($(`select#teams`).children());
            $(`option#${teamNo}.dropDownMenu`).remove();
            $("select#teams").trigger("change");
            console.log(`select#teams #${teamNo}`);
        }

    

    $(document).ready(function(){

        $.get("getTeams.php", function(responseData){
            console.log(responseData);
            let len = responseData.length;
            for(let i=0; i<len; i++){
                $("select#teams").append(`<option id = ${responseData[i].teamNo} value = ${responseData[i].teamNo} class="dropDownMenu">${responseData[i].teamNo}</option>`);
            }
        },"json");

        //#kanbanBoard .tasks tr:not(:first-child)
        console.log("page is loaded lol");

        $("#kanbanBoard .forms").hide();
        console.log("LOADED");

        

        $("select#teams").change(function(){
        let teamNo = $('select#teams').find(":selected").val();
        console.log("CHANGED");
        console.log("team:"+teamNo);
        $("#kanbanBoard .tasks td").parent().remove(); //removes every table row except the header
        tlrPromise = prepareTeamLeader(teamNo); //gets the promise for whether the team leader matches the user logged in
        tlrPromise.then(result => {
            if(result=="<?php echo $email?>"){
                $.get("getEmployees.php", function(responseData){
                    console.log("donee");
                    let len = responseData.length;
                    for(let i = 0; i<len; i++){
                        console.log(responseData[i].email);
                        $("select#teamMembers").append(`<option value = ${responseData[i].email}>${responseData[i].email}</option>`);
                    }
                } ,"json");
                $("#kanbanBoard .forms").show();
                $.get("retriveTeamTasks.php", {'teamNo' : teamNo}, function(responseData){
                    console.log("TEAMTASKS");
                    console.log(responseData);
                    let len = responseData.length;
                    for(let i=0; i<len; i++){
                        console.log("run");
                        let desc = responseData[i].taskDesc;
                        let date = responseData[i].dueDate;
                        let id = responseData[i].taskId;
                        let hours = responseData[i].manHours;
                        let status = responseData[i].taskStatus;
                        let email = responseData[i].email;
                        $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart2(event)" ondragend = "dragEnd2(event)" onclick="event.preventDefault();trClicked2(this.className,'${status}',${id})">
                        <td id='taskDesc'>${desc}</td>
                        <td id='dueDate'>${date}</td>
                        <td id='manHours'>${hours}</td>
                        </tr>`); 
                    }
                }, "json");
            }else{
                $("#kanbanBoard .forms").hide();
                $.get("retriveTeamTasks.php", {'teamNo' : teamNo}, function(responseData){
                    console.log("TEAM TASKS2");
                    console.log(responseData);
                    let len = responseData.length;
                    for(let i=0; i<len; i++){
                        let desc = responseData[i].taskDesc;
                        let date = responseData[i].dueDate;
                        let id = responseData[i].taskId;
                        let hours = responseData[i].manHours;
                        let status = responseData[i].taskStatus;
                        let email = responseData[i].email;
                        if(email=="<?php echo $email?>"){ //if email matches, then the user can drag that element around
                            $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart2(event)" ondragend = "dragEnd2(event)" onclick="event.preventDefault();trClicked2(this.className,'${status}',${id})">
                            <td id='taskDesc'>${desc}</td>
                            <td id='dueDate'>${date}</td>
                            <td id='manHours'>${hours}</td>
                        </tr>`);
                        }else{ //otherwise they cant
                            console.log("email not match");
                            $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email} onclick="event.preventDefault();trClicked2(this.className,'${status}',${id})">
                            <td id='taskDesc'>${desc}</td>
                            <td id='dueDate'>${date}</td>
                            <td id='manHours'>${hours}</td>
                        </tr>`);
                        }
                    }
                }, "json");
            }
        });
        
        /*
        $.get("getTeamLeader.php", {'teamNo' : teamNo}, function(responseData){}).then((result) => {
            if(result==""){ //if they are a team leader
                $.get("getTeamMembers.php", {'teamNo' : teamNo}, function(responseData){
                    let len = responseData.length;
                    for(let i = 0; i<len; i++){
                        console.log(responseData[i].email);
                        $("select#teamMembers").append(`<option value = ${responseData[i].email}>${responseData[i].email}</option>`);
                    }
                } ,"json");
                $("#kanbanBoard .forms").show();
                $.get("retriveTeamTasks.php", {'teamNo' : teamNo}, function(responseData){
                    console.log("TEAMTASKS");
                    console.log(responseData);
                    let len = responseData.length;
                    for(let i=0; i<len; i++){
                        console.log("run");
                        let desc = responseData[i].taskDesc;
                        let date = responseData[i].dueDate;
                        let id = responseData[i].taskId;
                        let status = responseData[i].taskStatus;
                        let email = responseData[i].email;
                        $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart2(event)" ondragend = "dragEnd2(event)" onclick="event.preventDefault();trClicked(this.className)">
                        <td id='taskDesc'>${desc}</td>
                        <td id='dueDate'>${date}</td>
                        </tr>`); 
                    }
                }, "json");
            }else{
                $("#kanbanBoard .forms").hide();
                $.get("retriveTeamTasks.php", {'teamNo' : teamNo}, function(responseData){
                    console.log("TEAM TASKS2");
                    console.log(responseData);
                    let len = responseData.length;
                    for(let i=0; i<len; i++){
                        let desc = responseData[i].taskDesc;
                        let date = responseData[i].dueDate;
                        let id = responseData[i].taskId;
                        let status = responseData[i].taskStatus;
                        let email = responseData[i].email;
                        if(email==""){ //if email matches, then the user can drag that element around
                            $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart2(event)" ondragend = "dragEnd2(event)" onclick="event.preventDefault();trClicked(this.className)">
                            <td id='taskDesc'>${desc}</td>
                            <td id='dueDate'>${date}</td>
                        </tr>`);
                        }else{ //otherwise they cant
                            console.log("email not match");
                            $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email} onclick="event.preventDefault();trClicked(this.className)">
                            <td id='taskDesc'>${desc}</td>
                            <td id='dueDate'>${date}</td>
                        </tr>`);
                        }
                    }
                }, "json");
            }
        });
        */
        /*
        $.get("retriveTeamTasks.php", {'teamNo' : teamNo}, function(responseData){
            console.log("res"+responseData);
            let len = responseData.length;
            for(let i=0; i<len; i++){
                let desc = responseData[i].taskDesc;
                let date = responseData[i].dueDate;
                let id = responseData[i].taskId;
                let status = responseData[i].taskStatus;
                let email = responseData[i].email;
                if(email==""){ //if email matches, then the user can drag that element around
                    $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart2(event)" ondragend = "dragEnd2(event)">
                    <td id='taskDesc'>${desc}</td>
                    <td id='dueDate'>${date}</td>
                </tr>`);
                }else{ //otherwise they cant
                    $(`#kanbanBoard .tasks#${status}`).append(`<tr id=${id} class = ${email}>
                    <td id='taskDesc'>${desc}</td>
                    <td id='dueDate'>${date}</td>
                </tr>`);
                }
            }
            }, "json");
            */
        });

        $(`#kanbanBoard #editTaskForm`).submit(function(event){
                event.preventDefault();
                let taskDesc = $(`#kanbanBoard #editTaskForm > #taskDesc`).val();
                let dueDate = $(`#kanbanBoard #editTaskForm > #dueDate`).val();
                let manHours = $(`#kanbanBoard #editTaskForm > #manHours`).val();
                if(taskDesc!='' || dueDate != '' || manHours !=''){
                    editTask2(prevStatus,taskDesc,dueDate,manHours,prevId);
                }
            });

        $("#kanbanBoard #addTaskForm").submit(function(event){
            event.preventDefault();
            let desc = $(`#kanbanBoard .forms > #taskDesc`).val(); 
            let date = $(`#kanbanBoard .forms > #dueDate`).val();
            let hours = $(`#kanbanBoard .forms > #manHours`).val();
            let email = $('select#teamMembers').find(":selected").val();
            $.post("addTeamTask.php", {'taskDesc' : desc, 'dueDate': date, 'email' : email, 'teamNo': $('select#teams').find(":selected").val(), 'manHours' : hours}, function(responseData){  
                let id = parseInt(responseData);
                console.log("res"+responseData);
                $(`#kanbanBoard .tasks#backlog`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart2(event)" ondragend = "dragEnd2(event)" onclick="event.preventDefault();trClicked2(this.className,'backlog',${id})">
                <td id='taskDesc'>${desc}</td>
                <td id='dueDate'>${date}</td>
                <td id='manHours'>${hours}</td>
                </tr>`);
                /*
                $.post("addToTeam.php", {'teamNo' : $('select#teams').find(":selected").val(), 'email' : email}, function(responseData){
                    console.log(responseData);
                });
                */
            
            });
            //.forms#${status} > #moveTask > button
        });
    });

    function editTask2(status,taskDesc,dueDate,manHours,id){
        console.log("manhrs "+manHours);
        $.post("editTask.php", {'taskId': id, 'taskDesc': taskDesc, 'dueDate': dueDate, 'manHours' : manHours}, function(responseData){
            console.log("res"+responseData);
            if(taskDesc!=''){
                $(`#kanbanBoard .tasks#${status} > tbody > tr#${id}`).children("#taskDesc").html(taskDesc);
            }

            if(dueDate!=''){
                $(`#kanbanBoard .tasks#${status} > tbody > tr#${id}`).children("#dueDate").html(dueDate);
            }
            
            if(manHours!=''){
                $(`#kanbanBoard .tasks#${status} > tbody > tr#${id}`).children("#manHours").html(manHours);
            }
            
        });
        }
    



</script>

<div id = "yourTeamsSection">
    <div style='overflow-x:auto;'>
    </div>
</div>

<div id = "pageContainer">
    <div id = "container" >
        <div class = "taskForms">
            <form class = "forms" id="addTaskForm">
                <h4>Add Task:</h4>
                <label for="taskDesc">Task Description:</label>
                <input type="text" id="taskDesc" name="taskDesc" class="inputForm" required><br>
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" class="inputForm" required><br>
                <label for="manHours">Man Hours</label>
                <input type="number" min="0" id="manHours" name="manHours" class="inputForm" required><br>
                <label for="teamMembers">Email:</label>
                <select id ="teamMembers">

                </select><br>
                <input type="submit" class="submitBtns">
                
            </form>
            <form class = "forms" id="editTaskForm">
                <h4>Edit Task:</h4>
                <label for="taskDesc">Task Description:</label>
                <input type="text" id="taskDesc" name="taskDesc" class="inputForm"><br>
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" class="inputForm"><br>
                <label for="manHours">Man Hours</label>
                <input type="number" min="0" id="manHours" name="manHours" class="inputForm"><br>
                <button type="submit">Edit</button><br>
                
            </form>
        </div>
        <div id = "zones" ondrop = "drop2(event)" ondragover = "allowDrop(event)">
            <div class = "zone" id = "backlog">
            <h1 id = "backlog">Backlog</h1>
                <table class = "tasks" id ="backlog">
                    <tr>
                        <th>Task Description</th>
                        <th>Date Due</th>
                        <th>Man Hours</th>
                    </tr>
                </table>
            </div>
            <div class = "zone" id = "pending">
                <h1 id = "pending">Pending</h1>
                <table class = "tasks" id ="pending">
                    
                    <tr>
                        <th>Task Description</th>
                        <th>Date Due</th>
                        <th>Man Hours</th>
                    </tr>
                </table>
            </div>
            <div class = "zone" id = "progress">
            <h1 id = "progress">In Progress</h1>
                <table class = "tasks" id ="progress">
                
                    <tr>
                        <th>Task Description</th>
                        <th>Date Due</th>
                        <th>Man Hours</th>
                    </tr>
                </table>
            </div>
            <div class = "zone" id = "complete">
            <h1 id = "complete">Complete</h1>
                <table class = "tasks" id ="complete">
                
                    <tr>
                        <th>Task Description</th>
                        <th>Date Due</th>
                        <th>Man Hours</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>


