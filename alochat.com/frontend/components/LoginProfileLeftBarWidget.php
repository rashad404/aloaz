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

class LoginProfileLeftBarWidget extends Widget
{
    public $actionName;
    public $user;

    public $profilePhoto;



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
            $profilePhoto = $user->profile_photo!=""? Url::base() . $user->profile_photo: Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user->sex];
            $profilePhoto = str_replace('/thumbs/','/resized/',$profilePhoto);
            $this->profilePhoto = $profilePhoto;

            if($user->id  == \Yii::$app->user->id)
                $this->isOwnProfile = true;
        }

    }


    public function run()
    {
        return $this->render('loginProfileLeftBar', [

            'user' => $this->user,
            'profilePhoto' => $this->profilePhoto,


            'isOwnProfile' =>  $this->isOwnProfile
        ]);
    }
}