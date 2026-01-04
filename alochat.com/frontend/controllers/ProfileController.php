<?php

namespace frontend\controllers;

use common\models\City;
use common\models\CoinLogs;
use common\models\Conversation;
use common\models\ConversationReply;
use common\models\Country;
use common\models\Gift;
use common\models\GiftCategory;
use common\models\ImageComment;
use common\models\ImageLike;
use common\models\Notification;
use common\models\Share;
use common\models\ShareComment;
use common\models\ShareLike;
use common\models\User;
use common\models\UserActivity;
use common\models\UserBlock;
use common\models\UserFriend;
use common\models\UserGift;
use common\models\UserImage;
use common\models\UserImageThumb;
use common\models\UserLike;
use common\models\UserPhotoUploadAsk;
use common\models\UserReport;
use common\models\UserVip;
use common\models\UserVisit;
use frontend\models\AcceptCommentForm;
use frontend\models\CitySelectForm;
use frontend\models\DiscoveryFilterForm;
use frontend\models\ImageUploadForm;
use frontend\models\PasswordChangeForm;
use frontend\models\PhoneChangeForm;
use frontend\models\ShareForm;
use frontend\models\UserFilterForm;
use frontend\models\VerifyPhoneForm;
use Yii;
use frontend\models\ProfileSettingsForm;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class ProfileController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionPhotos($id)
    {
        $this->layout = 'profile-simple';

        $db = Yii::$app->db;

        $user = $this->findModel($id);

        $isOwnProfile = false;

        if ($user->id == Yii::$app->user->id)
            $isOwnProfile = true;

        if($user->deactive==1){
            return $this->redirect("/profile/deactive/".$user["id"]);
        }

        $photoUploadAskExist =  false;
        $photoUploadAskExist = UserPhotoUploadAsk::findOne(['user_from' => Yii::$app->user->id, 'user_to' => $user->id]);


        $imageForm = new ImageUploadForm();

        $userImages = UserImageThumb::find()
            ->where(['user_id' => $user->id])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();

        return $this->render('photos',[
            'userModel' => $user,
            'userImages' =>$userImages,
            'isOwnProfile' => $isOwnProfile,
            'photoUploadAskExist' => $photoUploadAskExist,
            'imageForm' => $imageForm
        ]);
    }

    public function actionFriends($id)
    {
        $this->layout = 'profile-simple';

        $db = Yii::$app->db;

        $user = $this->findModel($id);

        $isOwnProfile = false;

        if ($user->id == Yii::$app->user->id)
            $isOwnProfile = true;

        if($user->deactive==1){
            return $this->redirect("/profile/deactive/".$user["id"]);
        }

        $users = [];

        $queryCount = $db->createCommand('SELECT count(id) FROM user_friend WHERE (user_1="'.$id.'" or user_2= "'.$id.'") and ok=1')->queryScalar();

        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 12;

            $userFriends =  $db->createCommand('SELECT user_1,user_2 FROM user_friend WHERE (user_1="'.$id.'" or user_2= "'.$id.'")  ORDER BY `id` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();

            foreach($userFriends as $key=>$friend){
                if($friend["user_1"] == $id){
                    $friendId = $friend["user_2"];
                }else {
                    $friendId = $friend["user_1"];
                }

                $friendUser = $db->createCommand('SELECT id,profile_photo,last_activity,nickname,full_name,last_post,city_id,country_id,age FROM `user` WHERE `id`="'.$friendId.'"')->queryOne();

                $users[$key]['id'] = $friendUser["id"];
                 $users[$key]['profile_photo'] = $friendUser["profile_photo"]!=''?$friendUser["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                $users[$key]['last_activity'] = $friendUser["last_activity"];
                $users[$key]['nickname'] = $friendUser["nickname"];
                $users[$key]['full_name'] = $friendUser["full_name"];
                $users[$key]['last_post'] = $friendUser["last_post"];
                $users[$key]['age'] = $friendUser["age"];

            }
        }



        return $this->render('friends',[
            'userModel' => $user,
            'users' =>$users,
            'isOwnProfile' => $isOwnProfile
         ]);
    }

    public function actionHome($id)
    {
        $startTime = time() + microtime();
        $this->layout = 'profile';

        $user = $this->findModel($id);

        $isOwnProfile = false;
        if($user->deactive == 1){

            return $this->redirect(Url::to(["profile/deactive/".$id]));
        }

        if ($user->id == Yii::$app->user->id)
            $isOwnProfile = true;

        if (!$isOwnProfile) {

            $visitExist = UserVisit::findOne(['visit_from' => Yii::$app->user->id, 'visit_to' => $user->id]);

            if (!empty($visitExist)) {

                $visitExist->time = time();
                $visitExist->seen = 0;
                $visitExist->count+=1;
                $visitExist->update(false);

            } else {
                $visit = new UserVisit();
                $visit->visit_from = Yii::$app->user->id;
                $visit->visit_to = $user->id;
                $visit->time = time();
                $visit->count = 1;
                $visit->seen = 0;
                $visit->save();
            }

        }
        $smilesArray = ConversationReply::getEmojis();

        $allShares = [];
        $shares = Yii::$app->db->createCommand('SELECT * FROM `share` WHERE `user_id`="'.$id.'" ORDER BY `time` DESC limit 5')->queryAll();
        foreach($shares as $key=>$share)
        {
            $allShares[$key]["id"] = $share["id"];
            $allShares[$key]["user_id"] = $share["user_id"];

            $allShares[$key]["text"] = $share["text"];
            $allShares[$key]["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),$allShares[$key]["text"]);

            $allShares[$key]["attach"] = $share["attach"];
            $allShares[$key]["like_count"] = $share["like_count"];
            $allShares[$key]["comment_count"] = $share["comment_count"];
            $allShares[$key]["read_count"] = $share["read_count"];
            $allShares[$key]["time"] = $share["time"];
            $allShares[$key]["nickname"] = $user["nickname"];
            $allShares[$key]["profile_photo"] = $user["profile_photo"];
            $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
            $allShares[$key]["last_activity"] = $user["last_activity"];
        }
        $model = new ShareForm();
        $model->user_id = $id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->attach = UploadedFile::getInstance($model, 'attach');

            if($model->sendShare()){
                //$model->saveImages($model->attach);
                return $this->redirect(Url::to(['profile/home/'.$id]));
            }

        }
        $imageForm = new ImageUploadForm();

        $endTime = time() + microtime();
        $ferqTime = $endTime - $startTime;
        return $this->render('home',
            [
                 'formHasError' => false,
                 'user' => $user,
                'isOwnProfile' => $isOwnProfile,
                'shares' => $allShares,
                'model' => $model,
                'imageForm' => $imageForm,
                'ferqTime' => $ferqTime
              ]
        );

     }

    public  function actionDeactive($id)
    {
        $this->layout = 'profile-simple';
        $user = $this->findModel($id);
        if($user->deactive == 0){
            return $this->redirect(Url::to(["/u/".$id]));
        }

        return $this->render('deactive',[
             'user'  => $user,
        ]);

    }


    public  function actionPost($id)
    {
        $this->layout = 'profile-simple';
        $allComments = [];
        $share = $this->findShareModel($id);
        $share->read_count+=1;
        $share->save(false);
        $user = $this->findModel($share->user_id);
        $user->profile_photo = $user->profile_photo!=''?$user->profile_photo:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
        $smilesArray = ConversationReply::getEmojis();


        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_SHARE_COMMENT,Notification::NOT_SHARE_LIKE];
            Notification::readByTypeNotification($types,$id);
        }

        $comments =  Yii::$app->db->createCommand('SELECT `id`,`comment`,`time`,`uid` FROM share_comment WHERE sid='.$id.' ORDER BY `time` DESC')->queryAll();
        if($comments){
            foreach($comments as $k=>$c){
                $comment_user_id = $c["uid"];
                $commentUser = Yii::$app->db->createCommand('SELECT nickname,profile_photo,last_activity FROM `user` WHERE id="'.$comment_user_id.'"')->queryOne();
                $allComments[$k]['nickname'] = $commentUser["nickname"];
                $allComments[$k]['last_activity'] = $commentUser["last_activity"];
                $allComments[$k]['profile_photo'] = $commentUser["profile_photo"]!=''?$commentUser["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                $allComments[$k]["comment"] =  $c["comment"];
                $allComments[$k]["comment"] = str_replace(array_keys($smilesArray), array_values($smilesArray),  $allComments[$k]["comment"]);

                $allComments[$k]["time"] =  $c["time"];
                $allComments[$k]["user_id"] =  $c["uid"];

            }
        }

        $comment = new ShareComment();
        if ($comment->load(Yii::$app->request->post())){
            $comment->comment = User::func_strip_tags($comment->comment);
            $comment->time = time();
            $comment->sid = $id;
            $comment->uid = Yii::$app->user->id;
            if($comment->save()){
                $share->comment_count+=1;
                $share->save(false);
                Notification::setNotification($user->id,Notification::NOT_SHARE_COMMENT,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,$id);
                return $this->redirect(Url::to(['/profile/post/'.$id.'#post']));
            }
        }
        $share["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),$share["text"]);
        return $this->render('post',[
            'share' => $share,
            'user'  => $user,
            'comment' => $comment,
            'comments' => $allComments
        ]);


    }


    public  function actionImage($id)
    {
        $this->layout = 'profile-simple';
        $allComments = [];
        $image = $this->findImageModel($id);
        $image->read_count+=1;
        $image->save(false);
        $user = $this->findModel($image->user_id);
        $user->profile_photo = $user->profile_photo!=''?$user->profile_photo:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
        $smilesArray = ConversationReply::getEmojis();

        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_IMAGE_COMMENT,Notification::NOT_IMAGE_LIKE];
            Notification::readByTypeNotification($types,$id);
        }

        $comments =  Yii::$app->db->createCommand('SELECT `id`,`comment`,`time`,`user_id` FROM image_comment WHERE image_id='.$id.' ORDER BY `time` DESC')->queryAll();
        if($comments){
            foreach($comments as $k=>$c){
                $comment_user_id = $c["user_id"];
                $commentUser = Yii::$app->db->createCommand('SELECT nickname,profile_photo,last_activity FROM `user` WHERE id="'.$comment_user_id.'"')->queryOne();
                $allComments[$k]['nickname'] = $commentUser["nickname"];
                $allComments[$k]['last_activity'] = $commentUser["last_activity"];
                $allComments[$k]['profile_photo'] = $commentUser["profile_photo"]!=''?$commentUser["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                $allComments[$k]["comment"] =  $c["comment"];
                $allComments[$k]["comment"] = str_replace(array_keys($smilesArray), array_values($smilesArray),  $allComments[$k]["comment"]);

                $allComments[$k]["time"] =  $c["time"];
                $allComments[$k]["user_id"] =  $c["user_id"];

            }
        }

        $comment = new ImageComment();
        if ($comment->load(Yii::$app->request->post())){
            $comment->comment = User::func_strip_tags($comment->comment);
            $comment->time = time();
            $comment->image_id = $id;
            $comment->user_id = Yii::$app->user->id;
            if($comment->save()){
                $image->comment_count+=1;
                $image->save(false);
                Notification::setNotification($user->id,Notification::NOT_IMAGE_COMMENT,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,$id);
                return $this->redirect(Url::to(['/profile/image/'.$id.'#post']));
            }
        }

        return $this->render('image',[
            'image' => $image,
            'user'  => $user,
            'comment' => $comment,
            'comments' => $allComments
        ]);


    }
    public function actionTimeline($id)
    {
        $this->layout = 'profile-simple';

        $db = Yii::$app->db;

        $user = $this->findModel($id);

        $isOwnProfile = false;

        if ($user->id == Yii::$app->user->id)
            $isOwnProfile = true;


        $allShares = [];

        $queryCount = $db->createCommand('SELECT count(id) FROM share WHERE user_id="'.$id.'"')->queryScalar();

        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 10;

            $smilesArray = ConversationReply::getEmojis();

            $shares =  $db->createCommand('SELECT * FROM share WHERE user_id="'.$id.'" ORDER BY `id` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();

            foreach($shares as $key=>$share)
            {
                $allShares[$key]["id"] = $share["id"];
                $allShares[$key]["user_id"] = $share["user_id"];
                $allShares[$key]["text"] = $share["text"];
                $allShares[$key]["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $allShares[$key]["text"]);

                $allShares[$key]["attach"] = $share["attach"];
                $allShares[$key]["like_count"] = $share["like_count"];
                $allShares[$key]["comment_count"] = $share["comment_count"];
                $allShares[$key]["read_count"] = $share["read_count"];
                $allShares[$key]["time"] = $share["time"];
                $allShares[$key]["nickname"] = $user["nickname"];
                $allShares[$key]["profile_photo"] = $user["profile_photo"];
                $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
                $allShares[$key]["last_activity"] = $user["last_activity"];
            }

        }



        return $this->render('timeline',[
            'user' => $user,
            'shares' =>$allShares,
            'pages' => $pages,
            'isOwnProfile' => $isOwnProfile
        ]);
    }

    public function actionIndex($id)
    {
        $startTime  = time() + microtime();
        $this->layout = 'profile';

        $user = $this->findModel($id);

        if($user->deactive == 1){
            return $this->redirect(Url::to(["profile/deactive/".$id]));
        }

        $isOwnProfile = false;


        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_USER_FRIEND_REQUEST_CONFIRM,Notification::NOT_USER_FRIEND_REQUEST_REMOVE];
            Notification::readByTypeNotification($types,0,$id);
        }

        if ($user->id == Yii::$app->user->id)
            $isOwnProfile = true;

        if (!$isOwnProfile) {

            $visitExist = UserVisit::findOne(['visit_from' => Yii::$app->user->id, 'visit_to' => $user->id]);

            if (!empty($visitExist)) {

                $visitExist->time = time();
                $visitExist->seen = 0;
                $visitExist->count+=1;
                $visitExist->update(false);

            } else {
                $visit = new UserVisit();
                $visit->visit_from = Yii::$app->user->id;
                $visit->visit_to = $user->id;
                $visit->time = time();
                $visit->count = 1;
                $visit->seen = 0;
                $visit->save();
               // Notification::setNotification($user->id,Notification::NOT_USER_VISIT,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,0);
            }

        }
        $smilesArray = ConversationReply::getEmojis();
        $allShares = [];
        $shares = Yii::$app->db->createCommand('SELECT * FROM `share` WHERE `user_id`="'.$id.'" ORDER BY `time` DESC limit 5')->queryAll();
        foreach($shares as $key=>$share)
        {
            $allShares[$key]["id"] = $share["id"];
            $allShares[$key]["user_id"] = $share["user_id"];
            $allShares[$key]["text"] = $share["text"];
            $allShares[$key]["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $allShares[$key]["text"]);

            $allShares[$key]["attach"] = $share["attach"];
            $allShares[$key]["like_count"] = $share["like_count"];
            $allShares[$key]["comment_count"] = $share["comment_count"];
            $allShares[$key]["read_count"] = $share["read_count"];
            $allShares[$key]["time"] = $share["time"];
            $allShares[$key]["nickname"] = $user["nickname"];
            $allShares[$key]["profile_photo"] = $user["profile_photo"];
            $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
            $allShares[$key]["last_activity"] = $user["last_activity"];
        }
        $model = new ShareForm();
        $model->user_id = $id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->attach = UploadedFile::getInstance($model, 'attach');

            if($model->sendShare()){
                //$model->saveImages($model->attach);
                return $this->redirect(Url::to(['profile/home/'.$id]));
            }else {
            Yii::$app->session->setFlash('error',Yii::t('app','An error occurred.'));
            return $this->redirect(Url::to(['profile/home/'.$id]));

        }

        }
        $imageForm = new ImageUploadForm();

        $endTime = time()+microtime();
        $ferqTime  = $endTime - $startTime;
        return $this->render('home',
            [
                'formHasError' => false,
                'user' => $user,
                'isOwnProfile' => $isOwnProfile,
                'shares' => $allShares,
                'model' => $model,
                'imageForm' => $imageForm,
                'ferqTime' => $ferqTime
            ]
        );

    }


    public function actionVipUsers()
    {
        $this->layout  = 'column3';

        $user = User::findOne(Yii::$app->user->id);

        $users = [];

        $query  = $user->getVipUsers();

        if($query) {
         // Pagination
            $countQuery = clone  $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 20;

            $users = $query->offset($pages->offset)
                ->orderBy(['V.time'=> SORT_DESC])
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }
        return $this->render('vip', [

            'pages' => $pages,
            'users' => $users,
        ]);

    }

    public function actionUsers()
    {
        $this->layout = 'column3';

        $discoveryFilterForm        = new DiscoveryFilterForm();

        $countries = Country::find()->orderBy(['code' => SORT_ASC])->asArray()->all();

        $countries = ArrayHelper::map($countries, 'id', 'name');

        $cities = [];

        $users = [];

        $query = User::find();

        if($query){
            //pagination

            $countQuery = clone $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 20;

            $users1 = $query->offset($pages->offset)
                ->orderBy(['id'=> SORT_DESC])
                ->limit($pages->limit)
                ->asArray()
                ->all();

        }
         return $this->render('users',[
            'pages' => $pages,
            'users1' => $users1,
            'discoveryFilterForm' => $discoveryFilterForm,
            'countries' => $countries,
            'cities' => $cities,
            'users' => $users,
        ]);


    }

    public function actionSetVip()
    {

        $user = User::findOne(Yii::$app->user->id);
        if(intval($user["coins"]) >= Yii::$app->params["minCoinsForVipUser"])
        {
            if(!empty($user["profile_photo"])){
                $coins = $user->coins;

                $vipStatus = UserVip::find()
                    ->select('id,user_id,time')
                    ->where(['user_id'=>$user->id])
                    ->limit(1)
                    ->one();

                if($vipStatus == null){
                    $vipStatus = new UserVip();
                    $vipStatus -> user_id = $user->id;
                }

                $vipStatus -> time = time();
                if($vipStatus -> save(false)){

                    $user -> coins = $coins - Yii::$app->params["minCoinsForVipUser"];
                    UserActivity::activityVipUser();
                    $user->save(false);
                    Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues([":user_id" => Yii::$app->user->id,":coins" => Yii::$app->params["minCoinsForVipUser"],":type"=>1,":text" => CoinLogs::LOG_SET_VIP,":date"=>date("Y-m-d H:i:s")])->execute();
                    Yii::$app->session->setFlash('success',Yii::t('app','You are a VIP user, now. Will be active for 24 hours. Thank you!'));
                }else {
                    Yii::$app->session->setFlash('error', Yii::t('app','An error occurred.'));
                }
            }else{
                Yii::$app->session->setFlash('error',Yii::t('app','You have not profile photo.'));
            }

        }else {
            Yii::$app->session->setFlash('error',Yii::t('app','You have not enough coins.'));
        }

        return $this->redirect('/profile/vip-users/');
    }

    public function actionLiked()
    {
        $this->layout = "column3";

        $user = User::findOne(Yii::$app->user->id);

        $users = [];

        $query = $user->getLikedUsers();

        if ($query) {
            // Pagination
            $countQuery = clone $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 12;

            $users = $query->offset($pages->offset)
                ->orderBy(['L.time' => SORT_DESC])
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }

        return $this->render('liked', [

            'pages' => $pages,
            'users' => $users,
        ]);

    }

    public function actionMutualLikes()
    {
        $this->layout = "column3";
        $user = User::findOne(Yii::$app->user->id);

        $users = [];

        $query = $user->getMutualLikes();

        if ($query) {
            // Pagination
            $countQuery = clone $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 12;

            $users = $query->offset($pages->offset)
                ->orderBy(['p.time' => SORT_DESC])
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }

        return $this->render('mutual-likes', [

            'pages' => $pages,
            'users' => $users,
        ]);

    }

    public function actionLike()
    {
        $this->layout = "column3";
        $user = User::findOne(Yii::$app->user->id);

        $users = [];

        $query = $user->getLikeUsers();

        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_USER_LIKE];
            Notification::readByTypeNotification($types);
        }

        if ($query) {
            // Pagination
            $countQuery = clone $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 12;

            $users = $query->offset($pages->offset)
                ->orderBy(['L.time' => SORT_DESC])
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }

        UserLike::updateAll(['seen' => 1], 'like_to=' . Yii::$app->user->id);
        return $this->render('like', [

            'pages' => $pages,
            'users' => $users,
        ]);

    }

    public function actionFriend()
    {
        $this->layout = 'column3';
        $user = User::findOne(Yii::$app->user->id);

        $users  = [];


        $userId = Yii::$app->user->id;

        $query = $user->getFriendUsers();

        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_USER_FRIEND];
            Notification::readByTypeNotification($types);
        }

        if ($query) {
            // pagination
            $countQuery = clone $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 12;

            $users = $query->offset($pages->offset)
                 ->limit($pages->limit)
                ->asArray()
                ->all();
        }

        UserFriend::updateAll(['seen' => 1], 'user_2=' . Yii::$app->user->id);
        return $this->render('friend', [

            'pages' => $pages,
            'users' => $users,
        ]);

    }

    public function actionVisitors()
    {
        $this->layout = "column3";
        $user = User::findOne(Yii::$app->user->id);

        $users = [];

        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_USER_VISIT];
            Notification::readByTypeNotification($types);
        }

        $query = $user->getVisitors();

        if ($query) {
            // Pagination
            $countQuery = clone $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 12;

            $users = $query->offset($pages->offset)
                ->orderBy(['v.time' => SORT_DESC])
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }

        UserVisit::updateAll(['seen' => 1], 'visit_to=' . Yii::$app->user->id);
        return $this->render('visitors', [

            'pages' => $pages,
            'users' => $users,
        ]);

    }

    public function actionSetProfilePicture($id)
    {

        $id = intval($id);

        $imageThumb = UserImageThumb::findOne($id);

        $image = UserImage::findOne($id);

        $user = User::findOne(Yii::$app->user->id);

        if ($image && $imageThumb) {

            $user->profile_photo = $imageThumb->path;
            $user->profile_photo_id = $image->id;
            $user->changed_photo = 1;


            $user->save(false);
        }

        return $this->redirect(Url::to(['/profile/index', 'id' => Yii::$app->user->id]));
    }

    public function actionImageUpload()
    {
        $this->enableCsrfValidation = false;

        $imageForm = new ImageUploadForm();

        if (Yii::$app->request->isPost) {

            $images = UploadedFile::getInstances($imageForm, 'image');

            if ($images) {

                if ($imageForm->validateImages($images)) {

                    $imageForm->saveImages($images);

                    // if many photo upload increase activity
                    UserActivity::activityPhoto();

                    return \Yii::createObject([
                        'class' => 'yii\web\Response',
                        'format' => \yii\web\Response::FORMAT_JSON,
                        'data' => ['success' => Yii::t('app', 'Your images successfully uploaded.')]
                    ]);
                } else {

                    return \Yii::createObject([
                        'class' => 'yii\web\Response',
                        'format' => \yii\web\Response::FORMAT_JSON,
                        'data' => ['error' => $imageForm->errors['image']]
                    ]);
                }
            }
        }
    }

    public function actionDeleteImage($id)
    {
        $id = intval($id);

        UserImage::deleteImageById($id);

        return $this->redirect(Url::to(['/profile/index', 'id' => Yii::$app->user->id]));
    }

    public function actionDeleteGift($id)
    {
        $id = intval($id);
        $gift  = UserGift::findOne($id);
         if($gift->gift_to != Yii::$app->user->id){
            throw new BadRequestHttpException;
        } else {
           UserGift::deleteAll(['id' => $id]);
        }
        return $this->redirect(Url::to(['/gift/'. Yii::$app->user->id]));
    }

    public function actionPhotosAjax()
    {

        if (Yii::$app->request->get('action') == 'get_info'
            && (int)Yii::$app->request->get('id') > 0
            && (int)Yii::$app->request->get('data_sec_id') > 0
        ) {

            $pid = (int)Yii::$app->request->get('id');

            $image = UserImage::findOne($pid);

            $user = User::findOne((int)Yii::$app->request->get('data_sec_id'));

            $prevBtn = UserImage::getPreviousButton($pid, $user->id);

            $nextBtn = UserImage::getNextButton($pid, $user->id);

            $commentForm = false;

            $isOwnProfile = false;

            if ($user->id == Yii::$app->user->id)
                $isOwnProfile = true;
            else
                $commentForm = new AcceptCommentForm();

            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'data1' =>
                        '<span class="img-align-helper"></span><img class="fileUnitSpacer" src="' . Url::base() . $image->path . '">' .
                        $prevBtn . $nextBtn,

                    'data2' => $this->renderPartial('partials/comment_block', [

                        'user' => $user,
                        'commentForm' => $commentForm,
                        'photoId' => $pid,
                        'isOwnProfile' => $isOwnProfile
                    ])
                ]
            ]);
        }
    }

    public function actionComplete()
    {
        $this->layout = 'column3';
        $result = false;

        $citySelectForm = new CitySelectForm();
        $userData = Yii::$app->user->identity;

        if($citySelectForm->load(Yii::$app->request->post())) {
            if($citySelectForm->changeCity()) {
                $result = true;
                Yii::$app->session->setFlash('success', Yii::t('app', 'Everything successfully saved.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred.'));
            }

        }

        $countries = Country::find()->orderBy(['name' => SORT_ASC])->asArray()->all();
        $countries = ArrayHelper::map($countries,'id','name');

        $cities = [];

        if($userData->country_id){
            $citySelectForm->countryId = $userData->country_id;

            $cities = City::find()
                ->where(['country_id' => $userData->country_id])
                ->andWhere("name!='-'")
                ->orderBy(['id' => SORT_ASC])
                ->asArray()
                ->all();

            $cities = ArrayHelper::map($cities, 'id', 'name');

            if ($userData->city_id)
                $citySelectForm->cityId = $userData->city_id;
        }


        $imageForm = new ImageUploadForm();

        if (Yii::$app->request->isPost) {
            $image[] = UploadedFile::getInstance($imageForm, 'image');

            if ($image[0]) {

                if ($imageForm->validateImages($image)) {
                    $imageForm->saveImages($image);

                } else {
                    $result = false;
                    Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred.'));
                }
            }
        }

        if($result == true) {
            return $this->redirect(Url::to(['site/users']));
        }

        return $this->render('sets', [
            'userData' => $userData,
            'citySelectForm' => $citySelectForm,
            'countries' => $countries,
            'cities' => $cities,
            'imageUploadForm' => $imageForm,
         ]);
    }

    public function actionVerify()
    {
        if($this->activeUser()){
            return  $this->redirect('index');
        }
        $this->layout = 'column3';

        $model = new VerifyPhoneForm();
        $user = User::findOne(Yii::$app->user->id);
        $model->phone = substr(Yii::$app->user->identity->phone,3);
        if(Yii::$app->session->get('phone')!=null) {
            $step = 2;
        } else {
            $step = 1;
        }
        if(Yii::$app->request->post()){
            if($model->load(Yii::$app->request->post())){

                if($step == 1){
                    if(strlen(intval($model->phone)) == 9){
                        $user->phone = '994'.intval($model->phone);
                        if(Yii::$app->user->identity->phone != $user->phone and  Yii::$app->db->createCommand('SELECT count(id) FROM `user` WHERE phone="'.$user->phone.'"')->queryScalar()>0){
                            Yii::$app->session->setFlash('error','Bu nömrə artıq qeydiyyatda var, xahiş edirik başqa nömrə daxil edin.');
                            return $this->redirect('verify');
                        }
                        $user->activation_code = VerifyPhoneForm::generateCode(6);
                        $sms_text = 'Siz alochat.com saytinda qeydiyyatdan kechdiniz. Tesdiq kodunuz : '.$user->activation_code;
                        $user->save(false);
                        Yii::$app->session->set('phone',$user->phone);
                        $array =  VerifyPhoneForm::sendsms($user->phone,$sms_text);
                        return $this->redirect('verify');
                    }
                } elseif($step == 2) {
                    if(strlen($model->code)==6 and $user->activation_code == $model->code){
                        $user->verify = 1;
                        $user->save(false);
                        return $this->redirect(Url::to('/u/'.Yii::$app->user->id));
                    } else {

                        Yii::$app->session->setFlash('error','Mobil nömrənizə göndərilən təsdiq kodunu düzgün daxil edin');

                    }
                }

            }
        }


        return $this->render('verify',[
            'model' => $model,
            'step' => $step
        ]);
    }


    public function actionVerify2()
    {
        if($this->activeUser()){
            return  $this->redirect('index');
        }
        $this->layout = 'column3';
        $model = new VerifyPhoneForm();

        $user = User::findOne(Yii::$app->user->id);
        $db = Yii::$app->db;
        $ip = htmlspecialchars($_SERVER['REMOTE_ADDR']);
        $ua = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
        if(Yii::$app->session->get('phone')!=null) {
            $step = 2;
        } else {
            $step = 1;
        }
        if(Yii::$app->request->post()){
            if($model->load(Yii::$app->request->post())){
                if($step == 1){
                    if(strlen(intval($model->phone)) == 9){
                        $phone = '994'.intval($model->phone);
                        if(Yii::$app->db->createCommand('SELECT count(id) FROM `user` WHERE phone=:phone')->bindValue(':phone',$phone)->queryScalar()>0){
                            Yii::$app->session->setFlash('error','Bu nömrə artıq qeydiyyatda var, xahiş edirik başqa nömrə daxil edin.');
                            return $this->redirect('verify2');
                        }
                        $checkIpConfirm = $db->createCommand("SELECT count(`id`) FROM `sms_regconfirm` WHERE `ip` = :ip AND `reg` = '0' AND `date` > :date")
                            ->bindValues([
                                ":ip" => $ip,
                                ":date" => (time() - 1800)
                            ])
                            ->queryScalar();
                        if($checkIpConfirm > 5) {
                            $error = "Bir neçe deqiqe sonra yeniden yoxlayın.";
                            Yii::$app->session->setFlash('error',$error);
                            return $this->redirect('verify2');
                        }
                        $activation_code = VerifyPhoneForm::generateCode(6);
                        $sms_text = 'Siz alochat.com saytinda qeydiyyatdan kechdiniz. Tesdiq kodunuz : '.$activation_code;
                        Yii::$app->session->set('phone',$phone);
                        $array =  VerifyPhoneForm::sendsms($phone,$sms_text);
                        $db->createCommand("INSERT INTO `sms_regconfirm` SET `phone` = :phone, `pass` = :code, `ip` = :ip, `ua` = :ua, `date` = :date")
                            ->bindValues([
                                ":phone" => $phone,
                                ":code" => $activation_code,
                                ":ip" => $ip,
                                ":ua" => $ua,
                                ":date" => time()
                            ])
                            ->execute();
                        return $this->redirect('verify2');
                    }
                }elseif($step == 2){
                    $regconfirm = $db->createCommand('SELECT * FROM sms_regconfirm WHERE phone=:phone ORDER BY `id` DESC')->bindValues([":phone"=>Yii::$app->session->get('phone')])->queryOne();
                    if(strlen($model->code)==6 and $regconfirm["pass"] == $model->code){
                        $user->phone = Yii::$app->session->get('phone');
                        $user->verify = 1;
                        $user->save(false);
                        $db->createCommand("UPDATE `sms_regconfirm` SET `reg` = '1' WHERE `phone` = :phone ORDER BY `id` DESC LIMIT 1")->bindValue(':phone',Yii::$app->session->get('phone'))->execute();

                        return $this->redirect(Url::to('/u/'.Yii::$app->user->id));
                    } else {

                        Yii::$app->session->setFlash('error','Mobil nömrənizə göndərilən təsdiq kodunu düzgün daxil edin');
                    }
                }
            }
        }



        return $this->render('verify',[
            'model' => $model,
            'step' => $step
        ]);
    }


    public function actionVerify1()
    {
        if($this->activeUser()){
            return  $this->redirect('index');
        }
        $this->layout = 'column3';

        $model = new VerifyPhoneForm();
        $user = User::findOne(Yii::$app->user->id);
        $model->phone = substr(Yii::$app->user->identity->phone,3);
        if(Yii::$app->session->get('phone')!=null) {
            $step = 2;
        } else {
            $step = 1;
        }
        if(Yii::$app->request->post()){
            if($model->load(Yii::$app->request->post())){

                if($step == 1){
                    if(strlen(intval($model->phone)) == 9){
                        $phone = '994'.intval($model->phone);
                        if(Yii::$app->user->identity->phone != $phone and  Yii::$app->db->createCommand('SELECT count(id) FROM `user` WHERE phone="'.$user->phone.'"')->queryScalar()>0){
                            Yii::$app->session->setFlash('error','Bu nömrə artıq qeydiyyatda var, xahiş edirik başqa nömrə daxil edin.');
                            return $this->redirect('verify');
                        }
                        $ip = htmlspecialchars('REMOTE_ADDR');
                        $ua = htmlspecialchars(getenv('HTTP_USER_AGENT'));

                        $checkIpConfirm = Yii::$app->db->createCommand("SELECT count(`id`) FROM `admin_alochat`.`sms_regconfirm` WHERE `ip` = '".$ip."' AND `reg` = '0' AND `date` > '".(time() - 1800)."'")->queryScalar();
                        if($checkIpConfirm > 5)
                        {
                            Yii::$app->session->setFlash('error','Bir neçe deqiqe sonra yeniden yoxlayın.');
                            return $this->redirect('verify');
                        }
                        $user->activation_code = VerifyPhoneForm::generateCode(6);
                        $checkMobileConf = Yii::$app->db->createCommand("SELECT count(`id`) FROM `admin_alochat`.`sms_regconfirm` WHERE `phone` = '".$user->phone."' AND `date` > '".(time() - 3600)."'")->queryScalar();

                        if($checkMobileConf == 0){
                            $sms_text = 'Siz alochat.com saytinda qeydiyyatdan kechdiniz. Tesdiq kodunuz : '.$user->activation_code;
                            $user->save(false);
                             Yii::$app->session->set('phone',$user->phone);

                            $array =  VerifyPhoneForm::sendsms($user->phone,$sms_text);
                            Yii::$app->db->createCommand("INSERT INTO `admin_alochat`.`sms_regconfirm` SET `phone` = '".$user->phone."', `pass` = '".$user->activation_code."', `ip` = '".$ip."', `ua` = '".$ua."', `date` = '".time()."'");
                            return $this->redirect('verify');
                        }

                    }
                } elseif($step == 2) {
                    if(strlen($model->code)==6 and $user->activation_code == $model->code){
                        $user->verify = 1;
                        $user->save(false);
                        return $this->redirect(Url::to('/u/'.Yii::$app->user->id));
                    } else {

                        Yii::$app->session->setFlash('error','Mobil nömrənizə göndərilən təsdiq kodunu düzgün daxil edin');

                    }
                }

            }
        }


        return $this->render('verify',[
            'model' => $model,
            'step' => $step
        ]);
    }


    protected function activeUser(){
        if(Yii::$app->user->identity->verify == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function actionSettings()
    {

        $this->layout = 'column3';
        $form = new ProfileSettingsForm();

        $citySelectForm = new CitySelectForm();
        $userData = Yii::$app->user->identity;
        if ($form->load(Yii::$app->request->post())) {

            if ($form->changeSettings()) {
                UserActivity::activityAbout();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Everything successfully saved.'));

            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred.'));
            }

            return $this->redirect(Url::to(['profile/settings']));
        }

        if ($citySelectForm->load(Yii::$app->request->post())) {

            if ($citySelectForm->changeCity()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Everything successfully saved.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred.'));
            }

            return $this->redirect(Url::to(['profile/settings']));
        }

        $form->full_name = $userData->full_name;
        $form->about = $userData->about;
        $form->age = $userData->age;
        $form->sex = $userData->sex;
        $form->phone = $userData->phone;
        $form->only_friend = $userData->only_friend;

        $countries = Country::find()->orderBy(['name' => SORT_ASC])->asArray()->all();
        $countries = ArrayHelper::map($countries, 'id', 'name');


        $cities = [];
        if ($userData->country_id) {
            $citySelectForm->countryId = $userData->country_id;

            $cities = City::find()
                ->where(['country_id' => $userData->country_id])
                ->andWhere("name!='-'")
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();

            $cities = ArrayHelper::map($cities, 'id', 'name');

            if ($userData->city_id)
                $citySelectForm->cityId = $userData->city_id;
        }
        return $this->render('settings', [

            'formUser' => $form,
            'userData' => $userData,
            'citySelectForm' => $citySelectForm,
            'countries' => $countries,
            'cities' => $cities,
            'passwordChangeForm' => $userData->social_login == 0 ? new PasswordChangeForm() : ''
        ]);
    }

    public function actionAskUploadImage($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $id = intval($id);

            if ($id > 0) {

                $askExist = UserPhotoUploadAsk::findOne(['user_from' => Yii::$app->user->id, 'user_to' => $id]);


                $userTwo = User::findOne($id);

                $message = Yii::$app->user->identity->full_name . ' ' . Yii::t('app', 'asked you upload photo');

                if (!$askExist && $userTwo && ConversationReply::sendMessage(Yii::$app->user->id, $id, $message))

                    $ask = new UserPhotoUploadAsk();

                $ask->user_from = Yii::$app->user->id;

                $ask->user_to = $id;

                $ask->save();

                return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => Response::FORMAT_JSON,
                    'data' => ['success' => 1]
                ]);
            }
        }
    }


    public  function actionLikeShare($id)
    {
        $request = Yii::$app->request;

        if($request->isAjax) {
            $id = intval($id);
            if($id > 0) {
                $share = Share::findOne($id);

                if($share) {
                    $res  = 0;

                    $liked = ShareLike::findOne(['uid' => Yii::$app->user->id, 'sid' => $id]);

                    if($liked) {
                        $liked->delete();
                        $share->like_count = $share->like_count -1 ;
                        $share->save(false);
                        $res = 2;
                    } else {
                        $userLike = new ShareLike();
                        $userLike->sid = $id;
                        $userLike->uid = Yii::$app->user->id;
                        $userLike->time = time();
                        $userLike->save();

                        if($userLike->id) {
                            $share->like_count = $share->like_count +1 ;
                            $share->save(false);
                            $res = 1;
                            Notification::setNotification($share->user_id,Notification::NOT_SHARE_LIKE,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,$id);

                        }
                    }


                    if ($res > 0)
                        return \Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => Response::FORMAT_JSON,
                            'data' => ['success' => $res,'likeCount' => $share->like_count]
                        ]);
                }
            }

        }
    }

    public  function actionLikeImage($id)
    {
        $request = Yii::$app->request;

        if($request->isAjax) {
            $id = intval($id);
            if($id > 0) {
                $image = UserImage::findOne($id);

                if($image) {
                    $res  = 0;

                    $liked = ImageLike::findOne(['user_id' => Yii::$app->user->id, 'image_id' => $id]);

                    if($liked) {
                        $liked->delete();
                        $image->like_count = $image->like_count -1 ;
                        $image->save(false);
                        $res = 2;
                    } else {
                        $userLike = new ImageLike();
                        $userLike->image_id = $id;
                        $userLike->user_id = Yii::$app->user->id;
                        $userLike->time = time();
                        $userLike->save();

                        if($userLike->id) {
                            $image->like_count = $image->like_count +1 ;
                            $image->save(false);
                            $res = 1;
                            Notification::setNotification($image->user_id,Notification::NOT_IMAGE_LIKE,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,$id);

                        }
                    }


                    if ($res > 0)
                        return \Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => Response::FORMAT_JSON,
                            'data' => ['success' => $res,'likeCount' => $image->like_count]
                        ]);
                }
            }

        }
    }

    public function actionBlockUser($id)
    {
        $request = Yii::$app->request;

        if($request->isAjax) {
            $id = intval($id);
            if($id > 0) {
                $userTo = User::findOne($id);

                if($userTo) {
                    $res  = 0;

                    $blocked = UserBlock::findOne(['block_from' => Yii::$app->user->id, 'block_to' => $id]);
                    $conversation = Conversation::checkConversationExist(Yii::$app->user->id,$id);

                    if($blocked) {
                        $blocked->delete();
                        if($conversation) {
                            $conversation->blocked = 0;
                            $conversation->save(false);
                        }
                        $res = 2;
                        Yii::$app->session->setFlash('success','Blokdan çıxarıldı');

                    } else {
                        $userBlock = new UserBlock();
                        $userBlock->block_to = $id;
                        $userBlock->block_from = Yii::$app->user->id;
                        $userBlock->time = time();
                        if($userBlock->save()){
                            if($conversation) {
                                $conversation->blocked = 1;
                                $conversation->save(false);
                            }
                        }

                        if($userBlock->id) {
                            Yii::$app->session->setFlash('success','Blok edildi');
                            $res = 1;
                        }
                    }


                    if ($res > 0)
                        return \Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => Response::FORMAT_JSON,
                            'data' => ['success' => $res]
                        ]);
                }
            }

        }


    }

    public function actionReportUser($id)
    {
        $request = Yii::$app->request;

        if($request->isAjax) {
            $id = intval($id);
            if($id > 0) {
                $userTo = User::findOne($id);

                if($userTo) {
                    $res  = 0;

                    $reported = UserReport::findOne(['report_from' => Yii::$app->user->id, 'report_to' => $id]);

                    if($reported) {
                        $reported->delete();
                        $res = 2;

                        if($userTo->report_count > 0 )  $userTo->report_count = $userTo->report_count - 1;

                        Yii::$app->session->setFlash('success','Şikayət geri çəkildi');

                    } else {
                        $userReport = new UserReport();
                        $userReport->report_to = $id;
                        $userReport->report_from = Yii::$app->user->id;
                        $userReport->time = time();
                        if($userReport->save()){
                             $userTo->report_count = $userTo->report_count + 1;
                            if($userTo->report_count % 5 == 0){
                                Conversation::sendBySystemMessage($id,'Sizin profilinizi '.$userTo->report_count.' istifadəçi şikayət edib. Zəhmət olmasa profilinizə göz gəzdirin. Əks halda bu şikayətlər sizin istifadəçilər arasında aktivliyinizə təsir edəcək və arxa sıralarda görsənəcəksiniz!');
                            }

                        }

                        if($userReport->id) {
                            Yii::$app->session->setFlash('success','Şikayət edildi');
                            $res = 1;
                        }
                    }

                    $userTo->save(false);


                    if ($res > 0)
                        return \Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => Response::FORMAT_JSON,
                            'data' => ['success' => $res]
                        ]);
                }
            }

        }


    }


    public function actionConfirmFriend($id)
    {
        $request = Yii::$app->request;

        if($request->isAjax){
            $id = intval($id);

            if($id > 0) {
                $userTo = User::findOne($id);

                if($userTo) {
                    $res = 0;

                    $isFriend = UserFriend::find()->where('(user_1='.Yii::$app->user->id.' and user_2='.$id.') or (user_2='.Yii::$app->user->id.' and user_1='.$id.')')->one();

                    if($isFriend) {

                        $isFriend->ok =  1;
                        $isFriend->ok_time = time();
                        if($isFriend->save(false)){
                            Yii::$app->session->setFlash('success','Dostluq teklifi qebul edildi');
                            $res = 2;
                            Notification::setNotification($id,Notification::NOT_USER_FRIEND_REQUEST_CONFIRM,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,0);
                        }
                    }


                    if ($res > 0)
                        return \Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => Response::FORMAT_JSON,
                            'data' => ['success' => $res]
                        ]);
                }
            }
        }

    }

    public function actionAddFriend($id)
    {

        $request = Yii::$app->request;

        if($request->isAjax) {
            $id = intval($id);

            if($id > 0) {


                $userTo = User::findOne($id);

                if($userTo) {
                    $res  = 0;

                    $isFriend = UserFriend::find()->where('(user_1='.Yii::$app->user->id.' and user_2='.$id.') or (user_2='.Yii::$app->user->id.' and user_1='.$id.')')->one();
                    $blocked = UserBlock::findOne(['block_to' => Yii::$app->user->id, 'block_from' => $id]);

                    if($isFriend) {

                        $isFriend->delete();
                        Yii::$app->session->setFlash('success','Dostluqdan cixarildi');
                        Notification::setNotification($id,Notification::NOT_USER_FRIEND_REQUEST_REMOVE,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,0);

                        $res = 2;
                    } else {
                        if($blocked){
                            $res = 0;
                            Yii::$app->session->setFlash('error','Siz blok oldugunuzdan dostluq teklifi gondere bilmezsiniz');

                        } else {
                            $addFriend = new UserFriend();
                            $addFriend->user_1 = Yii::$app->user->id;
                            $addFriend->user_2 = $id;
                            $addFriend->ok = 0;
                            $addFriend->ok_time = 0;
                            $addFriend->seen = 0;
                            $addFriend->request_time = time();
                            if($addFriend->save()){
                                if($addFriend->id) {
                                    $res = 1;
                                    Yii::$app->session->setFlash('success','Dostlug teklifi gonderildi');
                                    Notification::setNotification($id,Notification::NOT_USER_FRIEND,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,0);

                                }
                            }
                        }



                    }


                    if ($res > 0)
                        return \Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => Response::FORMAT_JSON,
                            'data' => ['success' => $res]
                        ]);

                }
            }

        }
    }

    public function actionLikeUser($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $id = intval($id);

            if ($id > 0) {

                $userTo = User::findOne($id);

                if ($userTo) {

                    $res = 0;

                    $liked = UserLike::findOne(['like_from' => Yii::$app->user->id, 'like_to' => $id]);

                    if ($liked) {

                        $liked->delete();
                        if($userTo->like_count>0) $userTo->like_count = $userTo->like_count - 1;
                        if($userTo->top_like_count>0) $userTo->top_like_count = $userTo->top_like_count - 1;
                        $res = 2;
                    } else {
                        $userLike = new UserLike();

                        $userLike->like_from = Yii::$app->user->id;
                        $userLike->like_to = $userTo->id;
                        $userLike->time = time();
                        $userLike->save();

                        if ($userLike->id){
                            UserActivity::activityLike();
                            $res = 1;
                            $userTo->like_count = $userTo->like_count + 1;
                            $userTo->top_like_count = $userTo->top_like_count + 1;

                            Notification::setNotification($userTo->id,Notification::NOT_USER_LIKE,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,0);


                        }
                    }
                    $userTo->save(false);
                    $likeCount = Yii::$app->db->createCommand('SELECT count(id) FROM user_like WHERE like_to="'.$id.'"')->queryScalar();

                    if ($res > 0)
                        return \Yii::createObject([
                            'class' => 'yii\web\Response',
                            'format' => Response::FORMAT_JSON,
                            'data' => ['success' => $res,'likeCount' => $likeCount]
                        ]);
                }
            }
        }
    }

    public function actionAcceptComment()
    {

        // $this->enableCsrfValidation = false;

        $request = Yii::$app->request;

        $commentForm = new AcceptCommentForm();

        $commentForm->photo_id = $request->post('photo_id');

        $commentForm->text = strip_tags($request->post('text'));

        if ($request->isAjax) {

            if ($commentForm->acceptComment() && !$commentForm->errors) {

                return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => Response::FORMAT_JSON,
                    'data' => ['success' => Yii::t('app', 'The comment has been sent.')]
                ]);
            } else {

                return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => Response::FORMAT_JSON,
                    'data' => ['error' => $commentForm->errors]
                ]);
            }
        } else {

            throw new BadRequestHttpException;
        }

    }

    public function actionGetCities()
    {

        $request = Yii::$app->request;

        $countryId = intval($request->get('country_id'));

        $with_prompt = intval($request->get('with_prompt'));

        if ($request->isAjax) {

            $cities = [];
            if ($countryId) {

                $cities = City::find()
                    ->select(['id', 'name'])
                    ->where(['country_id' => $countryId])
                    ->andWhere("name!='-'")
                    ->orderBy(['name' => SORT_DESC])
                    ->asArray()
                    ->all();
            }
            if ($with_prompt) {
                array_push($cities, ['id' => 0, 'name' => '---']);
                $cities = array_reverse($cities);
            }

            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => Response::FORMAT_JSON,
                'data' => $cities
            ]);

        } else {

            throw new BadRequestHttpException;
        }

    }

    public function actionUpdatePassword()
    {
        if (Yii::$app->user->identity->social_login)
            throw new ForbiddenHttpException;

        $form = new PasswordChangeForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate() && $form->updatePassword()) {
            $md5_pass = md5($form->password);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Your password successfully changed.'));

        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Incorrect password.'));

        }
        return $this->redirect(Url::to(['/profile/settings']));
    }

    public function actionSiteSettings()
    {
        $this->layout = 'column3';
        $languages = [

            'az' => ['short' => 'AZ', 'full' => 'Azərbaycan'],
            'tr' => ['short' => 'TR', 'full' => 'Türkçe'],
            'en' => ['short' => 'EN', 'full' => 'English'],
            'ru' => ['short' => 'RU', 'full' => 'Русский'],

        ];

        return $this->render('site-settings',['languages'=>$languages]);

    }

    public function  actionDeleteShare($id)
    {
        $share = $this->findOwnShareModel($id);

        if($share->attach!=""){
            $file_path = date("Ym",$share->time);
            $file_resized =  Yii::$app->basePath.'/../public_html/images/share/resized/'.$file_path.'/'.$share->attach;
            $file_thumbs =   Yii::$app->basePath.'/../public_html/images/share/thumbs/'.$file_path.'/'.$share->attach;
            $file_uploads =  Yii::$app->basePath.'/../public_html/images/share/uploads/'.$file_path.'/'.$share->attach;

            if(file_exists($file_resized)) unlink($file_resized);
            if(file_exists($file_thumbs)) unlink($file_thumbs);
            if(file_exists($file_uploads)) unlink($file_uploads);
        }



        Share::deleteAll('id=:id',[":id" => $id]);


        ShareComment::deleteAll('sid=:id',[":id" => $id]);

        ShareLike::deleteAll('sid=:id',[":id" => $id]);

        return $this->redirect(['/u/'.Yii::$app->user->id]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Requested page not found.'));
        }
    }


    protected function findShareModel($id)
    {
        if (($model = Share::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Requested page not found.'));
        }
    }

    protected function findImageModel($id)
    {
        if (($model = UserImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Requested page not found.'));
        }
    }

    protected function findOwnShareModel($id)
    {
        if (($model = Share::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Requested page not found.'));
        }
    }


    public function actionTest()
    {

    }

    public function actionGift($id)
    {
         $this->layout = 'column3';

        $user = $this->findModel($id);


        $isOwnProfile = false;

        if($user->id  == Yii::$app->user->id)
            $isOwnProfile = true;


        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_USER_GIFT];
            Notification::readByTypeNotification($types);
        }

        $name = 'name_'.Yii::$app->language;
        $categories = GiftCategory::findBySql('select '.$name.' as name, id ,(select count(id) from gift where gift_category.id = gift.category_id) as count from gift_category where status = 1 and (select count(id) from gift where gift_category.id = gift.category_id)>0')->asArray()->all();
        $gifts  = GiftCategory::find()
            ->select('gift.*')
            ->from('gift_category')
            ->leftJoin('gift','gift_category.id = gift.category_id')
            ->asArray()
            ->where('gift_category.status = 1')
            ->all();

        $userGifts = UserGift::find()
            ->select('user.full_name,gift.icon,user.sex,user_gift.time,user_gift.comment,user_image_thumb.path,user_gift.id,user_gift.gift_from as user_id')
            ->from('user_gift')
            ->leftJoin('gift','user_gift.gift_id = gift.id')
            ->leftJoin('user','user_gift.gift_from = user.id')
            ->leftJoin('user_image_thumb','user_image_thumb.id = user.profile_photo_id')
            ->where('user_gift.gift_to = '.$user->id)
             ->limit(20)
            ->orderBy(['user_gift.time' => SORT_DESC])
            ->asArray()
            ->all();

        if($isOwnProfile){
            UserGift::updateAll(['seen' => 1], 'gift_to=' . Yii::$app->user->id);
        }


        return $this->render('gift',
            [
                'id' => $id,
                'formHasError' => false,
                 'user' => $user,
                'isOwnProfile' => $isOwnProfile,
                'categories' => $categories,
                'gifts' => $gifts,
                'userGifts' => $userGifts
             ]
        );

    }

    public function actionGifts($id)
    {

        $this->layout = 'profile-simple';

        $user = $this->findModel($id);

        $isOwnProfile = false;

        if($user->id  == Yii::$app->user->id)
            $isOwnProfile = true;


        if($user->deactive==1){
            return $this->redirect("/profile/deactive/".$user["id"]);
        }


        $name = 'name_'.Yii::$app->language;
        $categories = GiftCategory::findBySql('select '.$name.' as name, id ,(select count(id) from gift where gift_category.id = gift.category_id) as count from gift_category where status = 1 and (select count(id) from gift where gift_category.id = gift.category_id)>0 ORDER by gift_category.id DESC')->asArray()->all();
        $gifts  = GiftCategory::find()
            ->select('gift.*')
            ->from('gift_category')
            ->leftJoin('gift','gift_category.id = gift.category_id')
            ->where('gift_category.status = 1')
             ->asArray()
            ->all();

        $userGifts = UserGift::find()
            ->select('user.full_name,gift.icon,user.sex,user_gift.time,user_gift.comment,user_image_thumb.path,user_gift.id,user_gift.gift_from as user_id')
            ->from('user_gift')
            ->leftJoin('gift','user_gift.gift_id = gift.id')
            ->leftJoin('user','user_gift.gift_from = user.id')
            ->leftJoin('user_image_thumb','user_image_thumb.id = user.profile_photo_id')
            ->where('user_gift.gift_to = '.$user->id)
            ->limit(20)
            ->orderBy(['user_gift.time' => SORT_DESC])
            ->asArray()
            ->all();

        if($isOwnProfile){
            UserGift::updateAll(['seen' => 1], 'gift_to=' . Yii::$app->user->id);
        }


        return $this->render('gifts',
            [
                'id' => $id,
                'formHasError' => false,
                'user' => $user,
                'isOwnProfile' => $isOwnProfile,
                'categories' => $categories,
                'gifts' => $gifts,
                'userGifts' => $userGifts
            ]
        );

    }

    public function actionModal()
    {
        $giftId = intval($_POST["modalId"]);
        $userId = intval($_POST["userId"]);

        $gift  = Gift::findOne($giftId);
        $user = User::findOne($userId);
        $userGift = new UserGift();
        $userImage = null;
        if(intval($user['profile_photo_id']))
        $userImage = UserImageThumb::find()->select('path')->where('id='.$user['profile_photo_id'])->one();
         if($userImage == null){
             if($user->sex == User::SEX_MAN)
             {
                 $userImage['path'] = '/images/icons/male_0.png';
             } elseif($user->sex == User::SEX_WOMAN){
                 $userImage['path'] = '/images/icons/female_0.png';
             }
        }
             echo '<div style="text-align: center">
                        <img src="'.$gift["icon"].'" height="100" width="100">
                        <img src="/images/icons/to.png" style="margin-right: 5px">
                        <img src="'.$userImage['path'].'" class="img-rounded" height="100" width="100">
                </div>
                 <br />
                <hr />
                <br />
                ';
        echo '<h4>'.Yii::t('app','You can write notes with a gift').'</h4>';

        if($gift->coin > Yii::$app->user->identity->coins){
            echo '
            <div class="form-group field-usergift-comment">
             <textarea id="usergift-comment" class="form-control" readonly>Mənim bu hədiyyəmi qebul edin</textarea>

             </div>            <div style="text-align: right">'.
                Html::button(Yii::t('app','Send'),['class'=> 'btn btn-large btn-default']).'</div>  <p style="color: red">'.Yii::t('app','You have not enough {coin} coins',['coin' =>$gift['coin'] ]).'</p>';
        } else {
            $form =  ActiveForm::begin(['action' => '/profile/send-gift']);
            echo $form->field($userGift,'comment')->textarea(['value' => 'Mənim bu hədiyyəmi qebul edin']);
            echo $form->field($userGift,'gift_to')->hiddenInput(['value' => $userId])->label(false);
            echo $form->field($userGift,'gift_id')->hiddenInput(['value' => $giftId])->label(false);
            echo '<div style="text-align: right">'.
                Html::submitButton(Yii::t('app','Send'),['class'=> 'btn btn-large btn-primary']).'</div>';
            ActiveForm::end();
            echo '
            <p>'.Yii::t('app','Price: {coin} Coins',['coin' => $gift['coin']]).'</p>';
        }

    }

    public function actionSendGift()
    {
        $userGift = new UserGift();

        if($userGift->load(Yii::$app->request->post())){
            $user = User::findOne(Yii::$app->user->id);
            $gift = Gift::findOne($userGift->gift_id);

            $userGift->time = time();
            $userGift->gift_from = Yii::$app->user->id;
            if($user->coins >= $gift->coin and  $userGift->save()){
                $user->coins = $user->coins - $gift->coin;
                $user->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app','Gift sent.'));
                Notification::setNotification($userGift->gift_to,Notification::NOT_USER_GIFT,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,0,0);

                return $this->redirect('/profile/gifts/'.$userGift->gift_to);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app','An error occurred.'));
                return $this->redirect('/profile/gifts/'.$userGift->gift_to);
            }
        }

        return $this->redirect('/gift/'.Yii::$app->user->id);

    }


    public function actionStatusUpdate()
    {
        if(Yii::$app->request->isAjax){
            $status  = $_POST['last_post'];
            $user = User::findOne(Yii::$app->user->id);

            $user->last_post = User::filterword(User::func_strip_tags($status)) ;
            if($user->save(false)){
                $data = ['success' => 'Ugurla tamamlandi'];
                print_r(json_encode($data));
            } else {
                throw new ConflictHttpException(400,'Invalid request. Please do not repeat this request again.');
            }
        }
    }

    public function actionImages($id)
    {
        $this->layout = 'profile';

        $user = $this->findModel($id);

        $isOwnProfile = false;
        if($user->deactive == 1){

            return $this->redirect(Url::to(["profile/deactive/".$id]));
        }

        if ($user->id == Yii::$app->user->id)
            $isOwnProfile = true;

        if (!$isOwnProfile) {

            $visitExist = UserVisit::findOne(['visit_from' => Yii::$app->user->id, 'visit_to' => $user->id]);

            if (!empty($visitExist)) {

                $visitExist->time = time();
                $visitExist->seen = 0;
                $visitExist->count+=1;
                $visitExist->update(false);

            } else {
                $visit = new UserVisit();
                $visit->visit_from = Yii::$app->user->id;
                $visit->visit_to = $user->id;
                $visit->time = time();
                $visit->count = 1;
                $visit->seen = 0;
                $visit->save();
            }

        }
        $smilesArray = ConversationReply::getEmojis();

        $allImages = [];

        $images = Yii::$app->db->createCommand('SELECT * FROM `user_image` WHERE user_id=:id ORDER BY `add_date` DESC')->bindValues([":id" => $id])->queryAll();

        foreach($images as $key=>$image){
            $allImages[$key]["id"] = $image["id"];
            $allImages[$key]["path"] = $image["path"];
            $allImages[$key]["time"] = $image["add_date"];
            $allImages[$key]["comment_count"] = $image["comment_count"];
            $allImages[$key]["like_count"] = $image["like_count"];
            $allImages[$key]["nickname"] = $user["nickname"];
            $allImages[$key]["profile_photo"] = $user["profile_photo"];
            $allImages[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
            $allImages[$key]["last_activity"] = $user["last_activity"];
        }


        $imageForm = new ImageUploadForm();
        return $this->render('images',
            [
                'formHasError' => false,
                'user' => $user,
                'isOwnProfile' => $isOwnProfile,
                'images' => $allImages,
                'imageForm' => $imageForm
            ]
        );
    }

}
