<?php
namespace Lexiangla\Openapi;

Trait EventTrait
{
    //创建活动
    public function postEvent($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'attributes' => [
                    'title' => $attributes['title'],
                    'location' => $attributes['location'],
                    'started_at' => $attributes['started_at'],
                    'ended_at' => $attributes['ended_at']
                ]
            ]
        ];
        if (!empty($options['content'])) {
            $document['data']['attributes']['content'] = $options['content'];
        }
        if (!empty($options['closed_at'])) {
            $document['data']['attributes']['closed_at'] = $options['closed_at'];
        }
        if (!empty($options['attend_start_at'])) {
            $document['data']['attributes']['attend_start_at'] = $options['attend_start_at'];
        }
        if (!empty($options['attend_end_at'])) {
            $document['data']['attributes']['attend_end_at'] = $options['attend_end_at'];
        }
        if (!empty($options['participant_count_limit'])) {
            $document['data']['attributes']['participant_count_limit'] = $options['participant_count_limit'];
        }
        if (!empty($options['enable_random_extract'])) {
            $document['data']['attributes']['enable_random_extract'] = $options['enable_random_extract'];
        }
        if (!empty($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (!empty($options['logo'])) {
            $document['data']['attributes']['logo'] = $options['logo'];
        }
        if (!empty($options['team_id'])) {
            $document['data']['relationships']['team']['data'] = [
                'type' => 'team',
                'id' => $options['team_id']
            ];
        }
        if (!empty($options['category_id'])) {
            $document['data']['relationships']['category']['data'] = [
                'type' => 'category_id',
                'id' => $options['category_id']
            ];
        }
        if (!empty($options['privilege'])) {
            $document['data']['relationships']['privilege']['data'] = $options['privilege'];
        }
        return $this->forStaff($staff_id)->post('events', $document);
    }
    //编辑活动
    public function putEvent($staff_id, $events_id, $attributes, $options = [])
    {
        if (!empty($options['title'])) {
            $document['data']['attributes']['title'] = $options['title'];
        }
        if (!empty($options['location'])) {
            $document['data']['attributes']['location'] = $options['location'];
        }
        if (!empty($options['started_at'])) {
            $document['data']['attributes']['started_at'] = $options['started_at'];
        }
        if (!empty($options['ended_at'])) {
            $document['data']['attributes']['ended_at'] = $options['ended_at'];
        }
        if (!empty($options['content'])) {
            $document['data']['attributes']['content'] = $options['content'];
        }
        if (!empty($options['closed_at'])) {
            $document['data']['attributes']['closed_at'] = $options['closed_at'];
        }
        if (!empty($options['attend_start_at'])) {
            $document['data']['attributes']['attend_start_at'] = $options['attend_start_at'];
        }
        if (!empty($options['attend_end_at'])) {
            $document['data']['attributes']['attend_end_at'] = $options['attend_end_at'];
        }
        if (!empty($options['participant_count_limit'])) {
            $document['data']['attributes']['participant_count_limit'] = $options['participant_count_limit'];
        }
        if (!empty($options['enable_random_extract'])) {
            $document['data']['attributes']['enable_random_extract'] = $options['enable_random_extract'];
        }
        if (!empty($options['privilege_type'])) {
            $document['data']['attributes']['privilege_type'] = $options['privilege_type'];
        }
        if (!empty($options['logo'])) {
            $document['data']['attributes']['logo'] = $options['logo'];
        }
        if (!empty($options['team_id'])) {   
            $document['data']['relationships']['team']['data'] = [
                'type' => 'team',
                'id' => $options['team_id']
            ];
        }else if(array_key_exists('team_id',$options))
        {
            $document['data']['relationships']['team'] = null;
        }
        if (!empty($options['category_id'])) {
            $document['data']['relationships']['category']['data'] = [
                'type' => 'category_id',
                'id' => $options['category_id']
            ];
        }
        if (!empty($options['privilege'])) {
            $document['data']['relationships']['privilege']['data'] = $options['privilege'];
        }
        return $this->forStaff($staff_id)->put('events/' . $events_id, $document);
    }
}