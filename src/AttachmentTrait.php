<?php
namespace Lexiangla\Openapi;

Trait AttachmentTrait
{
    public function uploadAttachment($staff_id, $file_path, $options = [])
    {
        $this->staff_id = $staff_id;
        if (!file_exists($file_path)) {
            throw new \Exception("上传文件路径不存在");
        }

        $cos_data = $this->postCosFile($file_path, 'attachment');
        if (empty($cos_data)) {
            throw new \Exception("上传到腾讯云cos存储或者获取签名失败");
        }
        list($etag, $state) = $cos_data;
        if (empty($etag)) {
            throw new \Exception("上传到腾讯云cos存储失败");
        }
        $document = [
            'data' => [
                'type' => 'attachment'
            ],
        ];
        if (isset($options['name'])) {
            $document['data']['attributes']['name'] = $options['name'];
        }

        if (isset($options['downloadable'])) {
            $document['data']['attributes']['downloadable'] = $options['downloadable'];
        }
        return $this->forStaff($staff_id)->post('attachments?state='.$state, $document);
    }
}
