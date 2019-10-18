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
        if (isset($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        if (isset($options['source'])) {
            $document['data']['attributes']['source'] = $options['source'];
        }
        if (isset($options['reship_url'])) {
            $document['data']['attributes']['reship_url'] = $options['reship_url'];
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

    public function postFile($staff_id, $file_path,  $attributes, $options = [])
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
        $file = [
            'data' => [
                'type' => 'file',
                'attributes' => [
                    'team_id'      => isset($attributes['team_id']) ? $attributes['team_id'] : "",
                    'downloadable'  => $attributes['downloadable'],
                    'picture_url'   => isset($attributes['picture_url']) ? $attributes['picture_url'] : "",
                ]
            ],
            'state' => $state
        ];

        $file['data']['relationships']['category']['data']['type'] = 'category';
        $file['data']['relationships']['category']['data']['id'] = $options['category_id'];
        return $this->forStaff($staff_id)->post('files', $file);
    }

    private function getDocCOSParam($file_name, $type)
    {
        $data = [
            'filename' => $file_name,
            'type'      => $type
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

    public function patchDoc($staff_id, $doc_id, $options)
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
        if (isset($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        if (isset($options['source'])) {
            $document['data']['attributes']['source'] = $options['source'];
        }
        if (isset($options['reship_url'])) {
            $document['data']['attributes']['reship_url'] = $options['reship_url'];
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
        if (!empty($options['attachments'])) {
            foreach ($options['attachments'] as $attachment_id) {
                $document['data']['relationships']['attachments']['data'][] = [
                    'type' => 'attachment',
                    'id' => $attachment_id
                ];
            }
        }
        return $this->forStaff($staff_id)->patch('docs/' . $doc_id, $document);
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

        if (isset($options['title'])) {
            $document['data']['attributes']['title'] = $options['title'];
        }

        if (isset($options['content'])) {
            $document['data']['attributes']['content'] = $options['content'];
        }

        if (isset($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (isset($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        if (isset($options['source'])) {
            $document['data']['attributes']['source'] = $options['source'];
        }
        if (isset($options['reship_url'])) {
            $document['data']['attributes']['reship_url'] = $options['reship_url'];
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
        if (!empty($options['attachments'])) {
            foreach ($options['attachments'] as $attachment_id) {
                $document['data']['relationships']['attachments']['data'][] = [
                    'type' => 'attachment',
                    'id' => $attachment_id
                ];
            }
        }
        return $this->forStaff($staff_id)->patch('directories/' . $directory_id, $document);
    }
}
