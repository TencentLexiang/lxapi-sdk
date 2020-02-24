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
                    'started_at' => $attributes['started_at'],
                    'intro' => '',
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
}
