<?php
  include("../../../api_util.php");

	$reply = new Reply();

  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

  $time = time();

	$selectLatest = Database::get()->prepare("SELECT * FROM `links` WHERE `ExpiryDate` >= :expire");
  $selectLatest->execute(["expire" => $time]);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->rowCount() != 0);
	$reply->setValue("records", $selectLatest->fetchAll(PDO::FETCH_ASSOC));

	echo $reply->toJson();

?>
