<?php
namespace Lexiangla\Openapi;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class Api
{
    use DocTrait;
    use DownloadLogTrait;

    protected $main_url = 'https://lxapi.lexiangla.com/cgi-bin';

    protected $verson = 'v1';

    protected $response;

    protected $key;

    protected $app_secret;

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

    public function post($uri, $data = [])
    {
        return $this->request('POST', $uri, $data);
    }

    public function request($method, $uri, $data = [])
    {
        $headers["Authorization"] = 'Bearer ' . $this->getAccessToken();
        if (!empty($data)) {
            $headers["Content-Type"] = 'application/vnd.api+json';
        }
        $request = new Request($method, $this->main_url.'/'.$this->verson.'/'.$uri, $headers, json_encode($data));
        $client = new JsonApiClient(new Client());

        $this->response = $client->sendRequest($request);
        if ($this->response->getStatusCode() >= 400) {
            return json_decode($this->response->getBody()->getContents(), true);
        }
        return $this->response->document()->toArray();
    }

    public function postAsset($staff_id, $type, $file)
    {
        $data = [
            [
                'name'     => 'file',
                'contents' => $file,
            ],
            [
                'name' => 'staff_id',
                'contents' => $staff_id
            ],
            [
                'name' => 'type',
                'contents' => $type
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $this->main_url.'/'.$this->verson.'/assets', [
            'multipart' => $data,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->getAccessToken()
            ],
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        return $response;
    }

    /**
     * @return JsonApiResponse
     */
    public function response()
    {
        return $this->response;
    }
}