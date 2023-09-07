<?php
    $email = $_SESSION['email'];
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>To do list</title>
<style>

    #toDoList .tasks{
        padding-top: 15px;
    }

    #toDoList .forms > span{
        padding-top: 5px;
        padding-bottom: 5px;
        display:flex;
        justify-content: center; 
    }


    .tasks tr:not(:first-child):hover{
        cursor: pointer;
        background-color: #ccc;
    }

    #toDoList #container{
        width: 100%;

    }

    #toDoList #backlog{
        justify-conent: center;
    }

    #toDoList .zone{
        padding: 15px;
        flex-wrap: wrap;
    }

    #toDoList #zones{
        display: flex;
        flex-direction: row;
    }

    #toDoList #pageContainer {
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


    #toDoList #zones > .zone > .tasks > tbody > *, #toDoList #zones h1{
        position: relative;
        z-index: 100;
    }

    .forms{
        position:relative;
        display: inline-block;
        z-index: 100;
        background-color: #2d2d2d;
        margin: 5px;
        border-radius: 5px;
        padding: 10px;
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

        function dragStart1(event){
            event.dataTransfer.setData("text", event.target.id);
            $(`#toDoList #zones h1, #toDoList #zones > .zone > .tasks > tbody > *:not(tr[id="${event.target.id}`).css({
                "position": "relative",
                "z-index": "-1"
            });
        }



        function dragEnd1(event){
            $(`#toDoList #zones h1, #toDoList #zones > .zone > .tasks > tbody > *:not(tr[id="${event.target.id}`).css({
                "position": "relative",
                "z-index": "100"
            });
        }

        function ondragover(event){
            event.preventDefault();
        }

        function allowDrop(event) {
            event.preventDefault();
        }

        let statuses = ["backlog","pending","progress","complete"];

        function drop1(event, target){
            event.preventDefault();
            let id = event.dataTransfer.getData("text");
            console.log("ID"+id);
            let dest = event.target.id;
            let src = $(`#toDoList tr#${id}`).closest('table').attr('id');
            console.log("SRC"+src);
            console.log("DEST"+dest);
            
            if(statuses.includes(src) && statuses.includes(dest)){
                moveTask1(id,src,dest);
            }
        }
        
        $.get("retriveTasks.php", function(responseData){
            console.log("personal tasks");
            console.log(responseData);
            let len = responseData.length;
            console.log(len);
            for(let i=0; i<len; i++){
                let desc = responseData[i].taskDesc;
                let date = responseData[i].dueDate;
                let id = responseData[i].taskId;
                let status = responseData[i].taskStatus;
                let hours = responseData[i].manHours;
                console.log("status "+status);
                console.log("id"+id);
                $(`#toDoList .tasks#${status}`).append(`<tr id=${id} draggable="true" ondragstart="dragStart1(event)" ondragend = "dragEnd1(event)" onclick = "event.preventDefault;checkTr('${status}',${id})">
                    <td id='taskDesc'>${desc}</td>
                    <td id='dueDate'>${date}</td>
                    <td id='dueDate'>${hours}</td>
                </tr>`);
                /*
                $(`#button${i}`).click(function(){
                    taskComplete(id,i);
                });
                */
            }
            
            
        }, "json");

        function taskAdded(){
            let task = $("#task").val();
            $("#toDoList").html(`<li>${task}</li>`);
        }

        function checkTr(status,id){
            console.log(id);
            if(status=="complete"){
                if (confirm("Are you sure you want to delete this task?") == true){
                    $.post("removeTask.php",{'taskId' : id}, function(responseData){
                        console.log(responseData);
                        $(`#toDoList .tasks#${status} > tbody > tr#${id}`).remove();
                    })
            }
            }
        }

        //.tasks#backlog > tbody > #5 > #taskDesc
        function moveTask1(id,src,dest){
            //`.tasks#${src} > tbody > tr#${id} > td#taskDesc`
            let desc = $(`#toDoList .tasks#${src} > tbody > tr#${id}`).children("#taskDesc").html(); //gets the taskDesc data of that table row
            //let date = $(`.tasks#${src} > tbody > #${id} > #dueDate`).html(); doesnt work?
            let date = $(`#toDoList .tasks#${src} > tbody > tr#${id}`).children("#dueDate").html(); //gets the due date data of that table row
            $.post("alterTaskStatus.php", {'taskId': id, "status": dest}, function(responseData){
                $(`#toDoList .tasks#${src} > tbody > tr#${id}`).remove();
                $(`#toDoList .tasks#${dest} > tbody`).append(`<tr id=${id} draggable = "true" ondragstart="dragStart1(event)" ondragend = "dragEnd1(event)" onclick = "event.preventDefault;checkTr('${dest}',${id})">
                    <td id='taskDesc'>${desc}</td>
                    <td id='dueDate'>${date}</td>
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
        }

        function editTask(status,id,text){
            $.post("editTaskDesc.php", {'taskId': id, 'newDesc': text}, function(responseData){
                $(`#toDoList .tasks#${status} > tbody > tr#${id}`).children("#taskDesc").html(text);
            });
        }

        function isDateBeforeToday(date) {
            return new Date(date.toDateString()) < new Date(new Date().toDateString());
        }

        $(document).ready(function(){

            /*
            $("tr").click(function(event){
                event.preventDefault();
                console.log("clicked!");
            })
            */
            
            $("#toDoList .forms").submit(function(event){
            event.preventDefault();
            let desc = $(`#toDoList .forms > #taskDesc`).val(); 
            let date = $(`#toDoList .forms > #dueDate`).val();
            let hours = $(`#toDoList .forms > #manHours`).val();
            let email = $('select#teamMembers').find(":selected").val();
            $.post("addTask.php", {'taskDesc' : desc, 'dueDate': date, 'email' : email, 'manHours' : hours}, function(responseData){  
                let id = parseInt(responseData);
                console.log("res"+responseData);
                $(`#toDoList .tasks#backlog`).append(`<tr id=${id} class = ${email} draggable="true" ondragstart="dragStart1(event)" ondragend = "dragEnd1(event)" onclick = "event.preventDefault;checkTr('backlog',${id});">
                <td id='taskDesc'>${desc}</td>
                <td id='dueDate'>${date}</td>
                <td id='manHours'>${hours}</td>
                </tr>`);
            });
            //.forms#${status} > #moveTask > button
        });

            /*
            $(`.buttons`).click(function(){
                let status = $(document.activeElement).closest('.forms').attr('id'); //gets the parent of the button, with class "forms" and gets the id of it, which is the status of that element
                let dest = $(document.activeElement).attr("name");
                console.log("current is "+status);
                console.log("clicked"+dest);
                let id = $(`#toDoList .tasks#${status} > tbody > tr > td > input[type='radio'][name='select']:checked`).val();
                console.log("id"+id);
                moveTask1(id,status,dest);
            });
            */


            $(`.edit`).click(function(){
                let status = $(document.activeElement).closest('#toDoList .forms').attr('id');
                let text = $(`#toDoList .forms#${status} > #taskDesc`).val();
                if(text==""){
                    alert("Task Description cannot be empty");
                    return;
                }
                let id = $(`#toDoList .tasks#${status} > tbody > tr > td > input[type='radio'][name='select']:checked`).val();
                editTask(status,id,text);
            });
        });



    </script>
    </script>
    <div id = "container" >
        <div class = "board" id="backlog" ondrop = "drop1(event)" ondragover = "allowDrop(event)">
            <form class = "forms" id="taskForm">
                <h4>Add Task:</h4>
                <label for="taskDesc">Task Description:</label>
                <input type="text" id="taskDesc" name="taskDesc" class="inputForm" required><br>
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" class="inputForm" required><br>
                <label for="manHours">Man Hours</label>
                <input type="number" min="0" id="manHours" name="manHours" class="inputForm" required><br>
                <input type="submit" name="complete" class="submitBtns">
            </form>
        </div>
        <div id = "zones" ondrop = "drop1(event)" ondragover = "allowDrop(event)">
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