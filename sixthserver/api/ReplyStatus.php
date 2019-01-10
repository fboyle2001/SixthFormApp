<?php
  if(!defined("AllowIncludes")) {
    die("403");
  }

  // Defines the status object in the JSON consists of a code and description
  class ReplyStatus {

    public $code;
    public $description;

    public function __construct() {
      $this->code = 0;
      $this->description = "";
    }

    public static function withData($code, $description) {
      $status = new ReplyStatus();
      $status->setCode($code);
      $status->setDescription($description);
      return $status;
    }

    public function setCode($code) {
      $this->code = $code;
    }

    public function setDescription($description) {
      $this->description = $description;
    }

    public function toJson() {
      return json_encode($this);
    }

  }

?>
