<?php
/**
 * Created by elvin
 * Time: 9:46
 */

namespace frontend\components;

use common\models\User;
use yii\base\Widget;
use yii\db\Query;

class LeftBarWidget extends Widget
{
    public $likedUserCount = 0;
    public $likeCount = 0;
    public $giftCount = 0;
    public $mutualLikeCount = 0;
    public $visitorCount = 0;
    public $languages = [];
    public $newFriendCount = 0;


    public function init()
    {
        parent::init();


        $qr = (new Query())
            ->select('count(id) as count')
            ->from('user_like')
            ->where('like_from=:id', [':id' => \Yii::$app->user->id])
            ->one();

        if(isset($qr['count']))
           $this->likedUserCount =$qr['count'];

        $qr = (new Query())
            ->select('count(id) as count')
            ->from('user_like')
            ->where('like_to=:id', [':id' => \Yii::$app->user->id])
            ->andWhere(['seen' => 0])
            ->one();

        if(isset($qr['count']))
            $this->likeCount =$qr['count'];

        $qr = (new Query())
            ->select('count(id) as count')
            ->from('user_friend')
            ->where('user_2=:id',[':id' => \Yii::$app->user->id])
            ->andWhere(['seen' => 0, 'ok' => 0])
            ->one();

        if(isset($qr['count'])){
            $this->newFriendCount = $qr['count'];
        }

        $qr = (new Query())
            ->select('count(id) as count')
            ->from('user_visit')
            ->where('visit_to=:id', [':id' => \Yii::$app->user->id])
            ->andWhere(['seen' => 0])
            ->one();

        if(isset($qr['count']))
            $this->visitorCount =$qr['count'];


        $qr = (new Query())
            ->select('count(p.like_from) as count')
            ->from('user_like p')
            ->innerJoin('user_like p2', 'p.like_from=p2.like_to AND p.like_to=p2.like_from AND p.like_from<p2.like_from')
            ->where('p.like_to=:id or p.like_from=:id', [':id' => \Yii::$app->user->id])
            ->one();

        if(isset($qr['count']))
            $this->mutualLikeCount =$qr['count'];

        $qr = (new Query())
            ->select('count(id) as count')
            ->from('user_gift')
            ->where('gift_to=:id', [':id' => \Yii::$app->user->id])
            ->andWhere(['seen' => 0])
            ->one();

        if(isset($qr['count']))
            $this->giftCount =$qr['count'];

        $this->languages = [

            'az' => ['short' => 'AZ', 'full' => 'Azərbaycanca'],
            'tr' => ['short' => 'TR', 'full' => 'Türkçe'],
            'en' => ['short' => 'EN', 'full' => 'English'],
            'ru' => ['short' => 'RU', 'full' => 'Русский'],

        ];

    }




    public function run()
    {
        return $this->render('leftBar', [
            'likedUserCount' => $this->likedUserCount,
            'likeCount'=> $this->likeCount,
            'giftCount'=> $this->giftCount,
            'mutualLikeCount' => $this->mutualLikeCount,
            'visitorCount'=> $this->visitorCount,
            'newFriendCount'=> $this->newFriendCount,
            'languages' => $this->languages
        ]);
    }
}