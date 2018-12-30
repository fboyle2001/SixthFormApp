<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

  $username = post("username");
  $password = post("password");

  $reply = new Reply();

  if($username == null || $password == null) {
		$reply->setStatus(ReplyStatus::withData(400, "No username or password"));
    die($reply->toJson());
  }

  $accountQuery = Database::get()->prepare("SELECT `Password` FROM `accounts` WHERE `Username` = :username");
  $accountQuery->execute(["username" => $username]);

  if($accountQuery->rowCount() == 0) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid username"));
    die($reply->toJson());
  }

  $passwordsMatch = password_verify($password, $accountQuery->fetch()["Password"]);

  if($passwordsMatch == false) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid password"));
    die($reply->toJson());
  }

  $secret = random_str(32);

  $deleteApiKeys = Database::get()->prepare("DELETE FROM `apikeys` WHERE `Username` = :username");
  $deleteApiKeys->execute(["username" => $username]);

  $authQuery = Database::get()->prepare("SELECT `IsAdmin`, `Reset` FROM `accounts` WHERE `Username` = :username");
  $authQuery->execute(["username" => $username]);

  $authResult = $authQuery->fetch(PDO::FETCH_ASSOC);
  $secret .= "." . $authResult["IsAdmin"];

  $expireTime = time() + 3600; #hour expire time
  $createApiKey = Database::get()->prepare("INSERT INTO `apikeys` (`Username`, `Secret`, `ExpireTime`) VALUES (:username, :secret, :expire)");
  $createApiKey->execute(["username" => $username, "secret" => $secret, "expire" => $expireTime]);

  $json = '{"username":"' . $username . '","secret":"' . $secret . '"}';
	$json = base64_encode($json);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));

  $hasReset = $authResult["Reset"] == 1 ? true : false;

  $reply->setValue("auth", $json);
  $reply->setValue("reset", $hasReset);
	echo $reply->toJson();

  //use reply
?>
