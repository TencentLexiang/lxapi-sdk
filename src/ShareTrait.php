<?php

namespace Lexiangla\Openapi;
trait ShareTrait
{
    //新建轻享
    public function postShare($staff_id, $options)
    {
        if (empty($options['content']) && empty($options['media_type'])) {
            throw new \Exception('不允许content和media_type同时为空');
        }
        if (isset($options["content"])) {
            $document["data"]["attributes"]["content"] = $options["content"];
        }
        if (isset($options["media_type"])) {
            $document["data"]["attributes"]["media_type"] = $options["media_type"];
        }
        if (isset($options["media_type"])) {
            if ($options["media_type"] == 'link') { // 链接
                $document["data"]["attributes"]["media_data"]["pic_url"] = $options["media_data"]["pic_url"];
                $document["data"]["attributes"]["media_data"]["link"] = $options["media_data"]["link"];
                $document["data"]["attributes"]["media_data"]["title"] = $options["media_data"]["title"];
            }
            if ($options["media_type"] == 'image') { // 图片
                foreach ($options["media_data"]['image_url'] as $image_url) {
                    $document["data"]["attributes"]["media_data"][] = [
                        "url" => $image_url
                    ];
                }
            }
            if ($options["media_type"] == 'video') { // 视频
                $document["data"]["attributes"]["media_data"]["id"] = $options["media_data"]["video_id"];

            }
        }
        if (isset($options["team_id"])) { // k吧
            $document["data"]["relationships"]["module"]["id"] = $options["team_id"];
            $document["data"]["relationships"]["module"]["type"] = "team";
        }
        if (isset($options["topic_ids"])) { // 话题
            foreach ($options['topic_ids'] as $topic_id) {
                $document['data']["relationships"]["topics"][] = [
                    'type' => 'topic',
                    'id' => $topic_id
                ];
            }
        }
        return $this->forStaff($staff_id)->post('shares', $document);
    }

    // 删除轻享
    public function deleteShare($staff_id, $share_id)
    {
        return $this->forStaff($staff_id)->delete('/shares/' . $share_id);
    }

    // 轻享回复
    public function postShareReply($staff_id, $share_id, $attributes, $options = [])
    {
        $document["data"]["attributes"]["content"] = $attributes["content"];
        if (isset($options["parent_reply_id"])) {
            $document["data"]["relationships"]["parent"] = [
                "type" => "reply_id",
                "id" => $options["parent_reply_id"]
            ];
        }

        return $this->forStaff($staff_id)->post("shares/" . $share_id . "/replies", $document);
    }

    // 轻享评论删除
    public function deleteShareReply($staff_id, $share_id, $reply_id)
    {
        return $this->forStaff($staff_id)->delete("shares/" . $share_id . "/replies/" . $reply_id);
    }

    // 轻享点赞
    public function postShareLike($staff_id, $share_id, $attributes)
    {
        $document["data"]["type"] = "share_like";
        $document["data"]["attributes"]["like_type"] = $attributes["like_type"];
        return $this->forStaff($staff_id)->post('/shares/' . $share_id . '/likes', $document);
    }

    // 取消点赞
    public function deleteShareLike($staff_id, $share_id, $like_id)
    {
        return $this->forStaff($staff_id)->delete('shares/' . $share_id . '/likes/' . $like_id);
    }

    // 新增话题
    public function postShareTopic($staff_id, $attributes)
    {
        $document = [
            "data" => [
                "type" => "topic",
                "attributes" => [
                    "content" => $attributes["content"],
                ]
            ]
        ];
        return $this->forStaff($staff_id)->post("topics", $document);
    }

}