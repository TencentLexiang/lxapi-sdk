<?php
namespace Lexiangla\Openapi;

Trait QuestionTrait
{
    public function postQuestion($staff_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'question',
                'attributes' => [
                    'title' => $attributes['title'],
                    'content' => $attributes['content'],
                    'is_anonymous' => $attributes['is_anonymous'],
                    'tags' => $attributes['tags'],
                ]
            ]
        ];
        foreach ($options as $k => $v) {
            $document['data']['attributes'][$k] = $v;
        }
        return $this->forStaff($staff_id)->post('questions', $document);
    }

    public function patchQuestion($staff_id, $question_id, $attributes)
    {
        $document = [
            'data' => [
                'type' => 'question',
            ]
        ];
        foreach ($attributes as $k => $v) {
            $document['data']['attributes'][$k] = $v;
        }
        return $this->forStaff($staff_id)->patch('questions/' . $question_id, $document);
    }

    public function deleteQuestion($staff_id, $question_id)
    {
        return $this->forStaff($staff_id)->delete('questions/' . $question_id);
    }

    public function getQuestion($id, $request = [])
    {
        return $this->get('questions/' . $id, $request);
    }

    public function postQuestionAnswer($staff_id, $question_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'answer',
                'attributes' => [
                    'content' => $attributes['content'],
                    'is_anonymous' => $attributes['is_anonymous'],
                ]
            ]
        ];
        foreach ($options as $k => $v) {
            $document['data']['attributes'][$k] = $v;
        }
        return $this->forStaff($staff_id)->post('questions/' . $question_id . '/answers', $document);
    }

    public function patchQuestionAnswer($staff_id, $question_id, $answer_id, $attributes, $options = [])
    {
        $document = [
            'data' => [
                'type' => 'answer',
            ]
        ];
        foreach ($attributes as $k => $v) {
            $document['data']['attributes'][$k] = $v;
        }
        return $this->forStaff($staff_id)->patch('questions/' . $question_id . '/answers/' . $answer_id, $document);
    }

    public function deleteQuestionAnswer($staff_id, $question_id, $answer_id)
    {
        return $this->forStaff($staff_id)->delete('questions/' . $question_id . '/answers/' . $answer_id);
    }

    public function postQuestionConcerns($question_id, $staff_id)
    {
        $document = [
            'data'=> [
                [
                    'type' => 'staff',
                    'id' => $staff_id,
                ]
            ]
        ];

        return $this->post('questions/' . $question_id . '/concerns', $document);
    }

}