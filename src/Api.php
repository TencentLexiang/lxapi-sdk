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
    protected $main_url = 'https://lxapi.lexiangla.com/cgi-bin';

    protected $verson = 'v1';

    protected $response;

    protected $key;

    protected $app_secret;

    protected $staff_id;

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
        if (!empty($data)) {
            $headers["Content-Type"] = 'application/vnd.api+json';
        }
        $request = new Request($method, $this->main_url.'/'.$this->verson.'/'.$uri, $headers, json_encode($data));
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

    public function postAsset($staff_id, $type, $file)
    {
        $data = [
            [
                'name'     => 'file',
                'contents' => $file,
            ],
            [
                'name' => 'type',
                'contents' => $type
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', $this->main_url.'/'.$this->verson.'/assets', [
            'multipart' => $data,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    public function postAttachment($staff_id, $target_type, $target_id, $file, $options = [])
    {
        $data = [
            [
                'name'     => 'file',
                'contents' => $file,
            ],
            [
                'name'     => 'target_type',
                'contents' => $target_type,
            ],
            [
                'name'     => 'target_id',
                'contents' => $target_id,
            ],
            [
                'name' => 'downloadable',
                'contents' => !empty($options['downloadable']) ? 1 : 0,
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', $this->main_url.'/'.$this->verson.'/attachments', [
            'multipart' => $data,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    private function getCOSAttachmentParams($filenames)
    {
        $data = compact('filenames');
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', $this->main_url . '/' . $this->verson . '/attachments/cos-attachment-params', [
            'json' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $this->staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * 直接调用腾讯云COS的putObject接口上传文件。
     * https://cloud.tencent.com/document/product/436/7749
     * @param $object
     * @param $options
     * @return string 上传文件内容的 MD5 值
     */
    private function qcloudPutObject($object, $options)
    {
        $key = $object['key'];
        $url = 'http://' . $options['Bucket'] . '.cos.' . $options['Region'] . '.myqcloud.com/' . $key;
        $client = new \GuzzleHttp\Client();
        $response = $client->put($url, [
            'headers' => [
                    'Authorization' => $object['auth']['Authorization'],
                    'x-cos-security-token' => $object['auth']['XCosSecurityToken'],
                ] + $object['headers'],
            'body' => fopen($object['filepath'], 'r'),
        ]);

        $header = $response->getHeader('ETag');
        $etag = isset($header[0]) ? $header[0] : '';
        $etag = trim($etag, '"');
        return $etag;
    }


    private function postCOSAttachment($state, $target_type, $target_id, $options = [])
    {
        $downloadable = !empty($options['downloadable']);
        $data = compact('state', 'target_type','target_id', 'downloadable');
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', $this->main_url . '/' . $this->verson . '/attachments/cos-attachment', [
            'json' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $this->staff_id,
            ],
        ]);
        $statusCode = $this->response->getStatusCode();
        return $statusCode === 204;
    }

    public function postCOSAttachments($filepaths, $target_type, $target_id, $options = [])
    {
        $results = [];

        //region 1. 从乐享批量获取直传文件的目标路径及签名参数
        $filenames = array_map(function ($filepath) {
            return pathinfo($filepath, PATHINFO_BASENAME);
        }, $filepaths);
        $cos_params = $this->getCOSAttachmentParams($filenames);
        //endregion 1

        for ($i = 0; $i < count($filepaths); $i++) {
            //region 2. 凭获得的签名及参数，直接调用腾讯云COS接口上传文件
            $object = &$cos_params['objects'][$i];
            $filename = pathinfo($filepaths[$i], PATHINFO_BASENAME);
            $object['filepath'] = $filepaths[$i];
            $etag = $this->qcloudPutObject($object, $cos_params['options']);
            //endregion 2

            if (!empty($etag)) {
                //region 3. 在乐享对上传完成的文件进行后续处理
                $success = $this->postCOSAttachment($object['state'], $target_type, $target_id, ['downloadable' => 1]);
                //endregion 3
            }
            $results[$filename] = compact('etag', 'success');
        }

        return $results;
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
}
