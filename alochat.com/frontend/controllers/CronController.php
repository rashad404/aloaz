<?php

namespace frontend\controllers;

use common\models\UserImage;
use common\models\UserImageResized;
use common\models\UserImageSend;
use common\models\UserImageThumb;
use yii\filters\AccessControl;
use yii\helpers\BaseFileHelper;
use yii\web\Controller;
use yii\web\User;

class CronController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','message','image'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'ips' => ['176.32.32.21','127.0.0.1','213.172.91.195','217.64.27.46']
                    ],

                ]
            ],
        ];
    }

    public function actionOnlineUserValue()
    {
        $this->layout = false;
        $db = \Yii::$app->db;
        $user_value = 0;
        $user_value_text = '';
        $min_time  = time() - \Yii::$app->params['userOnlineStatusCheckTime'];
        $today  = date("Y-m-d");
        $users = $db->createCommand('SELECT * FROM `user` WHERE `last_activity`>:min_time  ORDER BY last_activity DESC limit 5000')
            ->bindValues([":min_time" => $min_time])
            ->queryAll();
        echo count($users)." user tapildi <br />";
        foreach($users as $user){

            if(strlen($user["about"])>10){
                $user_value  += \Yii::$app->params["aboutIssetValue"];
                $user_value_text .= ' +about_value';
            }

            if($user["profile_photo"]!="" and intval($user["profile_photo_id"])>0){
                $user_value  += \Yii::$app->params["photoIssetValue"];
                $user_value_text .= ' +photo_value';
            }

            if(intval($user["like_count"])>0){
                $user_value  += $user["like_count"] * \Yii::$app->params["likeCountValue"];
                $user_value_text .= ' +like_value';
            }

            if(intval($user["report_count"])>0){
                $value = 0;
                if($user["report_count"]==1)
                {
                    $value = -5;
                }elseif($user["report_count"]>1 and $user["report_count"]<=3){
                    $value = -10;
                }elseif($user["report_count"]>3 and $user["report_count"]<=5){
                    $value = -20;
                } elseif($user["report_count"]>5 and $user["report_count"]<=10){
                    $value = -30;
                }   elseif($user["report_count"]>10){
                    $value = -40;
                }
                $user_value  += $value;
                $user_value_text .= ' -report_value';

            }

            if(intval($user["msg_count"])>0){
                $value = 0;
                if($user["msg_count"]>10 and $user["msg_count"]<=100)
                {
                    $value = 1;
                }elseif($user["msg_count"]>100 and $user["msg_count"]<=500){
                    $value = 2;
                }elseif($user["msg_count"]>500 and $user["msg_count"]<=1000){
                    $value = 3;
                }elseif($user["msg_count"]>1000){
                    $value = 5;
                }
                \Yii::$app->params["msgCountValue"] = $value;
                $user_value  +=  \Yii::$app->params["msgCountValue"];
                $user_value_text .= ' +message_value';

            }

            if(intval($user["verify"])==1){
                $user_value  +=  \Yii::$app->params["verifyUserValue"];
                $user_value_text .= ' +add_verify_value';
            }

            if(intval($user["sex"]) ==  1){
                $user_value  +=  \Yii::$app->params["womanValue"];
                $user_value_text .= ' +add_woman_value';
            }


            if($user["user_value"] != $user_value){
                $db->createCommand('UPDATE `user` SET user_value=:user_value,user_value_date=:user_value_date WHERE id=:user_id limit 1')
                    ->bindValues([
                        ":user_id" => $user["id"],
                        ":user_value" => $user_value,
                        ":user_value_date" => $today
                    ])
                    ->execute();
            }

            echo $user["id"]." ) ".$user["nickname"]." - deyeri ".$user_value." - value text".$user_value_text."<br />";

            $user_value = 0;
            $user_value_text = '';
        }
    }

    public function actionUserValue()
    {
        $this->layout = false;
        $db = \Yii::$app->db;
        $user_value = 0;
        $user_value_text = '';
        $min_time  = time() - 60*24*60*60;
        $today  = date("Y-m-d");
        $users = $db->createCommand('SELECT * FROM `user` WHERE `last_activity`>:min_time and `user_value_date`!=:today ORDER BY last_activity DESC limit 5000')
            ->bindValues([":min_time" => $min_time,":today"=>$today])
            ->queryAll();
        foreach($users as $user){

            if(strlen($user["about"])>10){
                $user_value  += \Yii::$app->params["aboutIssetValue"];
                $user_value_text .= ' +about_value';
            }

            if($user["profile_photo"]!="" and intval($user["profile_photo_id"])>0){
                $user_value  += \Yii::$app->params["photoIssetValue"];
                $user_value_text .= ' +photo_value';
            }

            if(intval($user["like_count"])>0){
                $user_value  += $user["like_count"] * \Yii::$app->params["likeCountValue"];
                $user_value_text .= ' +like_value';
            }

            if(intval($user["report_count"])>0){
                $value = 0;
                if($user["report_count"]==1)
                {
                    $value = -5;
                }elseif($user["report_count"]>1 and $user["report_count"]<=3){
                    $value = -10;
                }elseif($user["report_count"]>3 and $user["report_count"]<=5){
                    $value = -20;
                } elseif($user["report_count"]>5 and $user["report_count"]<=10){
                $value = -30;
                }   elseif($user["report_count"]>10){
                    $value = -40;
                }
                $user_value  += $value;
                $user_value_text .= ' -report_value';

            }

            if(intval($user["msg_count"])>0){
                $value = 0;
                if($user["msg_count"]>10 and $user["msg_count"]<=100)
                {
                    $value = 1;
                }elseif($user["msg_count"]>100 and $user["msg_count"]<=500){
                    $value = 2;
                }elseif($user["msg_count"]>500 and $user["msg_count"]<=1000){
                    $value = 3;
                }elseif($user["msg_count"]>1000){
                    $value = 5;
                }
                \Yii::$app->params["msgCountValue"] = $value;
                $user_value  +=  \Yii::$app->params["msgCountValue"];
                $user_value_text .= ' +message_value';

            }

            if(intval($user["verify"])==1){
                $user_value  +=  \Yii::$app->params["verifyUserValue"];
                $user_value_text .= ' +add_verify_value';
            }

            if(intval($user["sex"]) ==  1){
                $user_value  +=  \Yii::$app->params["womanValue"];
                $user_value_text .= ' +add_woman_value';
            }


            if($user["user_value"] != $user_value){
                $db->createCommand('UPDATE `user` SET user_value=:user_value,user_value_date=:user_value_date WHERE id=:user_id limit 1')
                    ->bindValues([
                        ":user_id" => $user["id"],
                        ":user_value" => $user_value,
                        ":user_value_date" => $today
                    ])
                    ->execute();
            }

            echo $user["id"]." ) ".$user["nickname"]." - deyeri ".$user_value." - value text".$user_value_text."<br />";

            $user_value = 0;
            $user_value_text = '';
        }
    }


    public function actionFolder()
    {
        $dir = 'images/user';
        $ffs = scandir($dir);
         foreach($ffs as $ff){
             if($ff != '.' && $ff != '..'){
                $oldDir = $dir.'/'.$ff;
                $newDir = $dir.'/'.str_replace('_a','',$ff);

                 if(is_dir($oldDir)) {
                     echo str_replace('_a','',$ff)."<br />";
                     rename($oldDir, $newDir);
                  }
            }
        }
     }

    public function actionImages()
    {
        $users = \Yii::$app->db->createCommand('SELECT id,old_id,profile_photo FROM `sil_user` where old_id>0 and f_row=0 and id in (12,13,18,2,3,4,59,10) limit 0,10')->queryAll();
         if($users!=NULL) {
            foreach ($users as $user) {
                echo "NEW user id -".$user["id"]."<br />";
                 $userId = $user["old_id"];
                $userNewId = $user["id"];

                $userImages = UserImage::find()->where('user_id=' . $userId)->all();
                if ($userImages != NULL) {

                    $dir = "images/user/" . $userId;
                    $newDir = "images/user/" . $userNewId . "_a";

                    if (is_dir($dir)) rename($dir, $newDir);
                    else  echo ("$dir is not a directory") . "<br />";

                    foreach ($userImages as $userImage) {
                        $userImage->user_id = $user["id"];
                        $path = $userImage->path;
                        $userImage->path = str_replace('user/' . $userId, 'user/' . $userNewId , $path);
                        $userImage->save(false);
                    }
                } else {
                    $dir = "images/user/" . $userId;
                    $newDir = "images/user/" . $userNewId . "_a";

                    if (is_dir($dir)) rename($dir, $newDir);
                    else  echo ("$dir is not a directory") . "<br />";
                }

                $userResizedImages = UserImageResized::find()->where('user_id=' . $userId)->all();
                if ($userResizedImages != NULL) {

                    $dir = "images/user/" . $userId;
                    $newDir = "images/user/" . $userNewId . "_a";

                    if (is_dir($dir)) rename($dir, $newDir);
                    else  echo ("$dir is not a directory") . "<br />";

                    foreach ($userResizedImages as $userResizedImage) {
                        $userResizedImage->user_id = $user["id"];
                        $path = $userResizedImage->path;
                        $userResizedImage->path = str_replace('user/' . $userId, 'user/' . $userNewId , $path);
                        $userResizedImage->save(false);
                    }
                }

                $userThumbImages = UserImageThumb::find()->where('user_id=' . $userId)->all();
                if ($userThumbImages != NULL) {

                    $dir = "images/user/" . $userId;
                    $newDir = "images/user/" . $userNewId . "_a";

                    if (is_dir($dir)) rename($dir, $newDir);
                    else  echo ("$dir is not a directory") . "<br />";

                    foreach ($userThumbImages as $userThumbImage) {
                        $userThumbImage->user_id = $user["id"];
                        $path = $userThumbImage->path;
                        $userThumbImage->path = str_replace('user/' . $userId, 'user/' . $userNewId , $path);
                        $userThumbImage->save(false);
                    }
                }


                $userSendImages = UserImageSend::find()->where('user_id=' . $userId)->all();
                if ($userSendImages != NULL) {

                    $dir = "images/user/" . $userId;
                    $newDir = "images/user/" . $userNewId . "_a";

                    if (is_dir($dir)) rename($dir, $newDir);
                    else  echo ("$dir is not a directory") . "<br />";

                    foreach ($userSendImages as $userSendImage) {
                        $userSendImage->user_id = $user["id"];

                        $path = $userSendImage->path;
                        $userSendImage->path = str_replace('user/' . $userId, 'user/' . $userNewId , $path);

                        $path_original = $userSendImage->path_original;
                        $userSendImage->path_original = str_replace('user/' . $userId, 'user/' . $userNewId , $path_original);

                        $userSendImage->save(false);
                    }
                }

                $profile_photo = str_replace('user/' . $userId, 'user/' . $userNewId , $user["profile_photo"]);
                \Yii::$app->db->createCommand("UPDATE sil_user SET emeliyyat=5,profile_photo='" . $profile_photo . "' WHERE id=" . $userNewId)->execute();
             }
        }

        exit;
    }

    public function actionAloazimage()
    {


        $db = \Yii::$app->db;

        $webRoot = \Yii::getAlias('@webroot');
        $imageIndex = rand(1000,9999);

        $users = $db->createCommand("SELECT id,changed_photo_url,sex,nickname FROM user WHERE changed_photo=1 and  changed_photo_url!='' order by id desc limit 10")->queryAll();
        foreach($users as $user){
            $profile_photo = explode('|',$user["changed_photo_url"]);
             if($user['sex']==0) $s_path = 0;  elseif($user['sex']==1) $s_path = 1;

             $userId = $user["id"];
             /* echo '<img src="http://m.alo.az/photos/files/'.$s_path.'/'.$profile_photo[0].'">';
            /*/
            echo "<br />".$userId;
               $userImage = new UserImage();
              $thumb = new UserImageThumb();

            $userImage->user_id = $userId;
            $path = 'images/user/'.$userId.'/';
            $filePath = 'images/user/'.$userId.'/'.$userId."_".$imageIndex.".jpg";
            BaseFileHelper::createDirectory($path,0777,false);
            $userImage->path = '/images/user/'.$userId.'/'.$userId."_".$imageIndex.".jpg";
            $userImage->add_date = time();
            $file = file_get_contents('http://m.alo.az/photos/files/'.$s_path.'/'.$profile_photo[0].'');
            if($file) {  // eger file varsa


                file_put_contents($filePath, $file);
                $userImage->save(false);


                //THUMB
                $thumbsDir = $webRoot . '/images/user/' . $userId . '/thumbs/';
                if (!is_dir($thumbsDir)) {
                    mkdir($thumbsDir, 0777, true);
                }
                $thumbsDir = $thumbsDir . '/';
                $image_t = $thumbsDir . $userId . '_' . $imageIndex . ".jpg";
                if (UserImage::resizeImageAndSave($webRoot . $userImage->path, $image_t, 120, 120)) {
                    $thumb = new UserImageThumb();
                    $thumb->user_id = $userId;
                    $thumb->path = '/images/user/' . $userId . '/thumbs/' . $userId . '_' . $imageIndex . ".jpg";
                    $thumb->add_date = time();
                    $thumb->id = $userImage->primaryKey;
                    $thumb->save();
                }

                //RESIZED
                $resizedImagesDir = $webRoot . '/images/user/' . $userId . '/resized/';
                if (!is_dir($resizedImagesDir)) {
                    mkdir($resizedImagesDir, 0777, true);
                }
                $resizedImagesDir = $resizedImagesDir . '/';
                $image_r = $resizedImagesDir . $userId . '_' . $imageIndex . ".jpg";

                if (UserImage::resizeImageAndSave($webRoot . $userImage->path, $image_r, 320, 240) && $thumb->id) {
                    $resized = new UserImageResized();
                    $resized->user_id = $userId;
                    $resized->path = '/images/user/' . $userId . '/resized/' . $userId . '_' . $imageIndex . ".jpg";
                    $resized->add_date = time();
                    $resized->id = $userImage->primaryKey;
                    $resized->save();
                }
            }


            $db->createCommand("UPDATE user SET profile_photo='".$thumb->path."',profile_photo_id='".$userImage->id."',changed_photo=0,changed_photo_url='' where id=".$userId)->execute();


        }
    }


    public  function actionUserfriend()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id,profile_photo FROM `user` where f_row=0 and old_id>0')->queryAll();
         if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update user_friend set user_1=".$userNewId." where user_1=".$userId)->execute();
                \Yii::$app->db->createCommand("update user_friend set user_2=".$userNewId." where user_2=".$userId)->execute();
            }
        }

    }

    public  function actionUservisitor()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where f_row=0 and old_id>0')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update user_visit set visit_from=".$userNewId." where visit_from=".$userId)->execute();
                \Yii::$app->db->createCommand("update user_visit set visit_to=".$userNewId." where visit_to=".$userId)->execute();
            }
        }

    }

    public  function actionUserlike()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update user_like set like_from=".$userNewId." where like_from=".$userId)->execute();
                \Yii::$app->db->createCommand("update user_like set like_to=".$userNewId." where like_to=".$userId)->execute();
            }
        }

    }

    public  function actionUserphotouploadask()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update user_photo_upload_ask set user_from=".$userNewId." where user_from=".$userId)->execute();
                \Yii::$app->db->createCommand("update user_photo_upload_ask set user_to=".$userNewId." where user_to=".$userId)->execute();
            }
        }

    }

    public  function actionUservip()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update vip_user set user_id=".$userNewId." where user_id=".$userId)->execute();
             }
        }

    }

    public  function actionUsergift()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update user_gift set gift_from=".$userNewId." where gift_from=".$userId)->execute();
                \Yii::$app->db->createCommand("update user_gift set gift_to=".$userNewId." where gift_to=".$userId)->execute();
            }
        }

    }

    public  function actionUserblock()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update user_block set block_from=".$userNewId." where block_from=".$userId)->execute();
                \Yii::$app->db->createCommand("update user_block set block_to=".$userNewId." where block_to=".$userId)->execute();
            }
        }

    }

    public  function actionUseractivity()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0 and emeliyyat=0 limit 100')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update user_activity set user_id=".$userNewId." where user_id=".$userId)->execute();
                \Yii::$app->db->createCommand("update `user` set id=".$userNewId." where emeliyyat=5")->execute();
            }
        }

    }

    public  function actionConversation()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0 and emeliyyat=0 and id!=1 limit 500')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update conversation set user_one=".$userNewId." where user_one=".$userId)->execute();
                \Yii::$app->db->createCommand("update conversation set user_two=".$userNewId." where user_two=".$userId)->execute();
                \Yii::$app->db->createCommand("update `user` set emeliyyat=5  where id=".$userNewId)->execute();
            }
        }

    }

    public  function actionConversationreply()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and emeliyyat=0 and id!=1 limit 500')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update conversation_reply set user_id=".$userNewId." where user_id=".$userId)->execute();
                \Yii::$app->db->createCommand("update `user` set emeliyyat=5  where id=".$userNewId)->execute();

            }
        }

    }

    public  function actionAuth()
    {
        // Bu action user friend tablesindeki user id-leri yeni id-ler ile evez etmek ucundur
        $users = \Yii::$app->db->createCommand('SELECT id,old_id FROM `user` where old_id>0 and f_row=0')->queryAll();
        if($users!=NULL) {
            foreach ($users as $user) {
                $userId = $user["old_id"];
                $userNewId = $user["id"];

                \Yii::$app->db->createCommand("update auth set user_id=".$userNewId." where user_id=".$userId)->execute();
            }
        }

    }

    public function actionNickname()
    {
        $db = \Yii::$app->db;
        $k = 0;
        $m = 0;
        $users = $db->createCommand('SELECT DISTINCT(nickname) AS nick, COUNT(nickname) AS dupCount,id,full_name FROM `user` where `f_row` =0 GROUP BY nickname HAVING dupCount > 1')->queryAll();
        echo count($users)."<br />";
          foreach($users as $user){
             $id=$user["id"];
             $nickname = $this->toAscii($user["full_name"]);
             echo $id.$nickname." - ";
             if($nickname==''){
                 $nickname = 'user_'.rand(1000,9999);
                 $m++;
             }
             $isset = $db->createCommand("select count(id) from `user` where nickname='".$nickname."'")->queryScalar();
             if($isset){
                $nickname = $nickname."_".rand(100,999);
                 $k++;
             }
             echo $nickname."<br />";
             $db->createCommand('UPDATE `user` SET nickname="'.$nickname.'", yenile=5 where id='.$id)->execute();

         }
        /*echo "<br />".$m."<br />";
        echo $k;*/
     }

    public function actionPass()
    {
        $db = \Yii::$app->db;
        $users = $db->createCommand('SELECT id,password FROM user WHERE f_row=1 and emeliyyat=0  and password!="" limit 80')->queryAll();
        foreach($users as $user){

            $auth_key = \Yii::$app->security->generateRandomString();
            $password_hash = \Yii::$app->security->generatePasswordHash($user["password"]);

            $db->createCommand('UPDATE `user` SET emeliyyat=5,password_hash="'.$password_hash.'",auth_key="'.$auth_key.'" WHERE id='.$user["id"])->execute();
            echo $user["id"]."<br />";

        }


     /*


        $user->setPassword($this->password);

        $user->generateAuthKey();

        if ($user->save(false)) {
            User::sendWelcomeMessage($user->id);
            return $user;
        }*/
    }


    public function actionImages2()
    {
        $db = \Yii::$app->db;
        $users = $db->createCommand('SELECT profile_photo_id,id,profile_photo FROM `user` WHERE profile_photo_id>0 and f_row=1 and emeliyyat!=15 limit 500')->queryAll();
        foreach($users as $user){
            $image = $user["profile_photo"];
            if(!strpos($image,"/thumbs/")){
                echo "yoxdur".$image."<br />";
                $new_image = str_replace('user/'.$user["id"]."/",'user/'.$user["id"]."/thumbs/",$image);
                $db->createCommand('UPDATE `user` SET profile_photo="'.$new_image.'",emeliyyat=15,tr_time="'.time().'" WHERE id='.$user["id"])->execute();
                echo $image. " -> ". $new_image."<hr />";
                exit;
            }

        }

     }




    public function actionChangesex()
    {
        \Yii::$app->db->createCommand('UPDATE `user` SET sex=IF(sex=0,"1","0") WHERE f_row=1')->execute();
    }

    protected function toAscii($str, $replace=array(), $delimiter='-') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }


    public function actionShareImage() // istifade olundu
    {
        /*$webRoot = \Yii::getAlias('@webroot');

        chmod($webRoot . '/images/share/resized/',777); exit;*/
        $db = \Yii::$app->db;
        //http://m.alo.az/share/uploads/201512/7fd0186134607a1.jpeg

        $webRoot = \Yii::getAlias('@webroot');

        $shares = $db->createCommand("SELECT id,attach,time FROM `share` WHERE attach!='' and emeliyyat!=5 order by id desc limit 190")->queryAll();
        $i=1;
        foreach ($shares as $share) {
            $date = date("Ym",$share["time"]);
             $name = 'http://m.alo.az/share/uploads/'.$date.'/'.$share["attach"];
            $file = file_get_contents($name);
            $filePath = 'images/share/uploads/'.$date.'/'.$share["attach"];


            if ($file) {  // eger file varsa
                echo $i.")".$share["id"].$name."<br />";
               // echo "<img src='".$name."'>"."<br />";

                $imagesDir = $webRoot . '/images/share/uploads/'.$date.'/';
                $thumbsDir = $webRoot . '/images/share/thumbs/'.$date.'/';
                $resizedImagesDir = $webRoot . '/images/share/resized/'.$date.'/';


                if (!is_dir($imagesDir)) {

                    mkdir($imagesDir, 0777, true);
                }

                if (!is_dir($thumbsDir)) {

                    mkdir($thumbsDir, 0777, true);
                }

                if (!is_dir($resizedImagesDir)) {

                    mkdir($resizedImagesDir, 0777, true);
                }

                file_put_contents($filePath, $file);


                $image_d = $imagesDir . $share["attach"];
                $image_t = $thumbsDir . $share["attach"];
                $image_r = $resizedImagesDir .$share["attach"];

                UserImage::resizeImageAndSave2($image_d, $image_t, 600,400);
                UserImage::resizeImageAndSave2($image_d, $image_r, 220, 250);



            }


            $db->createCommand("UPDATE `share` SET emeliyyat=5 where id=" . $share["id"])->execute();
            $i++;
        }
    }
    public function  actionShareComment()
    {
        $db  = \Yii::$app->db;

        $shares = $db->createCommand('SELECT id FROM share where emeliyyat!=10 limit 1000')->queryAll();

        foreach($shares  as $share){
            $commentCount = $db->createCommand('SELECT count(id) FROM share_comment WHERE sid="'.$share["id"].'"')->queryScalar();
            echo $share["id"]." - ".$commentCount."<br />";
            $update = $db->createCommand('UPDATE share SET comment_count="'.$commentCount.'",emeliyyat=10 where id="'.$share["id"].'"')->execute();
        }
    }


    public function actionChangePass()
    {
        $db = \Yii::$app->db;

        $isset = $db->createCommand('SELECT count(id) FROM `user` WHERE changed_pass=1')->queryScalar();
        if($isset > 0){
            $rows = $db->createCommand('SELECT id,password FROM `user` WHERE changed_pass=1 limit 20')->queryAll();
            foreach($rows as $row)
            {
                $passwordHash = \Yii::$app->security->generatePasswordHash($row["password"]);
                $db->createCommand('UPDATE `user` SET password_hash=:hash,changed_pass=0 WHERE id=:id')->bindValues([":hash" => $passwordHash,":id"=>$row["id"]])->execute();
                echo "id: ".$row["id"];
            }
        }
    }


    public function actionShareProblem()
    {
        $db = \Yii::$app->db;
        $i=0;
        $shares  = $db->createCommand('select id,user_id,attach FROM `admin_alochat`.`share` WHERE user_id=:user_id and attach!=""  limit 10000')->bindValues([":user_id"=>1129446])->queryAll();
        foreach($shares as $share){
            $share_id = $share["id"];
            if (strpos($share["attach"], '_') !== false) {
                $a = explode('_',$share["attach"]);
              //  echo $a[0].' true<br />';
               // echo $i.")".$share["attach"]."->" .$share["user_id"]."->".$share["id"]."<br />";
                $i++;
                $db->createCommand('update `admin_alochat`.`share` set user_id=:user_id,status=1 where id=:id')->bindValues([":user_id"=>$a[0], ":id" => $share_id])->execute();
                echo $i.")".$share_id."---".$a[0]."<br />";


            }           /* $aloshare = $db->createCommand('select id,uid from `aloaz_db`.`share_list` where id=:share_id')->bindValue(':share_id',$share_id)->queryOne();
            echo $share_id." ";
            if($aloshare != null){
                $db->createCommand('update `admin_alochat`.`share` set user_id=:user_id,status=1 where id=:id')->bindValues([":user_id"=>$aloshare["uid"], ":id" => $share_id])->execute();
            echo "oldu<br />";
            }*/

        }
    }

    public function actionDeleteOldMessages()
    {
        $db = \Yii::$app->db;
        $time = time() - 7*24*3600;

        $count  = $db->createCommand('SELECT count(id) FROM `conversation_reply` WHERE `read`=1 and `time`<:time')->bindValue(':time',$time)->queryScalar();
         $delete = $db->createCommand('DELETE FROM `conversation_reply` WHERE `read`=1 and `time`<:time')->bindValue(':time',$time)->execute();
        var_dump($delete);
    }

    public function actionDeleteOldMessagesRead()
    {
        $db = \Yii::$app->db;
        $time = time() - 30*24*3600;

        $count  = $db->createCommand('SELECT count(id) FROM `conversation_reply` WHERE `read`=0 and `time`<:time')->bindValue(':time',$time)->queryScalar();
        $delete = $db->createCommand('DELETE FROM `conversation_reply` WHERE `read`=0 and `time`<:time')->bindValue(':time',$time)->execute();
        var_dump($delete);
    }


    public function actionDeleteOldConversation()
    {
         $db = \Yii::$app->db;
        $update = $db->createCommand('UPDATE `conversation` as c SET `rstatus`=5 WHERE (SELECT count(id) FROM `conversation_reply` as cr WHERE cr.conversation_id=c.id) ')->execute();
        if($update){
            $delete = $db->createCommand('DELETE FROM `conversation` WHERE `rstatus`=5')->execute();
        }
    }

    public function actionDeleteOldRoomMessages()
    {
        $db = \Yii::$app->db;
        $time = (time() - (7*24*3600));



        $count  = $db->createCommand('SELECT count(id) FROM `room_msgs` WHERE `time`<:time')->bindValue(':time',$time)->queryScalar();
        $delete = $db->createCommand('DELETE FROM `room_msgs` WHERE `time`<:time')->bindValue(':time',$time)->execute();
        var_dump($delete);

        $selectCount = $db->createCommand('SELECT count(id) FROM `room_msgs` WHERE `rid`=10')->queryScalar();
        $deleteCount  = $selectCount-10000;
        $delete = $db->createCommand('DELETE FROM `room_msgs` WHERE `rid`=:rid ORDER BY id ASC LIMIT '.$deleteCount)->bindValue(':rid',10)->execute();
        echo "<br />";
        var_dump($delete);


    }

}
