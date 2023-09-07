
<script>
	//this file is for employess and team leaders viewing their own teams
	function viewEmployeeTasks(){
		console.log("test5");
		$.get("viewTeam.php", function(responseData){
			console.log("response"+responseData);
			len = responseData.length;
			console.log(len);
			for(let i=0; i<len; i++){
				let email = responseData[i].email;
				let team = responseData[i].teamNo;
				$('#teams').append(`<tr>
					<td>${email}</td>
					<td>${team}</td>
				</tr>`);
			}
		}, "json");
	}


	$(document).ready(function() {
		viewEmployeeTasks();
	});
	console.log("Script has run hello");

</script>


<div style='overflow-x:auto;'>
<table id ='teams' class='table table-dark table-striped'>
		<tr>
			<th>Team Member Email</th>
			<th>Team Number</th>
		</tr>
	</table>
</div>
