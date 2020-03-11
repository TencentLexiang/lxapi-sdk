<?php
namespace Lexiangla\Openapi;

Trait DocTrait
{
    public function postDoc($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'doc',
                'attributes' => [
                    'title' => $attributes['title'],
                    'content' => $attributes['content'],
                    'is_markdown' => $attributes['is_markdown'],
                ]
            ]
        ];
        if (isset($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (isset($options['source'])) {
            $document['data']['attributes']['source'] = $options['source'];
        }
        if (isset($options['reship_url'])) {
            $document['data']['attributes']['reship_url'] = $options['reship_url'];
        }
        if (isset($options['allow_comment'])) {
            $document['data']['attributes']['allow_comment'] = $options['allow_comment'];
        }
        if (isset($options['picture_url'])) {
            $document['data']['attributes']['picture_url'] = $options['picture_url'];
        }
        if (isset($options['signature'])) {
            $document['data']['attributes']['signature'] = $options['signature'];
        }
        if (isset($options['category_id'])) {
            $document['data']['relationships']['category']['data']['type'] = 'category';
            $document['data']['relationships']['category']['data']['id'] = $options['category_id'];
        }
        if (isset($options['team_id'])) {
            $document['data']['relationships']['team']['data']['type'] = 'team';
            $document['data']['relationships']['team']['data']['id'] = $options['team_id'];
        }
        if (isset($options['directory_id'])) {
            $document['data']['relationships']['directory']['data']['type'] = 'directory';
            $document['data']['relationships']['directory']['data']['id'] = $options['directory_id'];
        }
        if (!empty($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        if (!empty($options['attachments'])) {
            foreach ($options['attachments'] as $attachment_id) {
                $document['data']['relationships']['attachments']['data'][] = [
                    'type' => 'attachment',
                    'id' => $attachment_id
                ];
            }
        }
        return $this->forStaff($staff_id)->post('docs', $document);
    }
    public function uploadDoc($staff_id, $file_path, $options = [])
    {
        $this->staff_id = $staff_id;
        if (!file_exists($file_path)) {
            throw new \Exception("上传文件路径不存在");
        }
        $cos_data = $this->postCosFile($file_path, 'file');
        if (empty($cos_data)) {
            throw new \Exception("上传到腾讯云cos存储或者获取签名失败");
        }
        list($etag, $state) = $cos_data;
        if (empty($etag)) {
            throw new \Exception("上传到腾讯云cos存储失败");
        }
        $document = [
            'data' => [
                'type' => 'doc'
            ],
        ];
        if (isset($options['name'])) {
            $document['data']['attributes']['name'] = $options['name'];
        }
        if (isset($options['downloadable'])) {
            $document['data']['attributes']['downloadable'] = $options['downloadable'];
        }
        if (isset($options['picture_url'])) {
            $document['data']['attributes']['picture_url'] = $options['picture_url'];
        }
        if (isset($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (!empty($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        if (isset($options['category_id'])) {
            $document['data']['relationships']['category']['data']['type'] = 'category';
            $document['data']['relationships']['category']['data']['id'] = $options['category_id'];
        }
        if (isset($options['team_id'])) {
            $document['data']['relationships']['team']['data']['type'] = 'team';
            $document['data']['relationships']['team']['data']['id'] = $options['team_id'];
        }
        if (isset($options['directory_id'])) {
            $document['data']['relationships']['directory']['data']['type'] = 'directory';
            $document['data']['relationships']['directory']['data']['id'] = $options['directory_id'];
        }
        return $this->forStaff($staff_id)->post('docs/upload?state='.$state, $document);
    }

    public function reUploadDoc($staff_id, $doc_id, $file_path)
    {
        $this->staff_id = $staff_id;
        if (!file_exists($file_path)) {
            throw new \Exception("上传文件路径不存在");
        }
        $cos_data = $this->postCosFile($file_path, 'file');
        if (empty($cos_data)) {
            throw new \Exception("上传到腾讯云cos存储或者获取签名失败");
        }
        list($etag, $state) = $cos_data;
        if (empty($etag)) {
            throw new \Exception("上传到腾讯云cos存储失败");
        }

        return $this->forStaff($staff_id)->patch('docs/' . $doc_id . '/re-upload?state='.$state);
    }

    public function patchDoc($staff_id, $doc_id, $options)
    {
        if (isset($options['target_type']) && $options['target_type'] == 'file') {
            return $this->patchFile($staff_id, $doc_id, $options);
        } else {
            return $this->patchDocument($staff_id, $doc_id, $options);
        }

    }

    public function patchDocument($staff_id, $doc_id, $options)
    {
        $document = [
            'data' => [
                'type' => 'doc',
            ]
        ];
        if (isset($options['title'])) {
            $document['data']['attributes']['title'] = $options['title'];
        }
        if (isset($options['content'])) {
            $document['data']['attributes']['content'] = $options['content'];
        }
        if (isset($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (isset($options['source'])) {
            $document['data']['attributes']['source'] = $options['source'];
        }
        if (isset($options['reship_url'])) {
            $document['data']['attributes']['reship_url'] = $options['reship_url'];
        }
        if (isset($options['allow_comment'])) {
            $document['data']['attributes']['allow_comment'] = $options['allow_comment'];
        }
        if (isset($options['picture_url'])) {
            $document['data']['attributes']['picture_url'] = $options['picture_url'];
        }
        if (isset($options['signature'])) {
            $document['data']['attributes']['signature'] = $options['signature'];
        }
        if (isset($options['category_id'])) {
            $document['data']['relationships']['category']['data']['type'] = 'category';
            $document['data']['relationships']['category']['data']['id'] = $options['category_id'];
        }
        if (isset($options['team_id'])) {
            $document['data']['relationships']['team']['data']['type'] = 'team';
            $document['data']['relationships']['team']['data']['id'] = $options['team_id'];
        }
        if (isset($options['directory_id'])) {
            $document['data']['relationships']['directory']['data']['type'] = 'directory';
            $document['data']['relationships']['directory']['data']['id'] = $options['directory_id'];
        }

        if (!empty($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        if (isset($options['attachments'])) {
            $document['data']['relationships']['attachments']['data'] = [];
            foreach ($options['attachments'] as $attachment_id) {
                $document['data']['relationships']['attachments']['data'][] = [
                    'type' => 'attachment',
                    'id' => $attachment_id
                ];
            }
        }
        return $this->forStaff($staff_id)->patch('docs/' . $doc_id . '?target_type=document', $document);
    }

    public function patchFile($staff_id, $doc_id, $options)
    {
        $document = [
            'data' => [
                'type' => 'doc',
            ]
        ];
        if (isset($options['name'])) {
            $document['data']['attributes']['name'] = $options['name'];
        }
        if (isset($options['downloadable'])) {
            $document['data']['attributes']['downloadable'] = $options['downloadable'];
        }
        if (isset($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (isset($options['allow_comment'])) {
            $document['data']['attributes']['allow_comment'] = $options['allow_comment'];
        }
        if (isset($options['picture_url'])) {
            $document['data']['attributes']['picture_url'] = $options['picture_url'];
        }
        if (isset($options['signature'])) {
            $document['data']['attributes']['signature'] = $options['signature'];
        }
        if (isset($options['category_id'])) {
            $document['data']['relationships']['category']['data']['type'] = 'category';
            $document['data']['relationships']['category']['data']['id'] = $options['category_id'];
        }
        if (isset($options['team_id'])) {
            $document['data']['relationships']['team']['data']['type'] = 'team';
            $document['data']['relationships']['team']['data']['id'] = $options['team_id'];
        }
        if (isset($options['directory_id'])) {
            $document['data']['relationships']['directory']['data']['type'] = 'directory';
            $document['data']['relationships']['directory']['data']['id'] = $options['directory_id'];
        }

        if (!empty($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        return $this->forStaff($staff_id)->patch('docs/' . $doc_id . '?target_type=file', $document);
    }

    public function deleteDoc($staff_id, $doc_id)
    {
        return $this->forStaff($staff_id)->delete('docs/' . $doc_id);
    }
    public function getDoc($id, $request = [])
    {
        return $this->get('docs/' . $id, $request);
    }
    public function postDirectory($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'directory',
                'attributes' => [
                    'name' => $attributes['name'],
                ],
                'relationships' => [
                    'team' => [
                        'data' => [
                            'type' => 'team',
                            'id' => $attributes['team_id']
                        ]
                    ]
                ],
            ]
        ];
        if (isset($options['parent_id'])) {
            $document['data']['relationships']['parent']['data']['type'] = 'directory';
            $document['data']['relationships']['parent']['data']['id'] = $options['parent_id'];
        }
        return $this->forStaff($staff_id)->post('directories', $document);
    }

    public function deleteDirectory($staff_id, $directory_id)
    {
        return $this->forStaff($staff_id)->delete('directories/' . $directory_id);
    }

    public function patchDirectory($staff_id, $directory_id, $options)
    {
        $document = [
            'data' => [
                'type' => 'directory',
            ]
        ];

        if (isset($options['name'])) {
            $document['data']['attributes']['name'] = $options['name'];
        }

        return $this->forStaff($staff_id)->patch('directories/' . $directory_id, $document);
    }

    public function moveDirectory($staff_id, $directory_id, $options)
    {
        $document = [
            'data' => [
                'type' => 'directory',
            ]
        ];

        if (isset($options['name'])) {
            $document['data']['attributes']['name'] = $options['name'];
        }

        if (isset($options['parent_id'])) {
            $document['data']['relationships']['parent']['data']['type'] = 'directory';
            $document['data']['relationships']['parent']['data']['id'] = $options['parent_id'];
        }

        $path = 'directories/'. $directory_id . '/move';
        return $this->forStaff($staff_id)->patch((string)$path, $document);
    }
}

