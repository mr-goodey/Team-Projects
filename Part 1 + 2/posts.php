<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<style>
		
		
		
	</style>
	</head>
	<body>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script>

			$.get("retrivePosts.php", function(responseData){
				console.log("HELLO");
				console.log(responseData);
				appendPosts(responseData, responseData.length);
			}, "json");

			function appendPosts(responseData, len){
				for(let i=0; i<len; i++){
					let email = responseData[i].email;
					let topic = responseData[i].topic;
					let postId = responseData[i].postId;
					$("table#posts").append(
						`<tr>
						<td>${email}</td>
						<td>${topic}</td>
						<td><button type="button" id=${postId} onclick = "viewThread(${postId})">View post</button></td>
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
				console.log(topic);
				$.get("filterPosts.php", {'topic' : topic}, function(responseData){
					$('table#posts tr:not(:first)').remove(); //removes every table row except for header
					appendPosts(responseData, responseData.length);
				}, "json");
			}

			$(document).ready(function(){
				console.log("AAA");
				$("#createPost").submit(function(event){
					event.preventDefault();
					let topic = $("#postTopic").val();
					let message = $("#postContent").val();
					$.post("createPost.php", {'topic' : topic, 'message': message}, function(responseData){
						console.log(responseData);
						let dropDownTopic = $("select#filterMenu").find(":selected").val();
						if(dropDownTopic == topic || dropDownTopic == ""){
							$("table#posts").append(
							`<tr>
							<td><?php echo $email ?></td>
							<td>${topic}</td>
							<td><button type="button" id=${responseData} onclick = "viewThread(${responseData})">View post</button></td>
							</tr>`
						);
						}
						$(`select#filterMenu`).append(`<option value=${topic}>${topic}</option>`);
					});
				});
			});

			function viewThread(postId){
				window.open(`viewThread.php?${postId}`);
			}
			
		</script>
		<div>
			<form id="createPost">
				<label for="postTopic">Topic:</label>
				<input type="text" id="postTopic" name="postTopic" required><br>
				<label for="postContent">Post:</label>
				<input type="text" id="postContent" name="postContent" required><br>
				<input type="submit" name="post">
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
			</tr>
		</table>
	</body>
