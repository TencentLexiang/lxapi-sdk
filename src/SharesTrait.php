<?php
namespace Lexiangla\Openapi;
Trait SharesTrait {
    //新建轻享
    public function postSharesTrait($staff_id, $options) {
        if(isset($options["content"])){
            $document["data"]["attributes"]["content"]=$options["content"];
        }
        if(isset($options["media_type"])){
            $document["data"]["attributes"]["media_type"]=$options["media_type"];
        }
        
        //基本属行选择
        if(isset($options["media_type"])){
            //设置链接
            if($options["media_type"]=='link'){
                if(isset($options["pic_url"])){
                    $document["data"]["attributes"]["media_data"]["pic_url"]=$options["pic_url"];
                }if(isset($options["link"])){
                    $document["data"]["attributes"]["media_data"]["link"]=$options["link"];
                }if(isset($options["title"])){
                    $document["data"]["attributes"]["media_data"]["title"]=$options["title"];
                }
            }
            //设置图片
            if($options["media_type"]=='image'){
                if(isset($options["image_url"])){
                    foreach ($options['image_url'] as $image_url) {
                        $document["data"]["attributes"]["media_data"][]=[
                            "url"=>$image_url
                        ];
                    }
                }
            }
            //设置视频
            if($options["media_type"]=='video'){
                if(isset($options["video_id"])){
                    $document["data"]["attributes"]["media_data"]["id"]=$options["video_id"];
                }
            }
        }
        //设置k吧
        if(isset($options["team_id"])){
            $document["data"]["relationships"]["module"]["id"]=$options["team_id"];
            $document["data"]["relationships"]["module"]["type"]="team";
        }
        //设置话题
        if(isset($options["topics_id"])){
            foreach ($options['topics_id'] as $topics_id) {
                $document['data']["relationships"]["topics"][] = [
                    'type' => 'topic',
                    'id' => $topics_id
                ];
            }
            
        }
        return $this->forStaff($staff_id)->post('shares', $document);
    }
    //删除轻享
    public function DeleteShares($staff_id,$shares_id){//删除轻享
        return $this->forStaff($staff_id)->delete('/shares/'.$shares_id);
    }
    //轻享回复
    public function postReplies($staff_id,$shares_id,$attributes,$options=[]){
        $document["data"]["attributes"]["content"]=$attributes["content"];
        if(isset($options["reply_id"])){
            $document["data"]["relationships"]["parent"]=[
                "type"=>"reply_id",
                "id"=>$options["reply_id"]
            ];
        }
        
        return $this->forStaff($staff_id)->post("shares/".$shares_id."/replies", $document);
    }
    //轻享评论删除
    public function DeleteReplies($staff_id,$shares_id,$reply_id){
        return $this->forStaff($staff_id)->delete("shares/".$shares_id."/replies/".$reply_id);
    }
    //轻享点赞
    public function postLikes($staff_id,$share_id,$attributes){
        $document["data"]["attributes"]["like_type"]=$attributes["like_type"];
        return $this->forStaff($staff_id)->post('/shares/'.$share_id.'/likes',$document);
    }
    //取消点赞
    public function DeleteLikes($staff_id,$shares_id,$like_id){
        return $this->forStaff($staff_id)->delete('shares/'.$shares_id.'/likes/'.$like_id);
    }
    //新增话题
    public function postTopics($staff_id,$attributes){
        $document=[
            "data"=>[
                "type"=>"topic",
                "attributes"=>[
                    "content"=>$attributes["content"],
                ]
            ]
        ];
        return $this->forStaff($staff_id)->post("topics",$document);
    }
    
}
?>