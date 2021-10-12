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
        if (isset($options["is_anonymous"])) {
            $document['data']['attributes']['is_anonymous'] = $options["is_anonymous"];
        }
        /*foreach ($options as $k => $v) {
            $document['data']['attributes'][$k] = $v;
        }*/
        return $this->forStaff($staff_id)->post('threads', $document);
    }

    public function putThread($staff_id, $id, $options)
    {
        if (isset($options["content"])) {
            $document["data"]["attributes"]["content"] = $options["content"];
        }
        if (isset($options["title"])) {
            $document["data"]["attributes"]["title"] = $options["title"];
        }
        if (isset($options["category_id"])) {
            $document["data"]["relationships"]["category"]["data"]["type"] = "category";
            $document["data"]["relationships"]["category"]["data"]["id"] = $options["category_id"];
        }

        return $this->forStaff($staff_id)->put('threads/' . $id, $document);
    }

    public function getThread($id, $request = [])
    {
        return $this->get('threads/' . $id, $request);
    }

    public function getThreadPost($id, $request = [])
    {
        return $this->get('threads/' . $id . '/posts', $request);
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

    public function putThreadPost($staff_id, $thread_id, $post_id, $options)
    {
        if (isset($options["content"])) {
            $document["data"]["attributes"]["content"] = $options["content"];
        }
        return $this->forStaff($staff_id)->put('/threads/' . $thread_id . '/posts/' . $post_id, $document);
    }

    public function deleteThreadPost($staff_id, $thread_id, $post_id)
    {
        return $this->forStaff($staff_id)->delete('/threads/' . $thread_id . '/posts/' . $post_id);
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