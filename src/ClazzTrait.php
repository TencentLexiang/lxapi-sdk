<?php

namespace Lexiangla\Openapi;

Trait ClazzTrait
{
    public function postClazz($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'clazz',
                'attributes' => $attributes,
            ]
        ];
        $relationships = &$document['data']['relationships'];
        if (!empty($options['course_id'])) {
            $relationships['course']['data'] = [
                'type' => 'course',
                'id' => $options['course_id'],
            ];
            unset($options['course_id']);
        }
        if (!empty($options['chapters'])) {
            $relationships['chapters']['data'] = $options['chapters'];
            unset($options['chapters']);
        }
        if (!empty($options['category_id'])) {
            $relationships['category']['data'] = [
                'type' => 'category',
                'id' => $options['category_id'],
            ];
            unset($options['category_id']);
        }
        if (!empty($options['privileges'])) {
            $relationships['privileges']['data'] = $options['privileges'];
            unset($options['privileges']);
        }
        if (!empty($options['privilege'])) {
            $relationships['privilege']['data'] = $options['privilege'];
            unset($options['privilege']);
        }

        $document['data']['attributes'] += $options;

        echo json_encode($document) . PHP_EOL;
        return $this->forStaff($staff_id)->post('classes', $document);
    }
}
