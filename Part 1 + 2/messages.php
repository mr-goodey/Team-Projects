<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<style>
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

		#postContainer{
			position:relative;
			display: inline-block;
			background-color: #2d2d2d;
			margin: 5px;
			border-radius: 5px;
			padding: 10px;
		}
	</style>
	</head>
	<body>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script>

			$.get("retriveMessages.php", function(responseData){
				console.log("HELLO");
				console.log(responseData);
				appendPosts(responseData, responseData.length);
			}, "json");

			function appendPosts(responseData, len){
				for(let i=0; i<len; i++){
					let postId = responseData[i].postId;
					let email = responseData[i].email;
					let topic = responseData[i].topic;
					let post = responseData[i].post;
					$("table#posts").append(
					`<tr class = "${email}" id=${postId}>
						<td>${email}</td>
						<td>${topic}</td>
						<td>${post}</td>
						<td><button class = "deleteButtons" onclick = 'delPosts("${email}",${postId})'>Delete Post</button></td>
					</tr>`
);
				}
			}

			$.get("getTopics.php", function(responseData){
				$.each(responseData,function(i){
					$(`select#filterMenu`).append(`<option value=${responseData[i].topic}>${responseData[i].topic}</option>`);
				});
			},"json");
			
			function filter(){
				let topic = $("select#filterMenu").find(":selected").text();
				$.get("filterMessages.php", {'topic' : topic}, function(responseData){
					console.log("filtering")
					$('table#posts tr:not(:first)').remove(); //removes every table row except for header
					appendPosts(responseData, responseData.length);
				}, "json");
			}

			function delPosts(email, postId){
				if(email == "<?php echo $email?>"){
					if (confirm("Are you sure you want to delete this post?") == true){
						$.post("removeMessage.php",{'postId' : postId}, function(responseData){
							if(responseData==postId){
								$(`#posts tr#${postId}`).remove();
							}
						})
				}
				}
			}

			$(document).ready(function(){
				console.log("AAA");
				$("#createPost").submit(function(event){
					event.preventDefault();
					let topic = $("#postTopic").val();
					let post = $("#postContent").val();
					$.post("createMessage.php", {'topic' : topic, 'post': post}, function(responseData){
						let postId = responseData
						let dropDownTopic = $("select#filterMenu").find(":selected").val();
						if(dropDownTopic == topic || dropDownTopic == ""){
							$("table#posts").append(
							`<tr class = "<?php echo $email ?>" id = ${postId}>
							<td><?php echo $email ?></td>
							<td>${topic}</td>
							<td>${post}</td>
							<td><button class = "deleteButtons" onclick = 'delPosts("<?php echo $email ?>",${postId})'>Delete Post</button></td>
							</tr>`
						);
						}
						$(`select#filterMenu`).append(`<option value=${topic}>${topic}</option>`);
					});
				});
			});
			
		</script>
		<div id = "postContainer">
			<form id="createPost">
				<label for="postTopic">Topic:</label>
				<input type="text" id="postTopic" name="postTopic" required><br>
				<label for="postContent">Post:</label>
				<input type="text" id="postContent" name="postContent" required><br>
				<input type="submit" class = "submitBtns" name="post">
			</form>		
			<form id="filter">
				<label for="topicFilter">Filter:</label>
				<select name="topicFilter" onchange="filter()" id="filterMenu">
					<option value=""></option>
				</select>
			</form>
		</div>
		<table id = "posts" class='table table-dark table-striped'>
			<tr>
				<th>Email</th>
				<th>Topic</th>
				<th>Post</th>
				<th></th>
			</tr>
		</table>
	</body>
