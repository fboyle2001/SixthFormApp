<?php
  if(!defined("AllowIncludes")) {
    die("403");
  }

  include("ReplyStatus.php");

  // Used to form a standard format for the response to queries
  class Reply {

    public $status;
    public $content;

    public function __construct() {
      $this->status = null;
      $this->content = [];
    }

    // Creates a reply with a status object already set
    // Defaults the content to be empty
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
