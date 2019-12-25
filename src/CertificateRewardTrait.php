<?php

namespace Lexiangla\Openapi;

Trait CertificateRewardTrait
{
    function postCertificateReward($staff_id, $attributes, $options = [])
    {
        $certificate_reward = [
            'data' => [
                'type' => 'certificate_reward',
                'attributes' => [
                    'is_notify' => $attributes['is_notify'],
                ],
                'relationships' => [
                    'recipient' => [
                        'data' => [
                            'type' => 'staff',
                            'id' => $attributes['staff_id'],
                        ]
                    ],
                    'certificate' => [
                        'data' => [
                            'type' => 'certificate',
                            'id' => $attributes['cer_id'],
                        ]
                    ]
                ]
            ]
        ];

        return $this->forStaff($staff_id)->post('certificates/rewards', $certificate_reward);
    }
}
