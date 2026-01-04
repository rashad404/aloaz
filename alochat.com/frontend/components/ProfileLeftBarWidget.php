<?php
/**
 * Created by elvin
 * Time: 9:46
 */

namespace frontend\components;

use common\models\User;
use yii\base\Widget;
use yii\db\Query;
use yii\helpers\Url;

class ProfileLeftBarWidget extends Widget
{
    public $actionName;
    public $user;

    public $profilePhoto;

    public $photosCount=0;
    public $photos=[];


    public $friendsCount=0;
    public $friends=[];

    public $giftsCount=0;
    public $gifts=[];

    public $isOwnProfile = false;





    public function init()
    {
        parent::init();
        $db = \Yii::$app->db;
        if($this->actionName == 'post' and intval($_GET["id"])>0){
            $postId = intval($_GET['id']);
            $post = $db->createCommand('SELECT user_id FROM `share` WHERE id="'.$postId.'"')->queryOne();
            $userId = $post["user_id"];
        }else {
            $userId = intval($_GET["id"]);
        }
         $user =  User::findOne($userId);  // $db->createCommand('SELECT * FROM `user` WHERE id="'.$userId.'"')->queryOne();
        if($user){
            $this->user = $user;

            $profile_photo_id = $user["profile_photo_id"];
            $profilePhoto =  Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
            $profilePhoto = $user->profile_photo!=""? Url::base() . $user->profile_photo: Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user->sex];
            $profilePhoto = str_replace('/thumbs/','/resized/',$profilePhoto);
           /* if($profile_photo_id>0){
                $userImage = $db->createCommand('SELECT path FROM user_image WHERE id="'.$profile_photo_id.'"')->queryOne();
                 $profilePhoto = $userImage['path']? Url::base() . $userImage['path']: Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
            }*/
            $this->profilePhoto = $profilePhoto;

            $this->photosCount = $db->createCommand('SELECT count(`id`) FROM  user_image_thumb WHERE user_id="'.$userId.'"')->queryScalar();
            $this->photos =  $db->createCommand('SELECT id,path FROM  user_image_thumb WHERE user_id="'.$userId.'" order by id desc limit 8')->queryAll();

            $this->friendsCount = $db->createCommand('SELECT count(`id`) FROM `user_friend` WHERE (`user_1`="'.$userId.'" or `user_2`="'.$userId.'") and ok=1')->queryScalar();
            if($this->friendsCount>0){
                $userFriends = $db->createCommand('SELECT user_1,user_2 FROM `user_friend` WHERE (`user_1`="'.$userId.'" or `user_2`="'.$userId.'") and ok=1 order by id desc limit 8 ')->queryAll();
                $friends = [];
                foreach($userFriends as $key=>$friend){

                    if($userId == $friend["user_1"]){
                        $friendId = $friend["user_2"];
                    } else {
                        $friendId = $friend["user_1"];
                    }

                    $friendData = $db->createCommand('SELECT profile_photo,nickname,sex FROM `user` WHERE `id`="'.$friendId.'"')->queryOne();
                    $friends[$key]["id"] = $friendId;
                    $friends[$key]["nickname"] = $friendData['nickname'];
                    $friends[$key]["profile_photo"] = $friendData['profile_photo']? Url::base() . $friendData['profile_photo']: Url::base() . \Yii::$app->params['defaultProfilePicture_'.$friendData['sex']];
                }

                $this->friends = $friends;
            }

            $this->giftsCount = $db->createCommand('SELECT count(`id`) FROM `user_gift` WHERE `gift_to`="'.$userId.'"')->queryScalar();
            $this->gifts = $db->createCommand('SELECT g.icon FROM `user_gift` as ug LEFT JOIN `gift` as g ON ug.gift_id=g.id  WHERE `gift_to`="'.$userId.'" limit 8')->queryAll();


            if($user->id  == \Yii::$app->user->id)
                $this->isOwnProfile = true;
        }

    }


    public function run()
    {
        return $this->render('profileLeftBar', [

            'user' => $this->user,
            'profilePhoto' => $this->profilePhoto,


            'photosCount' => $this->photosCount,
            'photos' => $this->photos,

            'friendsCount' => $this->friendsCount,
            'friends' => $this->friends,

            'giftsCount' => $this->giftsCount,
            'gifts' => $this->gifts,

            'isOwnProfile' =>  $this->isOwnProfile
        ]);
    }
}