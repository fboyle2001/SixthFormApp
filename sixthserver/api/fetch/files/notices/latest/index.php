<?php
  define("AllowIncludes", 1);
  include("../../../../api_util.php");

	$reply = new Reply();

  // Need to be at least a student
  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

  // Only find the most recent, non expired one
  $time = time();

  $selectLatest = Database::get()->prepare("SELECT * FROM `files` WHERE `Type` = :type AND `ExpiryDate` >= :expire ORDER BY `AddedDate` DESC LIMIT 0, 1");
  $selectLatest->execute(["type" => StoreType::notices, "expire" => $time]);

  // Send the single one back
	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->rowCount() != 0);
	$reply->setValue("latest", $selectLatest->fetch(PDO::FETCH_ASSOC));

	echo $reply->toJson();
?>
