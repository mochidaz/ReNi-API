<?

class Response {
    public $status;
    public $body;

    public function __construct($status, $body) {
        $this->status = $status;
        $this->body = $body;
    }

    public function send() {
        http_response_code($this->status);
        echo json_encode($this->body);
    }
}

?>