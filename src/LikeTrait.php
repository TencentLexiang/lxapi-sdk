<?php

namespace Lexiangla\Openapi;

Trait LikeTrait
{
    function postLike($staff_id, $attributes)
    {
        $document = [
            'data' => [
                'type' => 'like',
            ]
        ];
        $document['data']['relationships']['target']['data'] = [
            'type' => $attributes['target_type'],
            'id'   => $attributes['target_id'],
        ];
        return $this->forStaff($staff_id)->post('likes', $document);
    }
}
