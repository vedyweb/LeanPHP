<?php

namespace LeanPHP\Core\Http;

class JwtHelper {
    private $secret = "supersecretkey";

    public function createJWT($payload, $expiryDuration) {
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
        
        $expiryTime = time() + $expiryDuration;
        $payload['exp'] = $expiryTime;

        $headerEncoded = base64_encode(json_encode($header));
        $payloadEncoded = base64_encode(json_encode($payload));

        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        $signatureEncoded = base64_encode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    public function validateJWT($token) {
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $token);

        $payload = json_decode(base64_decode($payloadEncoded), true);

        if ($payload['expiry'] < time()) {
            return false;
        }

        $signature = base64_decode($signatureEncoded);
        $calculatedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);

        return hash_equals($signature, $calculatedSignature);
    }
}
