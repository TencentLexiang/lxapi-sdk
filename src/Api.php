<?php
namespace Lexiangla\Openapi;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;

class Api
{

    protected $main_url = 'https://lxapi.lexiangla.com/cgi-bin';

    protected $verson = 'v1';

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
        return $response['access_token'];
    }

    public function get($uri, $data = [])
    {
        if ($data) {
            $uri .= ( '?' . http_build_query($data));
        }
        return $this->request('GET', $uri);
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