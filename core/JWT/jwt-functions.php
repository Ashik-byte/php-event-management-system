<?php
require_once 'JWT.php';
require_once 'Key.php';
$config = require_once __DIR__.'/../../config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = $config['JWT_SECRET_KEY'];

function createJWT($payload) {
    global $secretKey; 

    $issuedAt = time();
    $expirationTime = $issuedAt + 900;
    $payload['iat'] = $issuedAt;
    $payload['exp'] = $expirationTime;

    return JWT::encode($payload, $secretKey, 'HS256');
}

function verifyJWT($jwt) {
    global $secretKey; 

    try {
        return JWT::decode($jwt, new Key($secretKey, 'HS256'));
    } catch (Exception $e) {
        return false;
    }
}
