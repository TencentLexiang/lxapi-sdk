<?php


namespace Lexiangla\Openapi;


use GuzzleHttp\Client as HttpClient;
use Qcloud\Cos\Client as CosClient;

class VodApi
{
    private $storageSignature;
    private $mediaStoragePath;
    private $coverStoragePath;
    private $storageAppId;
    private $storageBucket;
    private $storageRegion;
    private $storageRegionV5;
    private $vodSessionKey;
    /** @var array $tempCertificate */
    private $tempCertificate;
    // 自定义数据
    private $localVodStoragePath;
    private $localCoverFilePath;
    private $signature;
    // 视频信息
    private $videoName;
    private $videoType;
    private $videoSize;
    // 常量

    /**
     * VodApi constructor.
     * @param $localVodStroagePath
     * @param $localCoverFilePath
     * @param $signature
     */
    public function __construct($signature, $localVodStroagePath, $localCoverFilePath)
    {
        $this->localVodStoragePath = $localVodStroagePath;
        $this->localCoverFilePath = $localCoverFilePath;
        $this->signature = $signature;
    }


    /**
     * 上传视频
     * @return array
     * @throws ApiException
     */
    public function uploadVideo()
    {
        //1. 申请上传
        $applyUploadResponse = $this->applyUpload();
        if (empty($applyUploadResponse) || $applyUploadResponse['code'] != 0) {
            throw new ApiException('申请上传视频失败');
        }
        // videoData
        $applyUploadResponseData = isset($applyUploadResponse['data']) ? $applyUploadResponse['data'] : [];
        $this->storageRegionV5 = isset($applyUploadResponseData['StorageRegionV5']) ? $applyUploadResponseData['StorageRegionV5'] : '';
        $video_data = isset($applyUploadResponseData['video']) ? $applyUploadResponseData['video'] : [];
        $this->storageSignature = isset($video_data['storageSignature']) ? $video_data['storageSignature'] : '';
        $this->mediaStoragePath = isset($video_data['storagePath']) ? $video_data['storagePath'] : '';
        $this->coverStoragePath = isset($video_data['coverStoragePath']) ? $video_data['coverStoragePath'] : '';
        $this->storageAppId = isset($applyUploadResponseData['storageAppId']) ? $applyUploadResponseData['storageAppId'] : '';
        $this->storageBucket = isset($applyUploadResponseData['storageBucket']) ? $applyUploadResponseData['storageBucket'] : '';
        $this->storageRegion = isset($applyUploadResponseData['storageRegion']) ? $applyUploadResponseData['storageRegion'] : '';
        $this->vodSessionKey = isset($applyUploadResponseData['vodSessionKey']) ? $applyUploadResponseData['vodSessionKey'] : '';
        $this->tempCertificate = isset($applyUploadResponseData['tempCertificate']) ? $applyUploadResponseData['tempCertificate'] : '';

        $this->uploadToCos();
        $result = $this->commitUpload();

        return [
            'vod_file_id' => $result['data']['fileId'],
            'name' => $this->videoName,
            'type' => $this->videoType
        ];
    }

    private function uploadToCos()
    {
        if ($this->mediaStoragePath) {
            $this->uploadCos(
                $this->localVodStoragePath,
                $this->storageBucket,
                $this->mediaStoragePath
            );
        }
        if ($this->coverStoragePath) {
            $this->uploadCos(
                $this->localCoverFilePath,
                $this->storageBucket,
                $this->coverStoragePath
            );
        }
    }

    // 上传
    private function uploadCos($localPath, $bucket, $cosPath)
    {
        $this->tempCertificate['appId'] = $this->storageAppId;
        $cosClient = new CosClient(array(
            'schema' => 'http',
            'credentials' => $this->tempCertificate,
            'region' => $this->storageRegionV5,
        ));
        return $cosClient->Upload($bucket, $cosPath, fopen($localPath, 'rb'), ['PartSize' => 10485760]);
    }

    /**
     * 申请上传
     * @return mixed
     * @throws ApiException
     */
    private function applyUpload()
    {
        $video_info = pathinfo($this->localVodStoragePath);
        $this->videoType = $video_info['extension'];
        $this->videoName = $video_info['filename'];
        $this->videoSize = filesize($this->localVodStoragePath);

        /** @var Client $client */
        $client = new HttpClient();
        $url = 'https://vod2.qcloud.com/v3/index.php?Action=ApplyUploadUGC';
        $data = [
            'signature' => $this->signature,
            'videoName' => $this->videoName,
            'videoType' => $this->videoType,
            'videoSize' => $this->videoSize,
        ];
        $response = $client->request('POST', $url, ['json' => $data]);
        if ($response->getStatusCode() != 200) {
            throw new ApiException('申请视频上传失败，返回码:' . $response->getStatusCode());
        }
        return json_decode($response->getBody(), true);
    }


    /**
     * 申请上传
     * @return mixed
     * @throws ApiException
     */
    private function commitUpload()
    {
        /** @var Client $client */
        $client = new HttpClient();

        $url = 'https://vod2.qcloud.com/v3/index.php?Action=CommitUploadUGC';
        $data = [
            'signature' => $this->signature,
            'vodSessionKey' => $this->vodSessionKey,
        ];
        $response = $client->request('POST', $url, ['json' => $data]);
        if ($response->getStatusCode() != 200) {
            throw new ApiException('确认视频上传失败，返回码:' . $response->getStatusCode());
        }
        return json_decode($response->getBody(), true);
    }

    /**
     * 获取文件类型
     *
     * @param $filePath
     * @return mixed|string
     */
    private static function getFileType($filePath)
    {
        if (empty($filePath)) {
            return '';
        }
        $tmp = explode('/', $filePath);
        $fullFileName = end($tmp);
        if (strrpos($fullFileName, '.') === false) {
            return '';
        }
        $pathArr = explode('.', $filePath);
        return end($pathArr);
    }

    /**
     * 获取文件名(不包含后缀)
     *
     * @param $filePath
     * @return bool|mixed|string
     */
    private static function getFileName($filePath)
    {
        if (empty($filePath)) {
            return '';
        }
        $tmp = explode('/', $filePath);
        $fullFileName = end($tmp);
        $pos = strrpos($fullFileName, '.');
        if ($pos === false) {
            return $fullFileName;
        }
        return substr($fullFileName, 0, $pos);
    }
}
