<?php


namespace Lexiangla\Openapi;


Trait ExamTrait
{
    //创建考试
    public function postExam($staff_id, $attributes, $options = [])
    {
        $document["data"]["type"] = "exam";
        $document["data"]["attributes"]["title"] = $attributes["title"];
        $document["data"]["attributes"]["privilege_type"] = $attributes["privilege_type"];
        $document["data"]["attributes"]["started_at"] = $attributes["started_at"];
        $document["data"]["attributes"]["ended_at"] = $attributes["ended_at"];
        $document["data"]["relationships"]["paper"]["data"]["type"] = "exam_paper";
        $document["data"]["relationships"]["paper"]["data"]["id"] = $attributes["paper_id"];
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
        if (isset($options['managers_ids'])) {
            foreach ($options['managers_ids'] as $managers) {
                $document["data"]["relationships"]["managers"]["data"][] = [
                    'type' => 'staff',
                    'id' => $managers
                ];
            }
        }

        if (isset($options['team'])) {
            $document["data"]["relationships"]["team"]["data"]["type"] = "team";
            $document["data"]["relationships"]["team"]["data"]["id"] = $options["team_id"];
        }
        if (isset($options['category_id'])) {
            $document["data"]["relationships"]["category"]["data"]["type"] = "category";
            $document["data"]["relationships"]["category"]["data"]["id"] = $options["category_id"];
        }
        if (isset($options['certificates_ids'])) {
            foreach ($options["certificates_ids"] as $certificates) {
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

    //发布考试
    public function putExamPublish($staff_id, $exam_id)
    {
        return $this->forStaff($staff_id)->put('exams/' . $exam_id . "/publish",[]);
    }
    //取消发布
    public function putExamCancelPublish($staff_id, $exam_id)
    {
        return $this->forStaff($staff_id)->put('exams/' . $exam_id . "/cancel-publish",[]);
    }

    //编辑考试
    public function patchExam($staff_id, $exam_id, $attributes, $options = [])
    {

        $document["data"]["type"] = "exam";
        $document["data"]["attributes"] = [];
        $document["data"]["relationships"]["paper"]["data"]["type"] = "exam_paper";
        $document["data"]["relationships"]["paper"]["data"]["id"] = $attributes["paper_id"];

        if (isset($options["title"])) {
            $document["data"]["attributes"]["title"] = $options["title"];
        }
        if (isset($options['started_at'])) {
            $document["data"]["attributes"]["started_at"] = $options["started_at"];
        }
        if (isset($options['ended_at'])) {
            $document["data"]["attributes"]["ended_at"] = $options["ended_at"];
        }
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
        if (isset($options['managers_ids'])) {
            foreach ($options['managers_ids'] as $managers) {
                $document["data"]["relationships"]["managers"]["data"][] = [
                    'type' => 'staff',
                    'id' => $managers
                ];
            }
        }

        if (isset($options['team_id'])) {
            $document["data"]["relationships"]["team"]["data"]["type"] = "team";
            $document["data"]["relationships"]["team"]["data"]["id"] = $options["team_id"];
        }
        if (isset($options['category_id'])) {
            $document["data"]["relationships"]["category"]["data"]["type"] = "category";
            $document["data"]["relationships"]["category"]["data"]["id"] = $options["category_id"];
        }
        if (isset($options['certificates_ids'])) {
            foreach ($options["certificates_ids"] as $certificates_ids) {
                $document["data"]["relationships"]["certificates"]["data"][] = [
                    'type' => 'certificate',
                    'id' => $certificates_ids
                ];
            }
        }
        if (isset($options['privileges'])) {
            $document["data"]["relationships"]["privileges"]["data"] = $options["privileges"];
        }
        return $this->forStaff($staff_id)->patch('exams/' . $exam_id, $document);
    }

    // 删除考试
    public function deleteExam($staff_id, $exam_id)
    {
        return $this->forStaff($staff_id)->delete('exams/' . $exam_id);
    }

    //考试试卷
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
        if (isset($options["team_id"])) {
            $document["data"]["relationships"]["team"]["data"]["type"] = "team";
            $document["data"]["relationships"]["team"]["data"]["id"] = $options["team_id"];
        }

        return $this->forStaff($staff_id)->post('exam-papers', $document);
    }

    //修改试卷
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

    //设置随机试卷题目
    public function patchExamPaperQuestionlib($staff_id, $attributes, $papers_id)
    {

        for ($x = 0; $x < count($attributes["exam_question_libP_ids"]); $x++) {
            $document["data"][] = [
                "type" => "exam_question_lib",
                "id" => $attributes["exam_question_libP_ids"][$x]
            ];

        }

        return $this->forStaff($staff_id)->patch("exam-papers/" . $papers_id . "/question_libs", $document);
    }

    //设置固定试卷
    public function patchExamPaperQuestion($staff_id, $attributes, $papers_id)
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

    //删除试卷
    public function deleteExamPaper($staff_id, $papers_id)
    {
        return $this->forStaff($staff_id)->delete("exam-papers/" . $papers_id);
    }

    //创建题库
    public function psotExamQuestionLib($staff_id, $attributes)
    {
        $document["data"]["type"] = "question_lib";
        $document["data"]["attributes"]["name"] = $attributes["name"];
        $document["data"]["attributes"]["is_shared"] = $attributes["is_shared"];
        return $this->forStaff($staff_id)->post("exams/question-libs", $document);
    }

    //更新题库
    public function patchExamQuestionLib($staff_id, $id, $attributes)
    {
        $document["data"]["type"] = "question_lib";
        $document["data"]["attributes"]["name"] = $attributes["name"];
        $document["data"]["attributes"]["is_shared"] = $attributes["is_shared"];
        return $this->forStaff($staff_id)->patch("exams/question-libs/" . $id, $document);
    }

    //删除题库
    public function deleteExamQuestionLib($staff_id, $question_lib_id)
    {
        return $this->forStaff($staff_id)->delete("exams/question-libs/" . $question_lib_id);
    }

    //创建题目
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

    //更新题目
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

    //删除题目
    public function deleteExamQuestion($staff_id, $question_lib_id, $question_id)
    {
        return $this->forStaff($staff_id)->delete("exams/question-libs/" . $question_lib_id . "/questions/" . $question_id);
    }
}