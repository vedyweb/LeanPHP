<?php

namespace leanphp\core;

use leanphp\core\Request;
use leanphp\core\Response;

/**
 * Summary of JwtHelper
 */
class JwtHelper {

 /**
  * Summary of secret
  * @var string
  */
    private $secret = "supersecretkey"; // Bu anahtar güvenlik için çok önemli, güçlü ve gizli tutulmalıdır.

 /**
  * Summary of currentUser
  * @var 
  */
    private static $currentUser = null;

    /**
     * Summary of __construct
     */
    public function __construct() {
    }

    /**
     * Summary of user
     * @return mixed
     */
    public static function user() {
        return self::$currentUser;
    }

    /**
     * Summary of getAuthenticate
     * @param \leanphp\core\Request $request
     * @param \leanphp\core\Response $response
     * @throws \Exception
     * @return bool
     */
    public function getAuthenticate(Request $request, Response $response): bool
    {
        $headers = getallheaders();

        if (!isset($headers['authorization'])) {
            throw new \Exception('Authorization header is missing');
        }

        $token = str_replace('Bearer ', '', $headers['authorization']);

        if (!$this->validateJWT($token)) {
            throw new \Exception('Invalid or expired token');
        }

        // Kullanıcıyı ayarla
        $payload = $this->decodeJWT($token);
        self::$currentUser = $payload;

        return true;
    }

    
    /**
     * Summary of createJWT
     * @param mixed $payload
     * @param mixed $expiryDuration
     * @return string
     */
    public function createJWT($payload, $expiryDuration) {
        $header = ["alg" => "HS256", "typ" => "JWT"];
        $payload['exp'] = time() + $expiryDuration;

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    /**
     * Summary of validateJWT
     * @param mixed $token
     * @return bool
     */
    public function validateJWT($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        $signature = $this->base64UrlDecode($signatureEncoded);
        $calculatedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);

        return hash_equals($signature, $calculatedSignature);
    }

    /**
     * Summary of decodeJWT
     * @param mixed $token
     * @throws \Exception
     * @return mixed
     */
    public function decodeJWT($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \Exception("Invalid JWT structure");
        }

        $payloadEncoded = $parts[1];
        $payload = json_decode($this->base64UrlDecode($payloadEncoded), true);
        if (is_null($payload)) {
            throw new \Exception("Invalid payload encoding");
        }

        return $payload;
    }

    /**
     * Summary of base64UrlEncode
     * @param mixed $data
     * @return string
     */
    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Summary of base64UrlDecode
     * @param mixed $data
     * @return bool|string
     */
    private function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}