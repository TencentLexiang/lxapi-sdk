<?php
namespace Lexiangla\Openapi;

Trait ThreadTrait
{
    public function postThread($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'thread',
                'attributes' => [
                    'title' => $attributes['title'],
                    'content' => $attributes['content'],
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'category',
                            'id' => $attributes['category_id']
                        ]
                    ],
                ]
            ]
        ];

        foreach ($options as $k => $v) {
            $document['data']['attributes'][$k] = $v;
        }
        return $this->forStaff($staff_id)->post('threads', $document);
    }

    public function getThread($id, $request = [])
    {
        return $this->get('threads/' . $id, $request);
    }

    public function getThreadPost($id, $request = [])
    {
        return $this->get('threads/' . $id. '/posts', $request);
    }

    public function deleteThread($staff_id, $thread_id)
    {
        return $this->forStaff($staff_id)->delete('threads/' . $thread_id);
    }

    public function postThreadPost($staff_id, $thread_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'post',
                'attributes' => [
                    'content' => $attributes['content'],
                ]
            ]
        ];
        if (isset($options['ref_id'])) {
            $document['data']['relationships']['reference'] = [
                'data' => [
                    'type' => 'post',
                    'id' => $options['ref_id']
                ]
            ];
        }
        foreach ($options as $k => $v) {
            $document['data']['attributes'][$k] = $v;
        }

        return $this->forStaff($staff_id)->post('threads/' . $thread_id . '/posts', $document);
    }

    public function postThreadConcerns($thread_id, $staffs)
    {
        $document = [];
        foreach ($staffs as $staff) {
            $document['data'][] = [
                'type' => 'staff',
                'id' => $staff['id'],
                'attributes' => [
                    'created_at' => $staff['created_at']
                ]
            ];
        }

        return $this->forStaff('system-bot')->post('threads/' . $thread_id . '/concerns', $document);
    }
}