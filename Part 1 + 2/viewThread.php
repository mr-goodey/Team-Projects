<?php
	session_start();
	$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="stylesheet.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Thread</title>
</head>
<style>
    #container{
        padding-left: 15px;
    }

    hr{
        background-color: white;
    }

    .profilepic{
        width: 40px;
        height: 40px;
    }

    #responses{
		text-align:left;
	}

	.profilepics, .responses{
		display: inline-block;
	}

    .profilepics{
        vertical-align: top;
    }

    .response{
        width: 300px;
    }


</style>
<body>
    <h1 style="text-align: center;">Make-It-All</h1>
    <hr>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        let url = window.location.href
        let postId = parseInt(url.split('?')[1]);
        console.log(postId);
        
        $.get("getPost.php", {'postId' : postId}, function(responseData){
                    let topic = responseData[0].topic;
                    let message = responseData[0].message;
                    let email = responseData[0].email;

                    $("#post").html(
                        `<h3>${topic}</h3>
                        <p>${message}</p>`
                    );
                
            }, "json");

        $.get("getThread.php", {'postId' : postId}, function(responseData){
            let len = responseData.length;
            console.log(responseData);
            for(let i=0; i<len; i++){
                let threadId = responseData[i].threadId;
                let message = responseData[i].message;
                let email = responseData[i].email;

                $("#responses").append(
                    `<div id = "pic${threadId}" class="profilepics">
                    </div>`
                );

                $("#responses").append(
                    `<div id = "thread${threadId}" class="responses">
                    </div>`
                );

                $(`#responses > #pic${threadId}`).append(
                    `<img class = "profilepic" src = "profilepic.png">`
                );

                $(`#responses > #thread${threadId}`).append(
                    `<p class = "response"><b>From: ${email}</b><br>
                    ${message}<p>`
                );

                $(`#responses`).append(
                    `<br>`
                );

            }
        }, "json");
        
        $(document).ready(function(){
            $("#createResponse").submit(function(event){
                event.preventDefault();
                let message = $("#createResponse > textarea").val();
                $.post("createResponse.php", {'postId' : postId, 'message' : message}, function(responseData){
                    let threadId = parseInt(responseData);
                    console.log("t"+threadId);
                    $("#responses").append(
                        `<div id = "pic${threadId}" class="profilepics">
                        </div>`
                    );

                    $("#responses").append(
                        `<div id = "thread${threadId}" class = "responses">
                        </div>`
                    );

                    $(`#responses > #pic${threadId}`).append(
                        `<img class = "profilepic" src = "profilepic.png">`
                    );


                    $(`#responses > #thread${threadId}`).append(
                        `<p class = "response"><b>From: <?php echo $email?></b><br>
                        ${message}<p>`
                    );

                    $(`#responses`).append(
                        `<br>`
                    );

                })
            })
        });
    </script>
    <div id = "container">
        <div id = "post">
            <h4 id = "topic"></h4>
            <p id = "message"></p>
        </div>
        <form id="createResponse">
            <label for="message">Response Message:</label>
            <textarea id="message" name="message" rows="4" cols="50" required></textarea>
            <br>
            <button type = "submit">Submit</button>
        </form>
        <br>
        <div id = "responses">

        </div>
    </div>
</body>
</html>