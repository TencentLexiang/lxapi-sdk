<?php

namespace Lexiangla\Openapi;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as HttpClient;
use Exception;

Trait VideoTrait
{

    /**
     * @param $staff_id 员工
     * @param $video_path 视频本地路径
     * @param $cover_img_path 封面图本地路径
     * @return array
     * @throws Exception
     * @throws GuzzleException
     */
    public function uploadVideo($staff_id, $video_path, $cover_img_path)
    {
        $signature = $this->getUploadSignature($staff_id);
        $vodApi = new VodApi($signature, $video_path, $cover_img_path);
        $attributes = $vodApi->uploadVideo();
        $document = [
            'data' => [
                'type' => 'video',
                'attributes' => $attributes
            ]
        ];
        return $this->forStaff($staff_id)->post('videos', $document);
    }

    /**
     * 获取上传签名，10次/分钟调用频率
     * @param $staff_id
     * @return string
     * @throws ApiException
     * @throws GuzzleException
     */
    private function getUploadSignature($staff_id)
    {
        $client = new HttpClient();
        $response = $client->request('POST', $this->main_url . '/' . $this->verson . '/videos/upload-signature', [
            'json' => [],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $staff_id,
            ],
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new ApiException('获取视频上传接口失败');
        }
        $response = json_decode($response->getBody()->getContents(), true);
        return $response['signature'];
    }
}
