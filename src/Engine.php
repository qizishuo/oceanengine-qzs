<?php

namespace OceanengineQzs;


class Engine
{
    protected $auth;
    protected $client;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->client = new ApiClient();
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function getClient()
    {
        return $this->client;
    }
}
