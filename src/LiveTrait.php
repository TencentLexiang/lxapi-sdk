<?php

namespace Lexiangla\Openapi;

Trait LiveTrait
{
    function postLive($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'live',
                'attributes' => [
                    'title' => $attributes['title'],
                    'started_at' => $attributes['started_at']
                ],
            ]
        ];

        foreach ($attributes['streamer_ids'] as $streamer_id) {
            $document['data']['relationships']['streamer']['data'][] = [
                'type' => 'staff',
                'id' => $streamer_id
            ];
        }

        if (isset($options['intro'])) {
            $document['data']['attributes']['intro'] = $options['intro'];
        }

        if (isset($options['content'])) {
            $document['data']['attributes']['content'] = $options['content'];
        }
        if (isset($options['pic'])) {
            $document['data']['attributes']['pic'] = $options['pic'];
        }
        if (isset($options['push_method'])) {
            $document['data']['attributes']['push_method'] = $options['push_method'];
        }
        if (isset($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (isset($options['enable_gift'])) {
            $document['data']['attributes']['enable_gift'] = $options['enable_gift'];
        }
        if (isset($options['enable_record'])) {
            $document['data']['attributes']['enable_record'] = $options['enable_record'];
        }
        if (isset($options['enable_question'])) {
            $document['data']['attributes']['enable_question'] = $options['enable_question'];
        }
        if (isset($options['enable_comment'])) {
            $document['data']['attributes']['enable_comment'] = $options['enable_comment'];
        }
        if (isset($options['vertical'])) {
            $document['data']['attributes']['vertical'] = $options['vertical'];
        }
        if (isset($options['auto_transfer_class'])) {
            $document['data']['attributes']['auto_transfer_class'] = $options['auto_transfer_class'];
        }
        if (isset($options['live_type'])) {
            $document['data']['attributes']['live_type'] = $options['live_type'];
        }
        if (isset($options['password'])) {
            $document['data']['attributes']['password'] = $options['password'];
        }
        if (!empty($options['course_category_id'])) {
            $document['data']['relationships']['course_category']['data'] = [
                'type' => 'category',
                'id' => $options['course_category_id']
            ];
        }
        if (!empty($options['class_category_id'])) {
            $document['data']['relationships']['class_category']['data'] = [
                'type' => 'category',
                'id' => $options['class_category_id']
            ];
        }
        if (!empty($options['manager_ids'])) {
            foreach ($options['manager_ids'] as $manager_id) {
                $document['data']['relationships']['managers']['data'][] = [
                    'type' => 'staff',
                    'id' => $manager_id
                ];
            }
        }
        if (!empty($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        return $this->forStaff($staff_id)->post('lives', $document);
    }

    function patchLive($staff_id, $live_id, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'live'
            ]
        ];

        if (isset($options['title'])) {
            $document['data']['attributes']['title'] = $options['title'];
        }
        if (isset($options['started_at'])) {
            $document['data']['attributes']['started_at'] = $options['started_at'];
        }
        if (isset($options['intro'])) {
            $document['data']['attributes']['intro'] = $options['intro'];
        }

        if (isset($options['streamer_ids'])) {
            foreach ($options['streamer_ids'] as $streamer_id) {
                $document['data']['relationships']['streamer']['data'][] = [
                    'type' => 'staff',
                    'id' => $streamer_id
                ];
            }
        }
        if (isset($options['content'])) {
            $document['data']['attributes']['content'] = $options['content'];
        }
        if (isset($options['pic'])) {
            $document['data']['attributes']['pic'] = $options['pic'];
        }
        if (isset($options['push_method'])) {
            $document['data']['attributes']['push_method'] = $options['push_method'];
        }
        if (isset($options['live_type'])) {
            $document['data']['attributes']['live_type'] = $options['live_type'];
        }
        if (isset($options['vertical'])) {
            $document['data']['attributes']['vertical'] = $options['vertical'];
        }
        if (isset($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (isset($options['enable_question'])) {
            $document['data']['attributes']['enable_question'] = $options['enable_question'];
        }
        if (isset($options['enable_comment'])) {
            $document['data']['attributes']['enable_comment'] = $options['enable_comment'];
        }
        if (isset($options['enable_gift'])) {
            $document['data']['attributes']['enable_gift'] = $options['enable_gift'];
        }
        if (isset($options['enable_record'])) {
            $document['data']['attributes']['enable_record'] = $options['enable_record'];
        }
        if (isset($options['status'])) {
            $document['data']['attributes']['status'] = $options['status'];
        }
        if (!empty($options['manager_ids'])) {
            foreach ($options['manager_ids'] as $manager_id) {
                $document['data']['relationships']['managers']['data'][] = [
                    'type' => 'staff',
                    'id' => $manager_id
                ];
            }
        }
        if (!empty($options['privilege'])) {
            foreach ($options['privilege'] as $privilege) {
                $document['data']['relationships']['privilege']['data'][] = $privilege;
            }
        }
        return $this->forStaff($staff_id)->patch('lives/' . $live_id, $document);
    }

    public function deleteLive($staff_id, $live_id)
    {
        return $this->forStaff($staff_id)->delete('lives/' . $live_id);
    }
}
