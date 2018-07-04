<!DOCTYPE HTML>
<html>
	<head>
		<script src="jquery-3.2.1.min.js"></script>
		<script src="login.js"></script>
		<script>
			performLogin("DevAdmin", "test", "http://localhost/sixthserver/api", function() {
			  $("#auth").append(window.user.auth);
			});

			$(document).ready(function () {
				$("#details").click(function () {
					if(window.user === null) {
						$("#result").text("Must wait for user.");
						return;
					}

					window.user.query("/accounts/details/", {}, function (data) {
						$("#result").text(JSON.stringify(data));
					}, function(data) {
						console.log(data);
					});
				});

				$("#notices").click(function () {
					if(window.user === null) {
						$("#result").text("Must wait for user.");
						return;
					}

					window.user.query("/fetch/files/notices/list/", {}, function (data) {
						$("#result").text(JSON.stringify(data));
					}, function(data) {
						console.log(data);
					});
				});

				$("#limit").click(function () {
					if(window.user === null) {
						$("#result").text("Must wait for user.");
						return;
					}

					window.user.query("/fetch/announcements/list/", {limit: 1}, function (data) {
						$("#result").text(JSON.stringify(data));
					}, function(data) {
						console.log(data);
					});
				});
			});
		</script>
	</head>
	<body>
		<p id="result">Results will appear here...</p>
		<div>
			<h3>Login Data</h3>
			<p>Auth Key: <span id="auth"></span></p>
			<button id="details">Fetch Details</button>
			<button id="notices">Fetch Notice List</button>
			<button id="limit">Fetch Single Limit Announcements</button>
		</div>
	</body>
</html>
