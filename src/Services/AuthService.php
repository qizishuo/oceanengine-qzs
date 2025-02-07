<?php

namespace packages\oceanengine-qzs\src\Services;

class AuthService
{
    protected $baseAuthUrl = 'https://open.oceanengine.com/audit/oauth.html';

    public function generateAuthUrl(array $params = []) {
        $baseParams = [
            'app_id' => config('oceanengine.app_id'),
//            'redirect_uri' => config('oceanengine.redirect_uri'),
            'state' => Str::random(16),
            'scope' => '',
            'material_auth' => '1'
        ];

        return $this->baseAuthUrl.'?'.http_build_query(array_merge($baseParams, $params));
    }
}
