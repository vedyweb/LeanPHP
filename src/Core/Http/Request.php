<?php

namespace LeanPHP\Core\Http;

class Request {

    private $data = [];

    public function __construct() {
        $this->parseIncomingParams();
    }

    private $parsedBody = null;

    public function getParsedBody() {
        if ($this->parsedBody === null) {
            $this->parseBody();
        }
        return $this->parsedBody;
    }

    private function parseBody() {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
            $this->parsedBody = json_decode(file_get_contents('php://input'), true);
        } elseif (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
            $this->parsedBody = $_POST;
        } else {
            // Daha fazla içerik türü desteklemek için buraya eklemeler yapabilirsiniz
            $this->parsedBody = [];
        }
    }
    
    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath() {
        return $_SERVER['REQUEST_URI'];
    }

    public function getParam($key, $default = null) {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    public function getBody() {
        return file_get_contents('php://input');
    }

    public function getHeaders() {
        return getallheaders();
    }

    private function parseIncomingParams() {
        $input = file_get_contents('php://input');

        if ($input) {
            $this->data = json_decode($input, true);
        } else {
            $this->data = $_POST;
        }
    }

    public function get($key, $default = null) {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}
