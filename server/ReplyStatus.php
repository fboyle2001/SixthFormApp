<?php

  class ReplyStatus {

    public $code;
    public $description;

    public function __construct() {
      $this->code = 0;
      $this->description = "";
    }

    public static function withData(int $code, string $description) : ReplyStatus {
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
