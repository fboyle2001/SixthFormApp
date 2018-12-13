<?php
  include("../../api_util.php");

  $username = post("username");
  $password = post("password");

  $reply = new Reply();

  if($username == null || $password == null) {
		$reply->setStatus(ReplyStatus::withData(400, "No username or password"));
    die($reply->toJson());
  }

  $accountQuery = "SELECT `Password` FROM `accounts` WHERE `Username` = '$username'";
  $accountResult = DatabaseHandler::getInstance()->executeQuery($accountQuery);

  if($accountResult->wasDataReturned() == false) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid username"));
    die($reply->toJson());
  }

  $passwordsMatch = password_verify($password, $accountResult->getRecords()[0]["Password"]);

  if($passwordsMatch == false) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid password"));
    die($reply->toJson());
  }

  $secret = random_str(32);

  $deleteApiKeys = "DELETE FROM `apikeys` WHERE `Username` = '$username'";
  DatabaseHandler::getInstance()->executeQuery($deleteApiKeys);

  $authQuery = "SELECT `IsAdmin`, `Reset` FROM `accounts` WHERE `Username` = '$username'";
  $authResult = DatabaseHandler::getInstance()->executeQuery($authQuery);

  $authResult = $authResult->getRecords()[0];
  $secret .= "." . $authResult["IsAdmin"];

  $expireTime = time() + 3600; #hour expire time
  $createApiKey = "INSERT INTO `apikeys` (`Username`, `Secret`, `ExpireTime`) VALUES ('$username', '$secret', '$expireTime')";
  DatabaseHandler::getInstance()->executeQuery($createApiKey);

  $json = '{"username":"' . $username . '","secret":"' . $secret . '"}';
	$json = base64_encode($json);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));

  $hasReset = $authResult["Reset"] == 1 ? true : false;

  $reply->setValue("auth", $json);
  $reply->setValue("reset", $hasReset);
	echo $reply->toJson();

  //use reply
?>
