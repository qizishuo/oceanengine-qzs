<?php

namespace OceanengineQzs;

use Illuminate\Cache;
use OceanengineQzs\Exceptions\Exception as Exceptiona;

// src/Core/AccessToken.php
class Auth {
    protected $baseUri = 'https://ad.oceanengine.com/open_api/';
    protected $cachePrefix = 'oceanengine_token';
    protected $appId;
    protected $secret;
    protected $redirectUri;

    public function __construct()
    {
        $this->appId = config('oceanengine.app_id');
        $this->secret = config('oceanengine.secret');
        $this->redirectUri = config('oceanengine.redirect_uri');
    }
    public function getToken() {
        $cacheKey = $this->cachePrefix;

        if ($token = Cache::get($cacheKey)) {
            return $token;
        }

        return $this->refreshByAdvertiser();
    }

    public function refreshByAdvertiser() {
        $refreshToken = $this->getRefreshToken();
        return $this->refreshToken($refreshToken);
    }

    public function refreshToken(string $refreshToken) {
        $response = $this->request('oauth2/refresh_token/', [
                'app_id' => config('oceanengine.app_id'),
                'secret' => config('oceanengine.secret'),
                'refresh_token' => $refreshToken
        ]);

        // 更新缓存
        $this->cacheToken($response);

        return $response['access_token'];
    }
    protected function cacheToken(array $response) {
        // 缓存access_token
        Cache::put(
            $this->cachePrefix,
            [
                'access_token' => $response['access_token'],
                'expires_at' => now()->addSeconds($response['expires_in'])->timestamp
            ],
            $response['expires_in'] - 600 // 提前10分钟过期
        );

        // 缓存refresh_token
        Cache::put(
            $this->cachePrefix.'refresh_token',
            [
                'refresh_token' => $response['refresh_token'],
                'expires_at' => now()->addDays($response['refresh_token_expires_in'])->timestamp // 假设refresh_token有效期为30天
            ],
            $response['refresh_token_expires_in'] - 600 // 提前10分钟过期
        );
    }
    public function getTokenByAuthCode(string $authCode) {
        $response = $this->request('oauth2/access_token/', [
                'app_id' => config('oceanengine.app_id'),
                'secret' => config('oceanengine.secret'),
                'auth_code' => $authCode
        ]);

        $this->cacheToken($response);

        return $response['access_token'];
    }

    protected function getRefreshToken() {
        $cacheKey = $this->cachePrefix.'refresh_token';

        if (!$refreshToken = Cache::get($cacheKey)) {
            throw new Exceptiona('Refresh token not found',100000);
        }

        return $refreshToken['refresh_token'];
    }

    private function request($url, $params)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post($this->baseUri.$url, ['json' => $params]);

        $data = json_decode($response->getBody()->getContents(), true);
        if (isset($data['error_code'])) {
            throw new Exceptiona($data['message'], $data['error_code']);
        }

        return $data;
    }
}
