<!DOCTYPE HTML>
<html>
	<head>
		<script src="jquery-3.2.1.min.js"></script>
		<script>
		
		function login(username, password, handle) {
			var auth = "";
			
			$.ajax({
				type: "POST",
				url: "http://localhost/sixthserver/api/accounts/login/",
				data: "username=" + username + "&password=" + password,
				processData: false,
				success: function(msg) {
					var obj = JSON.parse(msg);
					auth = obj["content"]["auth"];
					$("#username").append(username);
					$("#password").append(password);
					$("#auth").append(auth);
					handle(auth);
				},
				error: function(msg) {
					$("#last").append("bad");
				}
			});
			
			$("#last").append(auth);
			return auth;
		}
		
		function query(url, data, auth) {
			$("#last").append(auth);
			$.ajax({
				type: "POST",
				beforeSend: function(xhr) {
					xhr.setRequestHeader('Authorization', auth);
				},
				url: url,
				processData: false,
				success: function(msg) {
					$("#last").append(msg);
				}
			});
		}
		
		$(document).ready(function () {
			var auth = login("DevAdmin", "test", function(out) {
				return out;
			});
			
			$("#last").append(auth);
			query("http://localhost/sixthserver/api/accounts/details/", "", auth);
		});
		</script>
	</head>
	<body>
		<p id="last"></p>
		<div>
			<h3>Login Data</h3>
			<p>Username: <span id="username"></span></p>
			<p>Password: <span id="password"></span></p>
			<p>Auth Key: <span id="auth"></span></p>
		</div>
	</body>
</html>