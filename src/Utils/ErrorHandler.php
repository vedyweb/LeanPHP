<?php

namespace LeanPress\Utils;
use Exception;

class ErrorHandler {
    private $filePath;

    public function __construct($filePath = "errors.log") {
        $this->filePath = $filePath;
    }

    public function write($message) {
        $date = date('Y-m-d H:i:s');
        $msg = "Model: [{$date}] {$message}\n";
        file_put_contents($this->filePath, $msg, FILE_APPEND);
    }

    public function handle(Exception $e) {
        // Log the error. This is a simplistic example; in a real-world scenario, you'd use a logging library or mechanism.
        $date = date('Y-m-d H:i:s');
        $msg = "Controller: [{$date}] {$e}\n";
        file_put_contents($this->filePath, $msg, FILE_APPEND);
    
        // Return a generic error message.
        return [
            'status' => 500,
            'message' => 'An unexpected error occurred.'
        ];
    }

    public function displayError(Exception $e) {
        $error = $this->handle($e);
        echo "<h1>Hata</h1>";
        echo "<p>{$error['message']}</p>";
    }
}