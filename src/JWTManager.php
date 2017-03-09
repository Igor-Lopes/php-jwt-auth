<?php

namespace Igor\JWT;

use Firebase\JWT\JWT;

class JWTManager
{
    private $options;

    public function __construct(array $options = [])
    {
        $audience = $_SERVER['HTTP_USER_AGENT'].$this->getIpAddress().gethostname();

        $this->options = array(
          'issuer' => 'www.yourdomain.com',
          'subject' => 'subject',
          'audience' => $audience,
          'notBeforeSeconds' => 10,
          'expireSeconds' => 3600,
          'algo' => array('HS256'),
          'secret' => 'secret',
      );

        $this->options = array_replace($this->options, $options);
    }

    public function encodeToken($scope = null)
    {
        $issuedAt = time();
        $notBefore = $issuedAt + $this->options['notBeforeSeconds'];
        $expire = $notBefore + $this->options['expireSeconds'];

        $token = array(
            'iss' => $this->options['issuer'],
            'aud' => $this->options['audience'],
            'sub' => $this->options['subject'],
            'iat' => $issuedAt,
            'nbf' => $notBefore,
            'exp' => $expire,
            'scope' => $scope,
        );

        $jwt = JWT::encode($token, $this->options['secret']);

        return $jwt;
    }

    public function decodeToken($jwt)
    {
        JWT::$leeway = 60;

        try {
            $decoded = (array) JWT::decode($jwt, $this->options['secret'], array('HS256'));
            if ($decoded['aud'] === $this->options['audience']) {
                $data['valid'] = true;
                $data['decoded'] = $decoded;

                return $data;
            }

            $data['valid'] = false;
            $data['message'] = 'Aud claim is invalid';

            return $data;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            $data['valid'] = false;
            $data['message'] = $e->getMessage();

            return $data;
        } catch (\Firebase\JWT\ExpiredException $e) {
            $data['valid'] = false;
            $data['message'] = $e->getMessage();

            return $data;
        }
    }

    private function getIpAddress()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
