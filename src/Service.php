<?php

namespace Lexiangla\Openapi;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class Service
{

    protected $main_url = 'https://lxapi.lexiangla.com/cgi-bin/service';

    protected $suite_id;

    protected $suite_secret;

    protected $suite_ticket;

    protected $suite_access_token = '';

    protected $corp_access_token = '';

    public function __construct($suite_id = '', $suite_secret = '')
    {
        $this->suite_id = $suite_id;
        $this->suite_secret = $suite_secret;
    }

    public function getSuiteToken()
    {
        if ($this->suite_access_token) {
            return $this->suite_access_token;
        }

        $options = ['json' => [
            'grant_type' => 'client_credentials',
            'suite_id' => $this->suite_id,
            'suite_secret' => $this->suite_secret,
            'suite_ticket' => $this->getSuiteTicket(),
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post($this->main_url . '/get_suite_token', $options);
        $response = json_decode($response->getBody()->getContents(), true);
        $this->suite_access_token = $response['suite_access_token'];
        return $this->suite_access_token;
    }

    public function setSuiteAccessToken($suite_access_token)
    {
        $this->suite_access_token = $suite_access_token;
    }

    public function setSuiteTicket($suite_ticket)
    {
        $this->suite_ticket = $suite_ticket;
    }

    public function getSuiteTicket()
    {
        return $this->suite_ticket;
    }

    public function getCorpAccessToken($company_id, $permanent_code)
    {
        if ($this->corp_access_token) {
            return $this->corp_access_token;
        }

        $options = ['json' => [
            'grant_type' => 'client_credentials',
            'company_id' => $company_id,
            'permanent_code' => $permanent_code,
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post($this->main_url . '/get_corp_token?suite_access_token=' . $this->getSuiteToken(), $options);
        $response = json_decode($response->getBody()->getContents(), true);
        $this->corp_access_token = $response['access_token'];
        return $this->corp_access_token;
    }

    public function setCorpAccessToken($corp_access_token)
    {
        $this->corp_access_token = $corp_access_token;
    }

    public function getCorpClient($company_id, $permanent_code)
    {
        $api = new Api();
        $api->setAccessToken($this->getCorpAccessToken($company_id, $permanent_code));
        return $api;
    }
}
