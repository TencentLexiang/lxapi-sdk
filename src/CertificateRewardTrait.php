<?php

namespace Lexiangla\Openapi;

Trait CertificateRewardTrait
{
    function postCertificateReward($staff_id, $cer_id, $attributes, $options = [])
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
                    ]
                ]
            ]
        ];

        return $this->forStaff($staff_id)->post('certificates/' . $cer_id . '/rewards', $certificate_reward);
    }

    public function deleteCertificateReward($staff_id, $cer_id, $code)
    {
        return $this->forStaff($staff_id)->delete('certificates/' . $cer_id . '/rewards?code=' . $code);
    }
}
