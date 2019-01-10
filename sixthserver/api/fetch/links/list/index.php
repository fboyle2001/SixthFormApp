<?php
  define("AllowIncludes", 1);
  include("../../../api_util.php");

	$reply = new Reply();

  // Need to be at least a student
  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

  // Only display links if they haven't expired in case they are yet to be
  // deleted
  $time = time();

	$selectLatest = Database::get()->prepare("SELECT * FROM `links` WHERE `ExpiryDate` >= :expire");
  $selectLatest->execute(["expire" => $time]);

  // Send all the links found back
	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->rowCount() != 0);
	$reply->setValue("records", $selectLatest->fetchAll(PDO::FETCH_ASSOC));

	echo $reply->toJson();

?>
