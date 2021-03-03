<?php

namespace Lexiangla\Openapi;

Trait CommentTrait
{
	function postComment($staff_id, $attributes, $options = [])
	{
		$document = [
			'data' => [
				'type'       => 'category',
				'attributes' => [
					'content' => $attributes['content'],
				]
			]
		];
        $document['data']['relationships']['target']['data'] = [
            'type' => isset($attributes['target_type']) ? $attributes['target_type'] : "",
            'id'   => isset($attributes['target_id']) ? $attributes['target_id'] : "",
        ];

		if (isset($options['target_type']) && isset($options['target_id'])) {
			$document['data']['relationships']['target']['data'] = [
				'type' => $options['target_ty`pe'],
				'id'   => $options['target_id'],
			];
		}
		return $this->forStaff($staff_id)->post('comments', $document);
	}
}
