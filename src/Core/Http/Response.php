<?php

namespace LeanPress\Core\Http;

class Response {
    private $body;
    private $status = 200;
    private $headers = [];

    public function getBody() {
        return $this->body;
    }

    public function withStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function withHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withJSON($data) {
        $this->body = json_encode($data);
        $this->withHeader('Content-Type', 'application/json');
        return $this;
    }

    public function withHTML($content) {
        header('Content-Type: text/html');
        echo $content;
    }

    public function send() {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->body;
    }

    public function json($data, $statusCode = 200) {
        $this->setHeader('Content-Type', 'application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }

    public function setHeader($name, $value) {
        header("{$name}: {$value}");
    }
}