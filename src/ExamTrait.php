<?php


namespace Lexiangla\Openapi;


Trait ExamTrait
{
    public function postExam($staff_id, $attributes, $options = [])
    {
        $document["data"]["type"] = "exam";
        $document["data"]["attributes"]["title"] = $attributes["title"];
        $document["data"]["attributes"]["privilege_type"] = $attributes["privilege_type"];
        $document["data"]["attributes"]["started_at"] = $attributes["started_at"];
        $document["data"]["attributes"]["ended_at"] = $attributes["ended_at"];
        $document["data"]["relationships"]["paper"]["data"]["type"] = "exam_paper";
        $document["data"]["relationships"]["paper"]["data"]["id"] = $attributes["relationships"]["paper"];
        //选填---
        if (isset($options['content'])) {
            $document["data"]["attributes"]["content"] = $options["content"];
        }
        if (isset($options['pass_score'])) {
            $document["data"]["attributes"]["pass_score"] = $options["pass_score"];
        }
        if (isset($options['duration'])) {
            $document["data"]["attributes"]["duration"] = $options["duration"];
        }
        if (isset($options['finished_privilege'])) {
            $document["data"]["attributes"]["finished_privilege"] = $options["finished_privilege"];
        }
        if (isset($options['ended_privilege'])) {
            $document["data"]["attributes"]["ended_privilege"] = $options["ended_privilege"];
        }
        if (isset($options['is_shuffled'])) {
            $document["data"]["attributes"]["is_shuffled"] = $options["is_shuffled"];
        }
        if (isset($options['tips_when_start'])) {
            $document["data"]["attributes"]["tips_when_start"] = $options["tips_when_start"];
        }
        if (isset($options['tips_before_start'])) {
            $document["data"]["attributes"]["tips_before_start"] = $options["tips_before_start"];
        }
        if (isset($options['tips_before_end'])) {
            $document["data"]["attributes"]["tips_before_end"] = $options["tips_before_end"];
        }
        if (isset($options['exam_times_limit'])) {
            $document["data"]["attributes"]["exam_times_limit"] = $options["exam_times_limit"];
        }
        if (isset($options['limit_switch_count'])) {
            $document["data"]["attributes"]["limit_switch_count"] = $options["limit_switch_count"];
        }
        if (isset($options['enable_face_recognition'])) {
            $document["data"]["attributes"]["enable_face_recognition"] = $options["enable_face_recognition"];
        }
        if (isset($options['notify_when_issue_certificate'])) {
            $document["data"]["attributes"]["notify_when_issue_certificate"] = $options["notify_when_issue_certificate"];
        }
        if (isset($options['point_plus'])) {
            $document["data"]["attributes"]["point_plus"] = $options["point_plus"];
        }
        if (isset($options['managers'])) {
            foreach ($options['managers'] as $managers) {
                $document["data"]["relationships"]["managers"]["data"][] = [
                    'type' => 'staff',
                    'id' => $managers
                ];
            }
        }

        if (isset($options['team'])) {
            $document["data"]["relationships"]["team"]["data"]["type"] = "team";
            $document["data"]["relationships"]["team"]["data"]["id"] = $options["team"];
        }
        if (isset($options['category'])) {
            $document["data"]["relationships"]["category"]["data"]["type"] = "category";
            $document["data"]["relationships"]["category"]["data"]["id"] = $options["category"];
        }
        if (isset($options['certificates'])) {
            foreach ($options["certificates"] as $certificates) {
                $document["data"]["relationships"]["certificates"]["data"][] = [
                    'type' => 'certificate',
                    'id' => $certificates
                ];
            }
        }
        if (isset($options['privileges'])) {
            $document["data"]["relationships"]["privileges"]["data"] = $options["privileges"];
        }


        return $this->forStaff($staff_id)->post('exams', $document);
    }

    public function patchExam($staff_id, $exam_id, $attributes, $options = [])
    {

        $document["data"]["type"] = "exam";
        $document["data"]["attributes"]["title"] = $attributes["title"];
        $document["data"]["attributes"]["started_at"] = $attributes["started_at"];
        $document["data"]["attributes"]["ended_at"] = $attributes["ended_at"];
        $document["data"]["relationships"]["paper"]["data"]["type"] = "exam_paper";
        $document["data"]["relationships"]["paper"]["data"]["id"] = $attributes["relationships"]["paper"];


        if (isset($options['privilege_type'])) {
            $document["data"]["attributes"]["privilege_type"] = $options["privilege_type"];
        }
        if (isset($options['content'])) {
            $document["data"]["attributes"]["content"] = $options["content"];
        }
        if (isset($options['pass_score'])) {
            $document["data"]["attributes"]["pass_score"] = $options["pass_score"];
        }
        if (isset($options['duration'])) {
            $document["data"]["attributes"]["duration"] = $options["duration"];
        }
        if (isset($options['finished_privilege'])) {
            $document["data"]["attributes"]["finished_privilege"] = $options["finished_privilege"];
        }
        if (isset($options['ended_privilege'])) {
            $document["data"]["attributes"]["ended_privilege"] = $options["ended_privilege"];
        }
        if (isset($options['is_shuffled'])) {
            $document["data"]["attributes"]["is_shuffled"] = $options["is_shuffled"];
        }
        if (isset($options['tips_when_start'])) {
            $document["data"]["attributes"]["tips_when_start"] = $options["tips_when_start"];
        }
        if (isset($options['tips_before_start'])) {
            $document["data"]["attributes"]["tips_before_start"] = $options["tips_before_start"];
        }
        if (isset($options['tips_before_end'])) {
            $document["data"]["attributes"]["tips_before_end"] = $options["tips_before_end"];
        }
        if (isset($options['exam_times_limit'])) {
            $document["data"]["attributes"]["exam_times_limit"] = $options["exam_times_limit"];
        }
        if (isset($options['limit_switch_count'])) {
            $document["data"]["attributes"]["limit_switch_count"] = $options["limit_switch_count"];
        }
        if (isset($options['enable_face_recognition'])) {
            $document["data"]["attributes"]["enable_face_recognition"] = $options["enable_face_recognition"];
        }
        if (isset($options['notify_when_issue_certificate'])) {
            $document["data"]["attributes"]["notify_when_issue_certificate"] = $options["notify_when_issue_certificate"];
        }
        if (isset($options['point_plus'])) {
            $document["data"]["attributes"]["point_plus"] = $options["point_plus"];
        }
        if (isset($options['managers'])) {
            foreach ($options['managers'] as $managers) {
                $document["data"]["relationships"]["managers"]["data"][] = [
                    'type' => 'staff',
                    'id' => $managers
                ];
            }
        }

        if (isset($options['team'])) {
            $document["data"]["relationships"]["team"]["data"]["type"] = "team";
            $document["data"]["relationships"]["team"]["data"]["id"] = $options["team"];
        }
        if (isset($options['category'])) {
            $document["data"]["relationships"]["category"]["data"]["type"] = "category";
            $document["data"]["relationships"]["category"]["data"]["id"] = $options["category"];
        }
        if (isset($options['certificates'])) {
            foreach ($options["certificates"] as $certificates) {
                $document["data"]["relationships"]["certificates"]["data"][] = [
                    'type' => 'certificate',
                    'id' => $certificates
                ];
            }
        }
        if (isset($options['privileges'])) {
            $document["data"]["relationships"]["privileges"]["data"] = $options["privileges"];
        }
        return $this->forStaff($staff_id)->patch('exams/' . $exam_id, $document);
    }

    public function deleteExam($staff_id, $exam_id)
    {
        return $this->forStaff($staff_id)->delete('exams/' . $exam_id);
    }

    public function postExamPaper($staff_id, $attributes, $options = [])
    {
        $document["data"]["attributes"]["title"] = $attributes["title"];
        $document["data"]["attributes"]["type"] = $attributes["type"];
        if ($attributes["type"] == "question_lib") { //随机卷选题
            $document["data"]["attributes"]["extra_content"]["select_type"] = $attributes["select_type"];
            $document["data"]["attributes"]["extra_content"]["select_data"] = $attributes["select_data"];
        }
        if (isset($options["is_shared"])) {
            $document["data"]["attributes"]["is_shared"] = $options["is_shared"];
        }
        if (isset($options["team"])) {
            $document["data"]["relationships"]["team"]["data"]["type"] = "team";
            $document["data"]["relationships"]["team"]["data"]["id"] = $options["team"];
        }

        return $this->forStaff($staff_id)->post('exam-papers', $document);
    }

    public function patchExamPaper($staff_id, $papers_id, $options = [])
    {
        if (isset($options["title"])) {
            $document["data"]["attributes"]["title"] = $options["title"];
        }
        if (isset($options["extra_content"])) {
            $document["data"]["attributes"]["extra_content"] = $options["extra_content"];
        }
        if (isset($options["type"])) {
            $document["data"]["attributes"]["type"] = $options["type"];
        }
        if (isset($options["is_shared"])) {
            $document["data"]["attributes"]["is_shared"] = $options["is_shared"];
        }
        return $this->forStaff($staff_id)->patch('exam-papers/' . $papers_id, $document);
    }

    public function patchExamPaperQuestionlib($staff_id, $papers_id, $attributes)
    {

        for ($x = 0; $x < count($attributes); $x++) {
            $document["data"][] = [
                "type" => "exam_question_lib",
                "id" => $attributes[$x]
            ];

        }

        return $this->forStaff($staff_id)->patch("exam-papers/" . $papers_id . "/question_libs", $document);
    }

    public function patchExamPaperQuestion($staff_id, $papers_id, $attributes)
    {
        for ($x = 0; $x < count($attributes); $x++) {
            $document["data"][] = [
                "type" => "exam_question",
                "id" => $attributes[$x]["id"],
                "attributes" => [
                    "extra_content" => [
                        "score" => $attributes[$x]["score"]
                    ]
                ]

            ];

        }

        return $this->forStaff($staff_id)->patch("exam-papers/" . $papers_id . "/questions", $document);
    }

    public function deleteExamPaper($staff_id, $papers_id)
    {
        return $this->forStaff($staff_id)->delete("exam-papers/" . $papers_id);
    }

    public function psotExamQuestionLib($staff_id, $attributes)
    {
        $document["data"]["type"] = "question_lib";
        $document["data"]["attributes"]["name"] = $attributes["name"];
        $document["data"]["attributes"]["is_shared"] = $attributes["is_shared"];
        return $this->forStaff($staff_id)->post("exams/question-libs", $document);
    }

    public function patchExamQuestionLib($staff_id, $id, $attributes)
    {
        $document["data"]["type"] = "question_lib";
        $document["data"]["attributes"]["name"] = $attributes["name"];
        $document["data"]["attributes"]["is_shared"] = $attributes["is_shared"];
        return $this->forStaff($staff_id)->patch("exams/question-libs/" . $id, $document);
    }

    public function deleteExamQuestionLib($staff_id, $question_lib_id)
    {
        return $this->forStaff($staff_id)->delete("exams/question-libs/" . $question_lib_id);
    }

    public function postExamQuestion($staff_id, $question_lib_id, $attributes)
    {
        $document = [
            "data" => [
                "type" => "exam_question",
                "attributes" => $attributes

            ]
        ];
        return $this->forStaff($staff_id)->post("exams/question-libs/" . $question_lib_id . "/questions", $document);
    }

    public function patchExamQuestion($staff_id, $question_lib_id, $question_id, $attributes)
    {
        $document = [
            "data" => [
                "type" => "exam_question",
                "attributes" => $attributes

            ]
        ];
        return $this->forStaff($staff_id)->patch("exams/question-libs/" . $question_lib_id . "/questions/" . $question_id, $document);
    }

    public function deleteExamQuestion($staff_id, $question_lib_id, $question_id)
    {
        return $this->forStaff($staff_id)->delete("exams/question-libs/" . $question_lib_id . "/questions/" . $question_id);
    }
}