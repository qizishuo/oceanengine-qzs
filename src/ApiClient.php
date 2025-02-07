<?php

namespace OceanengineQzs;

use OceanengineQzs\Exceptions\Exception as Exceptiona;

class ApiClient
{
    protected $client;
    protected $baseUri = "https://ad.oceanengine.com/open_api/";

    public function __construct()
    {
        $this->client = new Client();
    }
    public function getClueLife(array $localAccountIds, string $startTime, string $endTime, int $page = 1, int $pageSize = 10, string $accessToken)
    {
        $url = "2/tools/clue/life/get/";

        // 参数校验
        if (empty($localAccountIds) || count($localAccountIds) > 50) {
            throw new Exceptiona("local_account_ids 不能为空，且最多 50 个");
        }

        if (empty($startTime) || empty($endTime)) {
            throw new Exceptiona("start_time 和 end_time 不能为空");
        }

        $params = [
            'local_account_ids' => $localAccountIds,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'page' => $page,
            'page_size' => $pageSize
        ];

        return $this->request('POST', $url, $params, $accessToken);
    }

    public function request($method, $endpoint, $params = [], $accessToken = null)
    {
        $url = $this->baseUri . ltrim($endpoint, '/');
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ];

        try {
            $response = $this->client->request($method, $url, [
                'headers' => $headers,
                'json' => $params,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            throw new Exceptiona("API 请求失败: " . $e->getMessage());
        }
    }
}
