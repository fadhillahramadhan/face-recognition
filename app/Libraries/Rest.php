<?php

namespace App\Libraries;

class Rest
{
    private $allowOrigin;
    private $allowHeaders;
    private $allowMethods;
    private $response;

    public function __construct()
    {
        $this->response = \Config\Services::response();
        $this->allowOrigin = '*';
        $this->allowHeaders = '*';
        $this->allowMethods = false;
    }

    public function setAllowOrigin($value = false)
    {
        $this->allowOrigin = $value;
    }

    public function setAllowHeaders($value = false)
    {
        $this->allowHeaders = $value;
    }

    public function setAllowMethods($value = false)
    {
        $this->allowMethods = $value;
    }

    public function responseSuccess($msg = "OK.", $result = [])
    {
        $body = [
            'message' => $msg,
            'error' => '',
            'data' => (object)$result,
        ];

        $this->createResponse(200, $body);
    }

    public function responseFailed($msg = "Bad Request.", $error_type = "process", $result = [], $error_source = [])
    {
        $body = [
            'message' => $msg,
            'error' => $error_type,
            'data' => (object)$result,
        ];

        if (!empty($error_source) && ENVIRONMENT == 'development') {
            $body['error_source'] = $error_source;
        }

        $this->createResponse(400, $body);
    }

    public function responseUnauthorized($msg = "Bad Request.", $result = [])
    {
        $this->createResponse(401, [
            'message' => $msg,
            'error' => "unauthorized",
            'data' => [],
        ]);
    }

    private function createResponse($code, $body)
    {
        $response = $this->response;

        // if ($this->allowOrigin)
        // 	$response->setHeader('Access-Control-Allow-Origin', '*');

        // if ($this->allowHeaders)
        // 	$response->setHeader('Access-Control-Allow-Headers', '*');

        // if ($this->allowMethods)
        // 	$response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');

        $response->setStatusCode($code)->setJSON($body)->send();
        // exit;
    }
}
