<?php

namespace Helpers;


class Jwt
{
    private static function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function sign($data)
    {
        // header
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $header_encoded = self::base64url_encode(json_encode($header));

        // payload
        $payload = [
            'iss' => 'shovan-php',
            'iat' => time(),
            'exp' => strtotime('+24 hour'),
            'email' => $data['email']
        ];
        $payload_encoded = self::base64url_encode(json_encode($payload));

        // signature
        $signature = hash_hmac('SHA256', $header_encoded . $payload_encoded, $_ENV['JWT_SECRET']);
        $signature_encoded = self::base64url_encode($signature);

        return $header_encoded . '.' . $payload_encoded . '.' . $signature_encoded;
    }

    public static function verify($token): bool | array
    {
        $token_parts = explode('.', $token);

        if (count($token_parts) !== 3) {
            return false;
        }

        // Recalculate signature
        $signature = hash_hmac('SHA256', $token_parts[0] . $token_parts[1], $_ENV['JWT_SECRET']);
        $signature_encoded = self::base64url_encode($signature);

        // Compare signatures
        if (!hash_equals($signature_encoded, $token_parts[2])) {
            return false;
        }

        // Decode payload
        $payload_json = base64_decode(strtr($token_parts[1], '-_', '+/'));
        $payload = json_decode($payload_json, true);

        // Check expiration
        if (!isset($payload['exp']) || time() > $payload['exp']) {
            return false;
        }

        return $payload;
    }
}
