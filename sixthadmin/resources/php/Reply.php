<?php
  include("ReplyStatus.php");

  // Standard reply used to reply to AJAX requests
  class Reply {

    public $status;
    public $content;

    public function __construct() {
      $this->status = null;
      $this->content = [];
    }

    public static function withStatus($status) {
      $reply = new Reply();
      $reply->status = $status;
      return $reply;
    }

		public function setStatus($status) {
			$this->status = $status;
		}

    public function setValue($key, $value) {
      $this->content[$key] = $value;
    }

    public function toJson() {
      return json_encode($this);
    }

  }
?>
