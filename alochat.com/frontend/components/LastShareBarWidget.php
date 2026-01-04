<?php
/**
 * Created by elvin
 * Time: 9:46
 */

namespace frontend\components;

use common\models\ConversationReply;
use common\models\User;
use yii\base\Widget;
use yii\db\Query;
use yii\helpers\Url;

class LastShareBarWidget extends Widget
{
    public $shares=[];
    public $weekShares=[];
    public $share_count = 10;
    public $user;
    public $topShareDay = true;
    public $topShareWeek = true;
    public $ferqTime = 0;



    public function init()
    {

        parent::init();
        $startTime = time() + microtime();
        $where = '';
        $order = '';
        if($this->topShareDay == true){
            $time = time() - 24*60*60;
           $where = ' and time>'.$time." ";
           $order .= ' like_count DESC,';
        }
        $order .= '`time` DESC ';
         $db = \Yii::$app->db;
        $allShares = [];
             $shares = $db->createCommand("SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM (
  SELECT id,user_id,text,attach,like_count,read_count,time,comment_count
  FROM `share` where status=1 ".$where."
  ORDER BY ".$order."
) AS share_list GROUP BY user_id ORDER BY ".$order." LIMIT ".$this->share_count)->queryAll();
            foreach($shares as $key=>$share){
                $allShares[$key]['attach'] = $share["attach"];
                $allShares[$key]['text'] = $share["text"];
                $allShares[$key]['like_count'] = $share["like_count"];
                $allShares[$key]['read_count'] = $share["read_count"];

                $allShares[$key]['time'] = $share["time"];
                $allShares[$key]['id'] = $share["id"];
                $shareUserId= $share["user_id"];
                $shareUser = $db->createCommand('SELECT profile_photo,sex FROM `user` WHERE id="'.$shareUserId.'"')->queryOne();
                $allShares[$key]['profile_photo'] = $shareUser["profile_photo"]!=''?$shareUser["profile_photo"]:\Yii::$app->params['defaultProfilePicture_'.$shareUser['sex']];
            }
            $this->shares = $allShares;
        $where = ''; $order = '';
        if($this->topShareWeek == true){
            $time = time() - 7*24*60*60;
            $where = ' and time>'.$time." ";
            $order .= ' like_count DESC,`time` DESC';
            $allShares = [];
            $shares = $db->createCommand("SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM (
  SELECT id,user_id,text,attach,like_count,read_count,time,comment_count
  FROM `share` where status=1 ".$where."
  ORDER BY ".$order."
) AS share_list GROUP BY user_id ORDER BY ".$order." LIMIT ".$this->share_count)->queryAll();
            foreach($shares as $key=>$share){
                $allShares[$key]['attach'] = $share["attach"];
                $allShares[$key]['text'] = $share["text"];
                $allShares[$key]['like_count'] = $share["like_count"];
                $allShares[$key]['read_count'] = $share["read_count"];
                $allShares[$key]['time'] = $share["time"];
                $allShares[$key]['id'] = $share["id"];
                $shareUserId= $share["user_id"];
                $shareUser = $db->createCommand('SELECT profile_photo,sex FROM `user` WHERE id="'.$shareUserId.'"')->queryOne();
                $allShares[$key]['profile_photo'] = $shareUser["profile_photo"]!=''?$shareUser["profile_photo"]:\Yii::$app->params['defaultProfilePicture_'.$shareUser['sex']];
            }
            $this->weekShares = $allShares;
        }
        $endTime = time() + microtime();
        $this->ferqTime = $endTime - $startTime;
    }


    public function run()
    {
        return $this->render('lastShareBar', [

             'shares' => $this->shares,
             'weekShares' => $this->weekShares,
             'ferqTime' => $this->ferqTime
        ]);
    }
}