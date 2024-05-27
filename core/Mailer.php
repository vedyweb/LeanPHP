<?php

namespace leanphp\core;
use Exception;
use leanphp\core\ErrorHandler;

class Mailer
{
    private $sender;
    private $replyTo;
    private $returnPath;
    private $appName;
    private $errorHandler;

    public function __construct()
    {
        $this->appName = getenv('APP_NAME') ?: 'leanphp';
        $this->sender = getenv('EMAIL_SENDER') ?: 'leanphp <info@leanphp.io>';
        $this->replyTo = getenv('EMAIL_REPLY_TO') ?: 'leanphp Support <support@leanphp.io>';
        $this->returnPath = getenv('EMAIL_RETURN_PATH') ?: 'info@leanphp.io';
    }

    public function sendEmail($email, $subject, $bodyContent)
    {
        // Hata raporlamayı kapat
        error_reporting(0);
    
        $subject = $this->appName . ' - ' . $subject;
        $body = $this->generateEmailBody($bodyContent);
        $headers = $this->generateEmailHeaders();
    
        print_r($body);
        try {
            if (!mail($email, $subject, $body, $headers)) {
                throw new Exception("Failed to send email. Please check your mail server configuration.");
            }
            return json_encode(['message' => 'Mail sent successfully']);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Internal Server Error',
                'message' => 'Email Send Error!: ' . $e->getMessage(),
                'code' => 0,
                'identifier' => 'error_' . uniqid()
            ]);
            exit;
        }
    }
    

    private function generateEmailBody($bodyContent)
    {
        $message = '<html><body>';
        $message .= $bodyContent;
        $message .= '</body></html>';
        return $message;
    }

    private function generateEmailHeaders()
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: {$this->sender}" . "\r\n";
        $headers .= "Reply-To: {$this->replyTo}" . "\r\n";
        $headers .= "Return-Path: {$this->returnPath}" . "\r\n";
        return $headers;
    }
}
