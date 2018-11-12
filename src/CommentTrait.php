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
		if (isset($options['target_type']) && isset($options['target_id'])) {
			$document['data']['relationships']['target']['data'] = [
				'type' => $options['target_type'],
				'id'   => $options['target_id'],
			];
		}
		return $this->forStaff($staff_id)->post('comments', $document);
	}
}
