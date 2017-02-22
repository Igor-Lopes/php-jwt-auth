<?php

use Firebase\JWT\JWT;

class JWTManager{
    private $issuer;
    private $aud;
    private $issuedAt;
    private $notBefore;
    private $expire;
    private $tokenKey;

    public function __construct(){
        $this->issuer = 'http://yourdomain.com';
        $this->issuedAt = time();
        $this->notBefore = $this->issuedAt + 10;
        $this->expire = $this->notBefore + 60;
        $this->aud = $_SERVER['HTTP_USER_AGENT'].$this->getIpAddress().gethostname();
        $this->userBrowser = $_SERVER['HTTP_USER_AGENT'];
        $this->tokenKey = 'YOUR_KEY_HERE';
    }

    public function encodeToken($scope){
        $token = array(
            'iss' => $this->issuer,
            'aud' => $this->aud,
            'iat' => $this->issuedAt,
            'nbf' => $this->notBefore,
            'exp' => $this->expire,
            'scope' => $scope,
        );

        $jwt = JWT::encode($token, $this->tokenKey);

        return $jwt;
    }

    public function decodeToken($jwt){
        JWT::$leeway = 60;

        try {
            $decoded = (array) JWT::decode($jwt, $this->tokenKey, array('HS256'));
            if ($decoded['aud'] === $_SERVER['HTTP_USER_AGENT'].$this->getIpAddress().gethostname()) {
                $data['valid'] = true;
                $data['message'] = 'Token is valid';
                $data['scope'] = $decoded['scope'];

                return json_encode($data);
            }
            $data['valid'] = false;
            $data['message'] = 'Aud claim is invalid';

            return json_encode($data);
        } catch (Firebase\JWT\SignatureInvalidException $e) {
            $data['valid'] = false;
            $data['message'] = $e->getMessage();

            return json_encode($data);
        } catch (Firebase\JWT\ExpiredException $e) {
            $data['valid'] = false;
            $data['message'] = $e->getMessage();

            return json_encode($data);
        }
    }

    private function getIpAddress(){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
