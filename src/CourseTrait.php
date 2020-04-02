<?php


namespace Lexiangla\Openapi;


trait CourseTrait
{
    // 创建素材
    public function postCourse($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'course',
                'attributes' => [
                    'title' => $attributes['title'],
                    'content' => $attributes['content'],
                ],
            ]
        ];

        if (isset($options['media_type'])) {
            $document['data']['attributes']['media_type'] = $options['media_type'];
        }
        if (isset($options['target_users'])) {
            $document['data']['attributes']['target_users'] = $options['target_users'];
        }
        if (isset($options['duration'])) {
            $document['data']['attributes']['duration'] = $options['duration'];
        }
        if (isset($options['complete_percent'])) {
            $document['data']['attributes']['complete_percent'] = $options['complete_percent'];
        }

        if (isset($options['category_id'])) {
            $document['data']['relationships']['category']['data']['type'] = 'category';
            $document['data']['relationships']['category']['data']['id'] = $options['category_id'];
        }
        if (isset($options['team_id'])) {
            $document['data']['relationships']['team']['data']['type'] = 'team';
            $document['data']['relationships']['team']['data']['id'] = $options['team_id'];
        }

        if (isset($options['attachment_id'])) {
            $document['data']['relationships']['attachment']['data']['type'] = 'attachment';
            $document['data']['relationships']['attachment']['data']['id'] = $options['attachment_id'];
        }

        if (!empty($options['videos'])) {
            foreach ($options['videos'] as $video_id) {
                $document['data']['relationships']['videos']['data'][] = [
                    'type' => 'video',
                    'id' => $video_id
                ];
            }
        }

        if (!empty($options['attachments'])) {
            foreach ($options['attachments'] as $attachment_id) {
                $document['data']['relationships']['attachments']['data'][] = [
                    'type' => 'attachment',
                    'id' => $attachment_id
                ];
            }
        }
        return $this->forStaff($staff_id)->post('courses', $document);
    }

    public function patchCourse($staff_id, $course_id, $options)
    {
        $document = [
            'data' => [
                'type' => 'course',
            ]
        ];
        if (isset($options['title'])) {
            $document['data']['attributes']['title'] = $options['title'];
        }
        if (isset($options['content'])) {
            $document['data']['attributes']['content'] = $options['content'];
        }

        if (isset($options['media_type'])) {
            $document['data']['attributes']['media_type'] = $options['media_type'];
        }
        if (isset($options['target_users'])) {
            $document['data']['attributes']['target_users'] = $options['target_users'];
        }
        if (isset($options['duration'])) {
            $document['data']['attributes']['duration'] = $options['duration'];
        }
        if (isset($options['complete_percent'])) {
            $document['data']['attributes']['complete_percent'] = $options['complete_percent'];
        }

        if (isset($options['category_id'])) {
            $document['data']['relationships']['category']['data']['type'] = 'category';
            $document['data']['relationships']['category']['data']['id'] = $options['category_id'];
        }
        if (isset($options['team_id'])) {
            $document['data']['relationships']['team']['data']['type'] = 'team';
            $document['data']['relationships']['team']['data']['id'] = $options['team_id'];
        }

        if (isset($options['attachment_id'])) {
            $document['data']['relationships']['attachment']['data']['type'] = 'attachment';
            $document['data']['relationships']['attachment']['data']['id'] = $options['attachment_id'];
        }

        if (!empty($options['videos'])) {
            foreach ($options['videos'] as $video_id) {
                $document['data']['relationships']['videos']['data'][] = [
                    'type' => 'video',
                    'id' => $video_id
                ];
            }
        }

        if (!empty($options['attachments'])) {
            foreach ($options['attachments'] as $attachment_id) {
                $document['data']['relationships']['attachments']['data'][] = [
                    'type' => 'attachment',
                    'id' => $attachment_id
                ];
            }
        }
        return $this->forStaff($staff_id)->patch('courses/' . $course_id, $document);
    }

    public function deleteCourse($staff_id, $course_id)
    {
        return $this->forStaff($staff_id)->delete('courses/' . $course_id);
    }

}