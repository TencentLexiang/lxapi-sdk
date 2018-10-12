<?php
namespace Lexiangla\Openapi;

Trait CategoryTrait
{
    public function postCategory($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'category',
                'attributes' => [
                    'name' => $attributes['name'],
                    'target_type' => $attributes['target_type'],
                ]
            ]
        ];
        if (isset($options['weight'])) {
            $document['data']['attributes']['weight'] = $options['weight'];
        }
        if (isset($options['parent_id'])) {
            $document['data']['relationships']['parent']['data'] = [
                'type' => 'category',
                'id' => $options['parent_id'],
            ];
        }
        return $this->forStaff($staff_id)->post('categories', $document);
    }
}