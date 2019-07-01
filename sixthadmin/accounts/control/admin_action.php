<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $actionId = post("actionId");
  $adminId = $_SESSION["userId"];

  // Check they have submitted the data
  if($adminId == null) {
    $reply->setStatus(ReplyStatus::withData(403, "Must be an admin"));
    die($reply->toJson());
  }

  if($actionId == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No action specified"));
    die($reply->toJson());
  }

  // Log the action
  $adminQuery = Database::get()->prepare("INSERT INTO `adminactions` (`AdminID`, `Action`) VALUES (:adminId, :actionId)");
  $adminQuery->execute(["adminId" => $adminId, "actionId" => $actionId]);

  if($adminQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Server error"));
    die($reply->toJson());
  }

  if($actionId == 1) {
    // Make users change their password on login
    $updateQuery = Database::get()->exec("UPDATE `accounts` SET `Reset` = 1 WHERE 1 = 1");

    if($updateQuery == 0) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to force password change."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully forced password change on app login for all users."));
    }
  } else if ($actionId == 2) {
    // Increment year group
    $updateQuery = Database::get()->exec("UPDATE `accounts` SET `Year` = `Year` + 1 WHERE `Year` <> 0");

    if($updateQuery == 0) {
      $reply->setStatus(ReplyStatus::withData(500, "Unable to increment year group."));
    } else {
      $reply->setStatus(ReplyStatus::withData(200, "Successfully incremented year group for all students."));
    }
  } else if ($actionId == 3) {
    // Delete all old students
    $selectAllQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `Year` = 14");
    $selectAllQuery->execute();

    if($selectAllQuery->rowCount() == 0) {
      $reply->setStatus(ReplyStatus::withData(400, "No users found to delete"));
      die($reply->toJson());
    }

    $successDelete = 0;
    $failDelete = 0;

    // Loop the users and delete them
    while($row = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
      $deleteAPI = Database::get()->prepare("DELETE FROM `apikeys` WHERE `Username` = :username");
      $deleteAPI->execute(["username" => $row["Username"]]);

      $deleteGroupLink = Database::get()->prepare("DELETE FROM `grouplink` WHERE `AccountID` = :id");
      $deleteGroupLink->execute(["id" => $row["ID"]]);

      $deletePush = Database::get()->prepare("DELETE FROM `push` WHERE `AccountID` = :id");
      $deletePush->execute(["id" => $row["ID"]]);

      $deleteAccount = Database::get()->prepare("DELETE FROM `accounts` WHERE `ID` = :id");
      $success = $deleteAccount->execute(["id" => $row["ID"]]);

      if($success) {
        $successDelete += 1;
      } else {
        $failDelete += 1;
      }
    }

    $reply->setStatus(ReplyStatus::withData(200, "Deleted old accounts"));
    $reply->setValue("success", $successDelete);
    $reply->setValue("fail", $failDelete);
  }

  echo $reply->toJson();
?>
