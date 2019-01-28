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
}