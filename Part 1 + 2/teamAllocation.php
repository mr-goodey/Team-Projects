<!DOCTYPE html>
<html>
	<body>
	<div>
	<form id="addToTeam" action="addToTeam.php" style="text-align: center;" method="post">
		<label for="email2">Email:</label>
		<input type="text" id="email2" name="email2"><br><br>
		<label for="team2">Team:</label>
		<input type="number" id="team2" name="team2" required><br><br>
		<input type="submit" value="Add To Team"><br><br>
		<div id="Response2"></div><br><br>
	</form>
	<form id="removeFromTeam" action="removeFromTeam.php" style="text-align: center;" method="post">
		<label for="email3">Email:</label>
		<input type="text" id="email3" name="email3"><br><br>
		<label for="team3">Team:</label>
		<input type="number" id="team3" name="team3" required><br><br>
		<input type="submit" value="Remove From Team"><br><br>
		<div id="Response3"></div>
	</form>
	<script>
		var frm2 = $('#addToTeam');
		frm2.submit(function (e) {
			e.preventDefault();
			$.ajax({
				type: frm2.attr('method'),
				url: frm2.attr('action'),
				data: frm2.serialize(),
				success: function (data2) {
					if (data2.status == 'success') {
						$("#Response2").html("Added to team");
					} else if (data2.status == 'error1') {
						$("#Response2").html("Invalid email");
					} else if (data2.status == 'error2') {
						$("#Response2").html("User not invited");
					} else if (data2.status == 'error3') {
						$("#Response2").html("User already on team");
					} else if (data2.status == 'error4') {
						$("#Response2").html("Team leader");
					}
				},
				error: function (data2) {
					$("#Response2").html("An error occurred");
				},
			});
		});
		
		var frm3 = $('#removeFromTeam');
		frm3.submit(function (e) {
			e.preventDefault();
			$.ajax({
				type: frm3.attr('method'),
				url: frm3.attr('action'),
				data: frm3.serialize(),
				success: function (data3) {
					if (data3.status == 'suc') {
						$("#Response3").html("Removed from team");
					} else if (data3.status == 'err1') {
						$("#Response3").html("Invalid email");
					} else if (data3.status == 'err2') {
						$("#Response3").html("User not invited");
					} else if (data3.status == 'err3') {
						$("#Response3").html("User not on team");
					} else if (data3.status == 'err4') {
						$("#Response3").html("Team leader");
					}
				},
				error: function (data3) {
					$("#Response3").html("An error occurred");
				},
			});
		});
	</script>
	</div>
	</body>
</html>