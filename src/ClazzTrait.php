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
        }
        if (!empty($options['chapters'])) {
            $relationships['chapters']['data'] = $options['chapters'];
        }
        if (!empty($options['category_id'])) {
            $relationships['category']['data'] = [
                'type' => 'category',
                'id' => $options['category_id'],
            ];
        }
        if (!empty($options['privileges'])) {
            $relationships['privileges']['data'] = $options['privileges'];
        }

        echo json_encode($document) . PHP_EOL;
        return $this->forStaff($staff_id)->post('classes', $document);
    }
}
