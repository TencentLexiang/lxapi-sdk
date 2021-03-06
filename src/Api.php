<?php

namespace Lexiangla\Openapi;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class Api
{
    use DocTrait;
    use QuestionTrait;
    use ThreadTrait;
    use CategoryTrait;
    use CommentTrait;
    use LikeTrait;
    use TeamTrait;
    use PointTrait;
    use AttachmentTrait;
    use VideoTrait;
    use LiveTrait;
    use ClazzTrait;
    use CourseTrait;
    use CertificateRewardTrait;

    protected $main_url = 'https://lxapi.lexiangla.com/cgi-bin';

    protected $verson = 'v1';

    protected $response;

    protected $key;

    protected $app_secret;

    protected $access_token = '';

    protected $staff_id;

    protected $listeners;

    public function __construct($app_key = '', $app_secret = '')
    {
        $this->key = $app_key;
        $this->app_secret = $app_secret;
    }

    public function getAccessToken()
    {
        if ($this->access_token) {
            return $this->access_token;
        }

        $options = ['json' => [
            'grant_type' => 'client_credentials',
            'app_key' => $this->key,
            'app_secret' => $this->app_secret
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post($this->main_url . '/token', $options);
        $response = json_decode($response->getBody()->getContents(), true);
        $this->access_token = $response['access_token'];
        return $this->access_token;
    }

    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    public function get($uri, $data = [])
    {
        if ($data) {
            $uri .= ('?' . http_build_query($data));
        }
        return $this->request('GET', $uri);
    }


    public function post($uri, $data = [])
    {
        return $this->request('POST', $uri, $data);
    }

    public function put($uri, $data = [])
    {
        return $this->request('PUT', $uri, $data);
    }

    public function patch($uri, $data = [])
    {
        return $this->request('PATCH', $uri, $data);
    }

    public function delete($uri, $data = [])
    {
        return $this->request('DELETE', $uri, $data);
    }

    public function request($method, $uri, $data = [])
    {
        $headers["Authorization"] = 'Bearer ' . $this->getAccessToken();
        $headers["StaffID"] = $this->staff_id;
        if (!empty($this->listeners)) {
            $data['meta']['listeners'] = $this->listeners;
            $this->listeners = [];
        }
        if (!empty($data)) {
            $headers["Content-Type"] = 'application/vnd.api+json';
        }
        $request = new Request($method, $this->main_url . '/' . $this->verson . '/' . $uri, $headers, json_encode($data));
        $client = new JsonApiClient(new Client());

        $this->response = $client->sendRequest($request);

        if ($this->response->getStatusCode() >= 400) {
            return json_decode($this->response->getBody()->getContents(), true);
        }
        if ($this->response->getStatusCode() == 204) {
            return [];
        }
        if (in_array($this->response->getStatusCode(), [200, 201])) {
            return $this->response->document()->toArray();
        }
    }

    /**
     * ????????????
     * @param $staff_id
     * @param $type
     * @param $file
     * @return mixed
     */
    public function postAsset($staff_id, $type, $file)
    {
        $data = [
            [
                'name' => 'file',
                'contents' => $file,
            ],
            [
                'name' => 'type',
                'contents' => $type
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', $this->main_url . '/' . $this->verson . '/assets', [
            'multipart' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * ????????????????????????cos???
     * @param $file_path
     * @param $upload_type
     * @return array|bool [etag, state]
     */
    private function postCosFile($file_path, $upload_type)
    {
        $filename = pathinfo($file_path, PATHINFO_BASENAME);

        $cos_param = $this->getDocCOSParam($filename, $upload_type);

        if (empty($cos_param['options']) || empty($cos_param['object'])) {
            return false;
        }

        $object = $cos_param['object'];
        $object['filepath'] = $file_path;

        return [$this->qcloudPutObject($object, $cos_param['options']), $object['state']];
    }

    /**
     * ???????????????????????????????????????
     * @param $file_name
     * @param $type
     * @return mixed
     */
    private function getDocCOSParam($file_name, $type)
    {
        $data = [
            'filename' => $file_name,
            'type' => $type
        ];
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', $this->main_url . '/' . $this->verson . '/docs/cos-param', [
            'json' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $this->staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * ?????????????????????COS???putObject?????????????????????
     * https://cloud.tencent.com/document/product/436/7749
     * @param $object
     * @param $options
     * @return string ????????????????????? MD5 ???
     */
    private function qcloudPutObject($object, $options)
    {
        $key = $object['key'];
        $url = 'http://' . $options['Bucket'] . '.cos.' . $options['Region'] . '.myqcloud.com/' . $key;

        $headers = [
                'Authorization' => $object['auth']['Authorization'],
                'x-cos-security-token' => $object['auth']['XCosSecurityToken']
            ] + $object['headers'];

        $raw_request_headers = [];
        foreach ($headers as $key => $header) {
            $raw_request_headers[] = $key . ":" . $header;
        }

        $ch = curl_init(); //?????????CURL??????
        curl_setopt($ch, CURLOPT_URL, $url); //???????????????URL
        curl_setopt($ch, CURLOPT_HTTPHEADER, $raw_request_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //??????TRUE???curl_exec()?????????????????????????????????????????????
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //??????????????????
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($object['filepath']));//????????????????????????

        $output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // ???????????????????????????????????????
        $raw_response_headers = explode("\r\n", trim(substr($output, 0, $header_size)));
        foreach ($raw_response_headers as $key => $raw_response_header) {
            if (stripos($raw_response_header, 'ETag') === 0) {
                list($item, $value) = explode(":", $raw_response_header);
                $etag = trim(trim($value), '"');
                return $etag;
            }
        }
    }

    /**
     * ????????????????????????????????????
     * @param $staffs
     * @return mixed
     */
    public function putStaffsAnniversaries($staffs)
    {
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('PUT', $this->main_url . '/' . $this->verson . '/wish/staffs-anniversaries', [
            'json' => compact('staffs'),
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $this->staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * @return JsonApiResponse
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * @param $staff_id
     * @return $this
     */
    public function forStaff($staff_id)
    {
        $this->staff_id = $staff_id;
        return $this;
    }

    /**
     * @param $listeners
     * @return $this
     */
    public function setListeners($listeners)
    {
        if (is_array($listeners)) {
            $this->listeners = $listeners;
        }
        return $this;
    }
}
