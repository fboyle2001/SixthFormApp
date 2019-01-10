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

  // Select all files of the correct type that haven't yet expired.
  // Display them in date of being added
  $time = time();
	$selectLatest = Database::get()->prepare("SELECT * FROM `files` WHERE `Type` = :type AND `ExpiryDate` >= :expire ORDER BY `AddedDate` DESC");
	$selectLatest->execute(["type" => StoreType::newsletters, "expire" => $time]);

  // Send them back to the client to process
	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->rowCount() != 0);
	$reply->setValue("records", $selectLatest->fetchAll(PDO::FETCH_ASSOC));

	echo $reply->toJson();

?>
