<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

  $username = post("username");
  $password = post("password");

  $reply = new Reply();

  // No username or password is a problem
  if($username == null || $password == null) {
		$reply->setStatus(ReplyStatus::withData(400, "No username or password"));
    die($reply->toJson());
  }

  // Get their hashed password
  $accountQuery = Database::get()->prepare("SELECT `Password` FROM `accounts` WHERE `Username` = :username");
  $accountQuery->execute(["username" => $username]);

  // Username must have been wrong
  if($accountQuery->rowCount() == 0) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid username"));
    die($reply->toJson());
  }

  // Verify submitted password with hash
  $passwordsMatch = password_verify($password, $accountQuery->fetch()["Password"]);

  if($passwordsMatch == false) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid password"));
    die($reply->toJson());
  }

  // Generate 32-char secret
  // Exists 62^32 combinations (2.27 * 10^57 secrets)
  $secret = random_str(32);

  // Remove their old secrets
  $deleteApiKeys = Database::get()->prepare("DELETE FROM `apikeys` WHERE `Username` = :username");
  $deleteApiKeys->execute(["username" => $username]);

  // Check if they are admin so it can be appended to their secret
  $authQuery = Database::get()->prepare("SELECT `IsAdmin`, `Reset` FROM `accounts` WHERE `Username` = :username");
  $authQuery->execute(["username" => $username]);

  $authResult = $authQuery->fetch(PDO::FETCH_ASSOC);
  $secret .= "." . $authResult["IsAdmin"];

  // Expire in 1 hour. Put the key in the database.
  $expireTime = time() + 3600;
  $createApiKey = Database::get()->prepare("INSERT INTO `apikeys` (`Username`, `Secret`, `ExpireTime`) VALUES (:username, :secret, :expire)");
  $createApiKey->execute(["username" => $username, "secret" => $secret, "expire" => $expireTime]);

  // JSON is encoded to base 64 for transmission between client-server when
  // requesting content as it is easier to handle and uses less bytes
  // May be breakable? If their username is weird e.g. "User"
  $json = '{"username":"' . $username . '","secret":"' . $secret . '"}';
	$json = base64_encode($json);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));

  // Check if the user has been forced to change their password
  $hasReset = $authResult["Reset"] == 1 ? true : false;

  // Reply with the standard reply
  $reply->setValue("auth", $json);
  $reply->setValue("reset", $hasReset);
	echo $reply->toJson();
?>
