<?php

namespace OceanengineQzs\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OceanengineController extends  Controller
{
    public function handleCallback(Request $request)
    {
        // 获取回调数据
        $data = $request->all();

        // 处理回调逻辑
        \Log::info('收到巨量引擎回调', $data);

        return response()->json(['message' => '回调接收成功']);
    }
}
