<?php

namespace LeanPress\Utils;
use Exception;

class Logger {
    private $filePath;

    public function __construct($filePath = "errors.log") {
        $this->filePath = $filePath;
    }

    public function write($message) {
        $date = date('Y-m-d H:i:s');
        $msg = "[{$date}] {$message}\n";
        file_put_contents($this->filePath, $msg, FILE_APPEND);
    }

    public function handle(Exception $e) {
        // Log the error. This is a simplistic example; in a real-world scenario, you'd use a logging library or mechanism.
        file_put_contents('errors.log', $e->getMessage() . PHP_EOL, FILE_APPEND);
    
        // Return a generic error message.
        return [
            'status' => 500,
            'message' => 'An unexpected error occurred.'
        ];
    }
}