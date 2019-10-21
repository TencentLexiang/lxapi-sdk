<?php

namespace Lexiangla\Openapi;

Trait TeamTrait
{
    function postTeam($staff_id, $attributes, $options = [])
    {
        $team = [
            'data' => [
                'type' => 'team',
                'attributes' => [
                    'name' => $attributes['name']
                ]
            ]
        ];

        if (isset($options['type'])) {
            $team['data']['attributes']['type'] = $options['type'];
        }
        if (isset($options['signature'])) {
            $team['data']['attributes']['signature'] = $options['signature'];
        }
        if (isset($options['is_secret'])) {
            $team['data']['attributes']['is_secret'] = $options['is_secret'];
        }

        if (isset($options['orgs'])) {
            foreach ($options['orgs'] as $privilege) {
                $team['data']['relationships']['orgs']['data'][] = $privilege;
            }
        }

        return $this->forStaff($staff_id)->post('teams', $team);
    }

    function patchTeam($staff_id, $team_id, $options = [])
    {
        $team = [
            'data' => [
                'type' => 'team'
            ]
        ];

        if (isset($options['name'])) {
            $team['data']['attributes']['name'] = $options['name'];
        }
        if (isset($options['signature'])) {
            $team['data']['attributes']['signature'] = $options['signature'];
        }
        if (isset($options['is_secret'])) {
            $team['data']['attributes']['is_secret'] = $options['is_secret'];
        }

        if (isset($options['orgs'])) {
            foreach ($options['orgs'] as $privilege) {
                $team['data']['relationships']['orgs']['data'][] = $privilege;
            }
        }

        return $this->forStaff($staff_id)->patch('teams/' . $team_id, $team);
    }

    function hideTeam($staff_id, $team_id)
    {
        return $this->forStaff($staff_id)->delete('teams/' . $team_id . '/hide');
    }

}