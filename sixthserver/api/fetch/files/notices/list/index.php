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

  // Only display links of the correct type and that haven't expired yet
  // order it by the most recent
  $time = time();
  $selectLatest = Database::get()->prepare("SELECT * FROM `files` WHERE `Type` = :type AND `ExpiryDate` >= :expire ORDER BY `AddedDate` DESC");
  $selectLatest->execute(["type" => StoreType::notices, "expire" => $time]);

  // Send all the notices found back to the client
  $reply->setStatus(ReplyStatus::withData(200, "Success"));
  $reply->setValue("found", $selectLatest->rowCount() != 0);
  $reply->setValue("records", $selectLatest->fetchAll(PDO::FETCH_ASSOC));

	echo $reply->toJson();
?>
