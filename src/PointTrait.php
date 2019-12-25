<?php

namespace Lexiangla\Openapi;

Trait PointTrait
{
    function postPoint($staff_id, $attributes, $options = [])
    {
        $point = [
            'data' => [
                'type' => 'point',
                'attributes' => [
                    'op_type' => $attributes['op_type'],
                    'point' => $attributes['point'],
                    'reason' => $attributes['reason'],
                    'is_notify' => $attributes['is_notify'],
                ],
                'relationships' => [
                    'recipient' => [
                        'data' => [
                            'type' => 'staff',
                            'id' => $attributes['staff_id'],
                        ]
                    ]
                ]
            ]
        ];

        return $this->forStaff($staff_id)->post('points', $point);
    }

    function getPoint($request = [])
    {
        return $this->get('points', $request);
    }
}
