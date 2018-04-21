<?php
namespace Lexiangla\Openapi;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;

class Api{

    private $main_url = 'https://lxapi.lexiangla.net/cgi-bin';

    private $verson = 'v1';

    public function __construct($app_key, $app_secret)
    {
        $this->key = $app_key;
        $this->app_secret = $app_secret;
    }

    public function getAccessToken()
    {
        $options = ['form_params' =>[
            'grant_type' => 'client_credentials',
            'app_key' => $this->key,
            'app_secret' => $this->app_secret
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post($this->main_url . '/token', $options);
        $response = json_decode($response->getBody()->getContents(), true);
        // TODO token有效期$expire_in秒，客户端须写入缓存多次使用
        // $expire_in = $access_token['expire_in'];
        return $response['access_token'];
    }

    public function getDownloadLogs($request = [])
    {
        return $this->get('download-logs', $request);
    }

    public function get($uri, $data)
    {
        return $this->request('GET', $uri . '?' . http_build_query($data));
    }

    public function request($method, $uri)
    {
        $headers["Authorization"] = 'Bearer ' . $this->getAccessToken();
        $request = new Request($method, $this->main_url.'/'.$this->verson.'/'.$uri, $headers);

        $client = new JsonApiClient(new Client());

        $response = $client->sendRequest($request);
        return $response->document();
    }
}