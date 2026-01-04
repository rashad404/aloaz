<?php
namespace frontend\controllers;

use common\models\City;
use common\models\Compotetion;
use common\models\Conversation;
use common\models\ConversationReply;
use common\models\Country;
use common\models\Notification;
use common\models\Share;
use common\models\ShareComment;
use common\models\User;
use common\models\UserActivity;
use common\models\UserFriend;
use common\models\UserImage;
use common\models\UserSearch;
use common\models\UserVip;
use dosamigos\datepicker\DatePicker;
use frontend\models\DiscoveryFilterForm;
use frontend\models\SearchUser;
use frontend\models\ShareForm;
use frontend\models\UserFilter;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;

use yii\data\Pagination;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Session;
use yii\web\Cookie;
use common\models\Auth;
use yii\web\UploadedFile;

/*
 *  @var $this User
 * */
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','ping','discovery','users','request-password-reset','test','rp','shares','login-alo','privacy-policy','yusif','manis'],
                'rules' => [
                    [
                            'actions' => ['signup','terms','request-password-reset','rp','privacy-policy','yusif','manis'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login-alo'],
                        'allow' => true,
                        'roles' => ['*'],
                    ],
                    [
                        'actions' => ['logout', 'ping', 'discovery','users','test','shares'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }


    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
       // var_dump($attributes); exit;
       // echo '<img src="https://graph.facebook.com/'.$attributes["id"].'/picture?width=590&height=600">';
        /** @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();
         if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                  $user = $auth->user;
                 Yii::$app->user->login($user);
            } else { // signup
                if (isset($attributes['email']) && isset($attributes['username']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);

                    $attrEmail = '';
                    $attrFullName = '';
                    $attrGender = 0;


                    if(isset($attributes['email'])){
                        $attrEmail = $attributes['email'];

                    }elseif(isset($attributes['emails'])){
                        $attrEmail = $attributes['emails'][0]['value'];
                    }

                    if(isset($attributes['name']) && !isset($attributes['displayName'])){
                        $attrFullName = $attributes['name'];

                    }elseif(isset($attributes['displayName'])){
                        $attrFullName = $attributes['displayName'];

                    }
                    if(isset($attributes['gender'])){
                        $attrGender =  $attributes['gender'] == 'male' ? User::SEX_MAN : User::SEX_WOMAN;
                    }


                    $user = new User([
                        'full_name' => $attrFullName,
                        'nickname' => User::getNickname($attrFullName),
                        'email' => $attrEmail,
                        'sex' => $attrGender,
                        'password' => $password,
                        'age' => 18,
                        'role' => User::ROLE_USER,
                        'social_login' => 1,
                        'last_post' => '',
                        'phone' => '',
                        'about' => '',

                    ]);

                    if($client->getId()=='facebook'){
                        $user->regfrom = 'facebook';
                    }else {
                        $user->regfrom = 'google';

                    }


                    $ip = Yii::$app->ipgeobase->getIP();

                    $geoData = Yii::$app->ipgeobase->getLocation($ip);

                    $countryId = 0;
                    $cityId = 0;



                    if(isset($geoData['country_id']))
                        $countryId = intval($geoData['country_id']);

                    if(isset($geoData['city_id']))
                        $cityId = intval($geoData['city_id']);

                    if ($countryId)
                        $user->country_id = $countryId;

                    if ($cityId && isset($geoData['city']) && $geoData['city'] != '-')
                        $user->city_id = $cityId;

                    //$user->nickname = User::getNickname($attrFullName);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();

                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {

                        if($client->getId()=='facebook'){
                            $facebook_id = $attributes["id"];
                            UserImage::saveFacebookImage($facebook_id,$user->id);
                        }
                        User::sendWelcomeMessage($user->id);
                        // image upload

                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                        } else {


                            Yii::$app->getSession()->setFlash('error', Yii::t('app', "Something went wrond.Pleas try again later"));
                        }
                    } else {

                        $errorText = '';

                        if($user->errors){

                            foreach($user->errors as $error){
                                $errorText.=$error[0];
                            }
                        }
                        if($errorText == '')
                            $errorText = Yii::t('app', "Something went wrond.Pleas try again later");

                        Yii::$app->getSession()->setFlash('error',$errorText );
                    }
                }
            }
        } else { // user already logged in

            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }

    }

    public function actionBlock()
    {
        $this->layout = 'home';

        $times = [
            '0' => 'bitib',
            '60' => '1 dəq',
            '300' => '5 dəq',
            '900' => '15 dəq',
            '1800' => '30 dəq',
            '3600' => '1 saat',
            '43200' => '12 saat',
            '86400' => '24 saat',
            '604800' => '1 həftə',
            '2592000' => '1 ay',
            '31104000' => '12 ay',
        ];


        $user = User::findOne(Yii::$app->user->id);
        $block = Yii::$app->db->createCommand('select * from blocks where user_id=:id and time>0 ORDER by id desc')->bindValue(':id',$user->id)->queryOne();
        if(($user->block_begin_time+$user->block_time<time()) and $user->block_begin_time!=0){
            $user->block_begin_time = 0;
            $user->block_time = 0;
            Yii::$app->db->createCommand('UPDATE blocks SET end_time=:end_time WHERE user_id=:user_id ORDER BY id DESC')->bindValues([':end_time' => time(),':user_id'=>Yii::$app->user->id])->execute();
            $user->save(false);
            return $this->redirect('/site/users');

        }else {
            if($user->block_begin_time == 0){
                $user->block_begin_time = time();
                $user->save(false);
                Yii::$app->db->createCommand('UPDATE blocks SET begin_time=:begin_time WHERE user_id=:user_id ORDER BY id DESC')->bindValues([':begin_time' => time(),':user_id'=>Yii::$app->user->id])->execute();
                return $this->redirect('/site/users');
            }
        }

        $timeString = '';
        if(array_key_exists($user->block_time,$times)){
            $timeString  = $times[$user->block_time];
        }
        return $this->render('block',[
            'user' => $user,
            'block' => $block,
            'timeString' => $timeString
        ]);
    }

    public function actionRegister()
    {
        $this->layout = 'home';
        $ref = '';
        if (Yii::$app->request->cookies->has('alochat_ref')) {
              $ref = Yii::$app->request->cookies->get('alochat_ref')->value;
        }
        /*
        if($country = $this->detectCountry()){
            return $this->redirect(Url::to([$country."/site/register"]));
        }
        */

        $signupForm = new SignupForm();
        $signupForm->b_year = 1990;

        if ($signupForm->load(Yii::$app->request->post())) {
            $signupForm->ref = $ref;

            //if (Yii::$app->request->post('g-recaptcha-response')) { //GOOGLE RECAPTCHA
            if ($user = $signupForm->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect(Url::to('/profile/complete'));
                }
            }
        }

        return $this->render('register',[
             'signupForm' => $signupForm
        ]);

    }

    public function actionNotifications()
    {
        $this->layout = 'column3';

        $db = Yii::$app->db;

        $user_id = Yii::$app->user->id;

        if(isset($_GET["p"]) and (intval($_GET["p"])==0 OR intval($_GET["p"])==1)) {
            $_COOKIE["not_read_filter"] = intval($_GET["p"]);
        }else {
            if(!isset($_COOKIE["not_read_filter"]))
                $_COOKIE["not_read_filter"] = 0;
        }

        $not_read_filter =  $_COOKIE["not_read_filter"];

        $db->createCommand('UPDATE notification SET `read`=1 WHERE user_id=:user_id and `read`=0')
            ->bindValues([":user_id" => $user_id])
            ->execute();



        if(intval($not_read_filter)==1){
            $where = " AND `read`!=2";
            $queryCount  = $db->createCommand('SELECT COUNT(id) FROM notification WHERE user_id=:user_id '.$where.' order by `time` DESC')
                ->bindValues([":user_id" => $user_id])
                ->queryScalar();

        }else{
            $where = '';
            $queryCount  = $db->createCommand('SELECT count(id) FROM notification WHERE user_id=:user_id order by `time` DESC')
                ->bindValues([":user_id" => $user_id])
                ->queryScalar();
        }

        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 10;

            $notifications  = $db->createCommand('SELECT * FROM notification WHERE user_id=:user_id '.$where.' ORDER BY `time` DESC
            LIMIT '.$pages->offset.', '.$pages->limit)
                ->bindValues([":user_id" => $user_id])
                ->queryAll();

        }


        return $this->render('notifications2',[
            'notifications' => $notifications,
            'pages' => $pages,
            'not_read_filter' => $not_read_filter
        ]);
    }


    public function actionNotifications2()
    {
        $this->layout = 'column3';

        $db = Yii::$app->db;

        $user_id = Yii::$app->user->id;

        if(isset($_GET["p"]) and (intval($_GET["p"])==0 OR intval($_GET["p"])==1)) {
            $_COOKIE["not_read_filter"] = intval($_GET["p"]);
        }else {
            if(!isset($_COOKIE["not_read_filter"]))
                $_COOKIE["not_read_filter"] = 0;
        }

        $not_read_filter =  $_COOKIE["not_read_filter"];

        $db->createCommand('UPDATE notification SET `read`=1 WHERE user_id=:user_id and `read`=0')
            ->bindValues([":user_id" => $user_id])
            ->execute();



        if(intval($not_read_filter)==1){
            $where = " AND `read`!=2";
             $queryCount  = $db->createCommand('SELECT COUNT(id) FROM notification WHERE user_id=:user_id '.$where.' order by `time` DESC')
                ->bindValues([":user_id" => $user_id])
                ->queryScalar();

        }else{
            $where = '';
            $queryCount  = $db->createCommand('SELECT count(id) FROM notification WHERE user_id=:user_id order by `time` DESC')
                ->bindValues([":user_id" => $user_id])
                ->queryScalar();
        }

        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 10;

            $notifications  = $db->createCommand('SELECT * FROM notification WHERE user_id=:user_id '.$where.' ORDER BY `time` DESC
            LIMIT '.$pages->offset.', '.$pages->limit)
                ->bindValues([":user_id" => $user_id])
                ->queryAll();

        }


        return $this->render('notifications2',[
            'notifications' => $notifications,
            'pages' => $pages,
            'not_read_filter' => $not_read_filter
        ]);
    }

    public function actionShares()
    {
        $startTime = time() + microtime();
        $this->layout = 'column3';

        $db = Yii::$app->db;
        $cookies = Yii::$app->request->cookies;

        if(isset($_GET["p"]) and (intval($_GET["p"])==0 OR intval($_GET["p"])==1)) {
            $_COOKIE["friend_filter"] = intval($_GET["p"]);
        }else {
            if(!isset($_COOKIE["friend_filter"]))
                $_COOKIE["friend_filter"] = 0;

        }

        $friend_filter =  $_COOKIE["friend_filter"];
        $user_id = Yii::$app->user->id;
        $friendsArray = [];
        $friends = $db->createCommand('SELECT `user_1`, `user_2` FROM `user_friend` WHERE (`user_1` = "'.$user_id.'" OR `user_2` = "'.$user_id.'") AND `ok` =1  ORDER BY `id` ASC LIMIT 100')->queryAll();

        foreach($friends as $friend){
            $friend_user_1 = $friend['user_1'];
            $friend_user_2 = $friend['user_2'];
            if($friend_user_1 != $user_id) $f_uid = $friend_user_1; else $f_uid = $friend_user_2;

            $friendsArray[] = $f_uid;
        }

        if(count($friendsArray)>0){
            $friendsArray = "(".implode(',',$friendsArray).")";
            $whereFriend = "user_id in ".$friendsArray;
            $whereFriend1 = "OR user_id in ".$friendsArray;
        }
        if($friend_filter==1){
            $where = " WHERE ".$whereFriend;
            $queryCount = $db->createCommand("SELECT COUNT(id) FROM `share` ".$where)->queryScalar();

        }else{
            $where = " WHERE status=1 and  permission=0 ".$whereFriend1;
            $queryCount = $db->createCommand("SELECT COUNT(DISTINCT(`user_id`)) FROM `share` ")->queryScalar();

        }


        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 10;

            $shares =  $db->createCommand("SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count FROM (
              SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count
              FROM `share` ".$where."
              ORDER BY time DESC
            ) AS share_list GROUP BY user_id ORDER BY `time` DESC
            LIMIT ".$pages->offset.", ".$pages->limit)->queryAll();
            //  $shares =  $db->createCommand('SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM share GROUP BY user_id  ORDER BY `time` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();
            $smilesArray = ConversationReply::getEmojis();
            foreach($shares as $key=>$share){
                $allShares[$key]["id"] = $share["id"];
                $allShares[$key]["user_id"] = $share["user_id"];

                $allShares[$key]["text"] = $share["text"];
                $allShares[$key]["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $allShares[$key]["text"]);

                $allShares[$key]["attach"] = $share["attach"];
                $allShares[$key]["like_count"] = $share["like_count"];
                $allShares[$key]["comment_count"] = $share["comment_count"];
                $allShares[$key]["read_count"] = $share["read_count"];
                $allShares[$key]["time"] = $share["time"];
                $allShares[$key]["date_folder"] = date("Ym",$share["time"]);

                $user = $db->createCommand('SELECT id,nickname,profile_photo,last_activity,sex FROM `user` WHERE `id`="'.$share["user_id"].'"')->queryOne();
                $allShares[$key]["nickname"] = $user["nickname"];
                $allShares[$key]["user_id"] = $user["id"];
                $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                $allShares[$key]["last_activity"] = $user["last_activity"];
            }
        }

        $model = new ShareForm();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->attach = UploadedFile::getInstance($model, 'attach');

            if($model->sendShare()){
                //$model->saveImages($model->attach);
                return $this->redirect(Url::to(['/site/shares']));
            }else {
                Yii::$app->session->setFlash('error',Yii::t('app','An error occurred.'));
                return $this->redirect(Url::to(['/site/shares']));

            }

        }
        $endTime = time() + microtime();
        $ferqTime = $endTime - $startTime;
        // echo $friend_filter;exit;
        return $this->render('shares',[
            'shares' =>$allShares,
            'pages' => $pages,
            'model' => $model,
            'friend_filter' => $friend_filter,
            'ferqTime' => $ferqTime
        ]);
    }
    public function actionTopDayShares()
    {
        $this->layout = 'column3';


        $db = Yii::$app->db;
        $cookies = Yii::$app->request->cookies;

        if(isset($_GET["p"]) and (intval($_GET["p"])==0 OR intval($_GET["p"])==1)) {
            $_COOKIE["friend_filter"] = intval($_GET["p"]);
        }else {
            if(!isset($_COOKIE["friend_filter"]))
                $_COOKIE["friend_filter"] = 0;

        }

        $friend_filter =  $_COOKIE["friend_filter"];
        $user_id = Yii::$app->user->id;
        $friendsArray = [];
        $friends = $db->createCommand('SELECT `user_1`, `user_2` FROM `user_friend` WHERE (`user_1` = "'.$user_id.'" OR `user_2` = "'.$user_id.'") AND `ok` =1  ORDER BY `id` ASC LIMIT 100')->queryAll();

        foreach($friends as $friend){
            $friend_user_1 = $friend['user_1'];
            $friend_user_2 = $friend['user_2'];
            if($friend_user_1 != $user_id) $f_uid = $friend_user_1; else $f_uid = $friend_user_2;

            $friendsArray[] = $f_uid;
        }

        if(count($friendsArray)>0){
            $friendsArray = "(".implode(',',$friendsArray).")";
            $whereFriend = "user_id in ".$friendsArray;
            $whereFriend1 = "OR user_id in ".$friendsArray;
        }
        $time = time() - 24*3600;
        if($friend_filter==1){
            $where = " WHERE `time`>".$time." and  ".$whereFriend;

        }else{
            $where = " WHERE permission=0 and `time`>".$time." ".$whereFriend1;
        }

        $queryCount = $db->createCommand("SELECT COUNT(id) FROM `share` ".$where)->queryScalar();


        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 10;

            $shares =  $db->createCommand("
              SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count
              FROM `share` ".$where."
              ORDER BY like_count DESC
            LIMIT ".$pages->offset.", ".$pages->limit)->queryAll();
            //  $shares =  $db->createCommand('SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM share GROUP BY user_id  ORDER BY `time` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();
            foreach($shares as $key=>$share){
                $allShares[$key]["id"] = $share["id"];
                $allShares[$key]["user_id"] = $share["user_id"];
                $allShares[$key]["text"] = $share["text"];
                $allShares[$key]["attach"] = $share["attach"];
                $allShares[$key]["like_count"] = $share["like_count"];
                $allShares[$key]["comment_count"] = $share["comment_count"];
                $allShares[$key]["read_count"] = $share["read_count"];
                $allShares[$key]["time"] = $share["time"];
                $allShares[$key]["date_folder"] = date("Ym",$share["time"]);

                $user = $db->createCommand('SELECT id,nickname,profile_photo,last_activity,sex FROM `user` WHERE `id`="'.$share["user_id"].'"')->queryOne();
                $allShares[$key]["nickname"] = $user["nickname"];
                $allShares[$key]["user_id"] = $user["id"];
                $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                $allShares[$key]["last_activity"] = $user["last_activity"];
            }
        }

        $model = new ShareForm();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->attach = UploadedFile::getInstance($model, 'attach');

            if($model->sendShare()){
                //$model->saveImages($model->attach);
                return $this->redirect(Url::to(['/site/shares']));
            }

        }

        $topParam = 'day';
        // echo $friend_filter;exit;
        return $this->render('shares',[
            'shares' =>$allShares,
            'pages' => $pages,
            'model' => $model,
            'friend_filter' => $friend_filter,
            'topParam' => $topParam
        ]);
    }



    public function actionTopWeekShares()
    {
        $this->layout = 'column3';

        $db = Yii::$app->db;
        $cookies = Yii::$app->request->cookies;

        if(isset($_GET["p"]) and (intval($_GET["p"])==0 OR intval($_GET["p"])==1)) {
            $_COOKIE["friend_filter"] = intval($_GET["p"]);
        }else {
            if(!isset($_COOKIE["friend_filter"]))
                $_COOKIE["friend_filter"] = 0;

        }

        $friend_filter =  $_COOKIE["friend_filter"];
        $user_id = Yii::$app->user->id;
        $friendsArray = [];
        $friends = $db->createCommand('SELECT `user_1`, `user_2` FROM `user_friend` WHERE (`user_1` = "'.$user_id.'" OR `user_2` = "'.$user_id.'") AND `ok` =1  ORDER BY `id` ASC LIMIT 100')->queryAll();

        foreach($friends as $friend){
            $friend_user_1 = $friend['user_1'];
            $friend_user_2 = $friend['user_2'];
            if($friend_user_1 != $user_id) $f_uid = $friend_user_1; else $f_uid = $friend_user_2;

            $friendsArray[] = $f_uid;
        }

        if(count($friendsArray)>0){
            $friendsArray = "(".implode(',',$friendsArray).")";
            $whereFriend = "user_id in ".$friendsArray;
            $whereFriend1 = "OR user_id in ".$friendsArray;
        }
        $time = time() - 7*24*3600;
        if($friend_filter==1){
            $where = " WHERE `time`>".$time." ".$whereFriend;

        }else{
            $where = " WHERE permission=0 and `time`>".$time." ".$whereFriend1;
        }

        $queryCount = $db->createCommand("SELECT COUNT(id) FROM `share` ".$where)->queryScalar();


        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 10;

            $shares =  $db->createCommand("
              SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count
              FROM `share` ".$where."
              ORDER BY like_count DESC
            LIMIT ".$pages->offset.", ".$pages->limit)->queryAll();
            //  $shares =  $db->createCommand('SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM share GROUP BY user_id  ORDER BY `time` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();
            foreach($shares as $key=>$share){
                $allShares[$key]["id"] = $share["id"];
                $allShares[$key]["user_id"] = $share["user_id"];
                $allShares[$key]["text"] = $share["text"];
                $allShares[$key]["attach"] = $share["attach"];
                $allShares[$key]["like_count"] = $share["like_count"];
                $allShares[$key]["comment_count"] = $share["comment_count"];
                $allShares[$key]["read_count"] = $share["read_count"];
                $allShares[$key]["time"] = $share["time"];
                $allShares[$key]["date_folder"] = date("Ym",$share["time"]);

                $user = $db->createCommand('SELECT id,nickname,profile_photo,last_activity,sex FROM `user` WHERE `id`="'.$share["user_id"].'"')->queryOne();
                $allShares[$key]["nickname"] = $user["nickname"];
                $allShares[$key]["user_id"] = $user["id"];
                $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                $allShares[$key]["last_activity"] = $user["last_activity"];
            }
        }

        $model = new ShareForm();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->attach = UploadedFile::getInstance($model, 'attach');

            if($model->sendShare()){
                //$model->saveImages($model->attach);
                return $this->redirect(Url::to(['/site/shares']));
            }

        }
        $topParam = 'week';
        // echo $friend_filter;exit;
        return $this->render('shares',[
            'shares' =>$allShares,
            'pages' => $pages,
            'model' => $model,
            'friend_filter' => $friend_filter,
            'topParam' => $topParam
        ]);
    }


    public function actionUser($id)
    {
        $this->layout = 'profile-login';

        $db = Yii::$app->db;

        $user = $this->findModel($id);

        $isOwnProfile = false;


        $allShares = [];

        $queryCount = $db->createCommand('SELECT count(id) FROM share WHERE user_id="'.$id.'"')->queryScalar();

        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 10;

            $shares =  $db->createCommand('SELECT * FROM share WHERE user_id="'.$id.'" ORDER BY `id` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();

            foreach($shares as $key=>$share)
            {
                $allShares[$key]["id"] = $share["id"];
                $allShares[$key]["user_id"] = $share["user_id"];
                $allShares[$key]["text"] = $share["text"];
                $allShares[$key]["date_folder"] = date("Ym",$share["time"]);
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
        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {

            return $this->actionUsers();
        }



        return $this->render('timeline',[
            'user' => $user,
            'shares' =>$allShares,
            'pages' => $pages,
            'isOwnProfile' => $isOwnProfile,
            'loginForm' => $loginForm
        ]);

    }

    public function actionPost($id)
    {
        if(!Yii::$app->user->isGuest){
            return $this->redirect(Url::to(["/profile/post/".$id]));
        }
        /*
        if($country = $this->detectCountry()){
            return $this->redirect(Url::to([$country."/site/post/".$id]));
        }
        */

        $this->layout = 'profile-login';

        $db = Yii::$app->db;

        $allComments = [];

        $share = self::findShare($id);

        $user_id =  $share["user_id"];

        $user = self::findUser($user_id);

        $user["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

        $smilesArray = ConversationReply::getEmojis();
        $share["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),    $share["text"] );


        $comments =  $db->createCommand('SELECT `id`,`comment`,`time`,`uid` FROM share_comment WHERE sid='.$id.' ORDER BY `time` DESC')->queryAll();
        if($comments){
            foreach($comments as $k=>$c){
                $comment_user_id = $c["uid"];
                $commentUser = Yii::$app->db->createCommand('SELECT nickname,profile_photo FROM `user` WHERE id="'.$comment_user_id.'"')->queryOne();
                $allComments[$k]['nickname'] = $commentUser["nickname"];
                $allComments[$k]['profile_photo'] = $commentUser["profile_photo"]!=''?$commentUser["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];
                $allComments[$k]["comment"] =  $c["comment"];
                $allComments[$k]["comment"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $allComments[$k]["comment"] );

                $allComments[$k]["time"] =  $c["time"];
            }
        }

        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {

            return $this->actionUsers();
        }



        return $this->render('post',[
            'share' => $share,
            'user'  => $user,
            'comments' => $allComments,
            'loginForm' => $loginForm
        ]);


    }

    protected function findUser($id)
    {
        $db = Yii::$app->db;
        $user = $db->createCommand('SELECT * FROM `user` WHERE id="'.$id.'"')->queryOne();

        if($user){
            return $user;
        }else{
            throw new NotFoundHttpException(Yii::t('app', 'Requested page not found.'));
        }
    }

    protected function findShare($id)
    {
        $db = Yii::$app->db;
        $share = $db->createCommand('SELECT * FROM `share` WHERE id="'.$id.'"')->queryOne();

        if($share){
            return $share;
        }else{
            throw new NotFoundHttpException(Yii::t('app', 'Requested page not found.'));
        }
    }


    public function actionHome()
    {
        $this->layout = 'home';
        $allShares = [];
        $db = Yii::$app->db;
        $users = User::getOnlineUsersForHome();
        $onlineCount = User::getOnlineUserCount();


        $queryCount = $db->createCommand('SELECT count(id) FROM share ORDER BY id DESC')->queryScalar();

        if ($queryCount>0) {
            // Pagination

            $pages = new Pagination(['totalCount' => $queryCount]);

            $pages->pageSize = 5;
            $shares =  $db->createCommand("SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count FROM (
              SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count
              FROM `share` WHERE permission=0 and status=1
              ORDER BY time DESC
            ) AS share_list GROUP BY user_id ORDER BY `time` DESC
            LIMIT ".$pages->offset.", ".$pages->limit)->queryAll();
            //$shares =  $db->createCommand('SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM share  ORDER BY `id` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();
            $smilesArray = ConversationReply::getEmojis();
            foreach($shares as $key=>$share){
                $allShares[$key]["id"] = $share["id"];
                $allShares[$key]["user_id"] = $share["user_id"];
                $allShares[$key]["text"] = $share["text"];
                $allShares[$key]["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $allShares[$key]["text"]);

                $allShares[$key]["attach"] = $share["attach"];
                $allShares[$key]["like_count"] = $share["like_count"];
                $allShares[$key]["comment_count"] = $share["comment_count"];
                $allShares[$key]["read_count"] = $share["read_count"];
                $allShares[$key]["time"] = $share["time"];
                $allShares[$key]["date_folder"] = date("Ym",$share["time"]);

                $user = $db->createCommand('SELECT nickname,profile_photo,last_activity,sex FROM `user` WHERE `id`="'.$share["user_id"].'"')->queryOne();
                $allShares[$key]["nickname"] = $user["nickname"];
                $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                $allShares[$key]["last_activity"] = $user["last_activity"];
            }
        }


        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {

            return $this->actionUsers();
        }
         return $this->render('home',[
             'users' => $users,
             'onlineCount' =>$onlineCount,
             'loginForm' => $loginForm,
             'shares' => $allShares,
             'pages' => $pages,

         ]);
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            if(isset($_GET["ref"]) and  !empty($_GET["ref"]))
            {
                $ref = trim($_GET["ref"]);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'alochat_ref',
                    'value' => $ref,
                    'expire' => time() +  60 * 60,

                ]));
            }

            $country_code = $this->detectCountry();

            $this->layout = 'home';

            $allShares = [];
            $db = Yii::$app->db;
            $users = User::getOnlineUsersForHome();
            $onlineCount = User::getOnlineUserCount();


            if(isset($_GET["country"])){
                $country_code = htmlspecialchars($_GET["country"]);
                $countryRow = $db->createCommand('SELECT id FROM `country` WHERE `code`=:code')->bindValue(':code',$country_code)->queryOne();
                if($countryRow["id"]){
                    $whereCountry = "WHERE `country`=".$countryRow["id"];
                }
            }

            $queryCount = $db->createCommand('SELECT count(id) FROM share '.$whereCountry.' ORDER BY id DESC')->queryScalar();

            if($queryCount<20){
                $queryCount = $db->createCommand('SELECT count(id) FROM share  ORDER BY id DESC')->queryScalar();
            }

            if ($queryCount>0) {
                // Pagination

                $pages = new Pagination(['totalCount' => $queryCount]);

                $pages->pageSize = 5;
                $shares =  $db->createCommand("SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count FROM (
              SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count
              FROM `share` WHERE permission=0 and status=1
              ORDER BY time DESC
            ) AS share_list GROUP BY user_id ORDER BY `time` DESC
            LIMIT ".$pages->offset.", ".$pages->limit)->queryAll();
                //$shares =  $db->createCommand('SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM share  ORDER BY `id` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();
                $smilesArray = ConversationReply::getEmojis();
                foreach($shares as $key=>$share){
                    $allShares[$key]["id"] = $share["id"];
                    $allShares[$key]["user_id"] = $share["user_id"];
                    $allShares[$key]["text"] = $share["text"];
                    $allShares[$key]["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $allShares[$key]["text"]);

                    $allShares[$key]["attach"] = $share["attach"];
                    $allShares[$key]["like_count"] = $share["like_count"];
                    $allShares[$key]["comment_count"] = $share["comment_count"];
                    $allShares[$key]["read_count"] = $share["read_count"];
                    $allShares[$key]["time"] = $share["time"];
                    $allShares[$key]["date_folder"] = date("Ym",$share["time"]);

                    $user = $db->createCommand('SELECT nickname,profile_photo,last_activity,sex FROM `user` WHERE `id`="'.$share["user_id"].'"')->queryOne();
                    $allShares[$key]["nickname"] = $user["nickname"];
                    $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                    $allShares[$key]["last_activity"] = $user["last_activity"];
                }
            }


            $loginForm = new LoginForm();
            if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
                if(Yii::$app->user->identity->deactive == 1){
                    Yii::$app->db->createCommand('UPDATE `user` SET deactive=0 WHERE id=:id limit 1')->bindValue(':id',Yii::$app->user->id)->execute();
                }
                return $this->actionUsers();
            }
            return $this->render('home',[
                'users' => $users,
                'onlineCount' =>$onlineCount,
                'loginForm' => $loginForm,
                'shares' => $allShares,
                'pages' => $pages,
                'country_code' => $country_code

            ]);

        } else {
            return $this->actionUsers();

        }
    }


    public function actionIndex2()
    {
        if (Yii::$app->user->isGuest) {
            if(isset($_GET["ref"]) and  !empty($_GET["ref"]))
            {
                $ref = trim($_GET["ref"]);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'alochat_ref',
                    'value' => $ref,
                    'expire' => time() +  60 * 60,

                ]));
            }

            $country_code = $this->detectCountry();

            echo $country_code;
            if(Yii::$app->response->cookies->has('alochat_user_country')){
                echo "<br /> Olke->".Yii::$app->response->cookies->get('alochat_user_country')->value;
            }
            if(Yii::$app->response->cookies->has('alochat_language')){
                echo "<br /> Dil->".Yii::$app->response->cookies->get('alochat_language')->value;
            }
            echo "<br />"."Dil yii2 -> ".Yii::$app->language;
            $this->layout = 'home2';

            $allShares = [];
            $db = Yii::$app->db;
            $users = User::getOnlineUsersForHome();
            $onlineCount = User::getOnlineUserCount();


            if(isset($_GET["country"])){
                $country_code = htmlspecialchars($_GET["country"]);
                $countryRow = $db->createCommand('SELECT id FROM `country` WHERE `code`=:code')->bindValue(':code',$country_code)->queryOne();
                if($countryRow["id"]){
                    $whereCountry = "WHERE `country`=".$countryRow["id"];
                }
            }

            $queryCount = $db->createCommand('SELECT count(id) FROM share '.$whereCountry.' ORDER BY id DESC')->queryScalar();

            if($queryCount<20){
                $queryCount = $db->createCommand('SELECT count(id) FROM share  ORDER BY id DESC')->queryScalar();
            }

            if ($queryCount>0) {
                // Pagination

                $pages = new Pagination(['totalCount' => $queryCount]);

                $pages->pageSize = 5;
                $shares =  $db->createCommand("SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count FROM (
              SELECT id,user_id,text,attach,like_count,read_count,`time`,comment_count
              FROM `share` WHERE permission=0 and status=1
              ORDER BY time DESC
            ) AS share_list GROUP BY user_id ORDER BY `time` DESC
            LIMIT ".$pages->offset.", ".$pages->limit)->queryAll();
                //$shares =  $db->createCommand('SELECT id,user_id,text,attach,like_count,read_count,time,comment_count FROM share  ORDER BY `id` DESC LIMIT '.$pages->offset.','.$pages->limit)->queryAll();
                $smilesArray = ConversationReply::getEmojis();
                foreach($shares as $key=>$share){
                    $allShares[$key]["id"] = $share["id"];
                    $allShares[$key]["user_id"] = $share["user_id"];
                    $allShares[$key]["text"] = $share["text"];
                    $allShares[$key]["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $allShares[$key]["text"]);

                    $allShares[$key]["attach"] = $share["attach"];
                    $allShares[$key]["like_count"] = $share["like_count"];
                    $allShares[$key]["comment_count"] = $share["comment_count"];
                    $allShares[$key]["read_count"] = $share["read_count"];
                    $allShares[$key]["time"] = $share["time"];
                    $allShares[$key]["date_folder"] = date("Ym",$share["time"]);

                    $user = $db->createCommand('SELECT nickname,profile_photo,last_activity,sex FROM `user` WHERE `id`="'.$share["user_id"].'"')->queryOne();
                    $allShares[$key]["nickname"] = $user["nickname"];
                    $allShares[$key]["profile_photo"] = $user["profile_photo"]!=''?$user["profile_photo"]:Url::base() . \Yii::$app->params['defaultProfilePicture_'.$user['sex']];

                    $allShares[$key]["last_activity"] = $user["last_activity"];
                }
            }


            $loginForm = new LoginForm();
            if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
                if(Yii::$app->user->identity->deactive == 1){
                    Yii::$app->db->createCommand('UPDATE `user` SET deactive=0 WHERE id=:id limit 1')->bindValue(':id',Yii::$app->user->id)->execute();
                }
                return $this->actionUsers();
            }
            return $this->render('home2',[
                'users' => $users,
                'onlineCount' =>$onlineCount,
                'loginForm' => $loginForm,
                'shares' => $allShares,
                'pages' => $pages,
                'country_code' => $country_code

            ]);

        } else {
            return $this->actionUsers();

        }
    }





    public function actionTerms()
    {
        $this->layout = false;
        return $this->render('terms');
    }


    public function actionTest()
    {
        $db = \Yii::$app->db;
        $time = time() - 15*24*3600;
        echo  $time;

    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(Yii::$app->user->identity->deactive == 1){
                echo "ttt"; exit;
            }
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionReadNotification()
    {

        if(Yii::$app->request->isAjax){
            $user = User::findOne(Yii::$app->user->id);

            $newNotificationCount = $user->readNotification();

        }

        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => ['response' => ['newNotificationCount' => $newNotificationCount]]
        ]);
    }

    public function actionPing()
    {
        $newNotificationText  = 'ss';

        $user = User::findOne(Yii::$app->user->id);

        $user->setLoginDate();

        $user->updateLastActivity();

        $user->updateActivityCoin();

        UserActivity::activityOnlineTime();

        $count = $user->getNewMessagesCount();

        $newNotificationCount  = $user->getNewNotificationCount();

        $newNotificationText = $user->getNewNotificationText();


        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => ['response' => ['newMessageCount' => $count,'newNotificationCount' => $newNotificationCount,'newNotificationText'=>$newNotificationText]]
        ]);


    }

    public function actionContact()
    {
        $this->layout = 'home';
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        $this->layout = 'home';
        return $this->render('about');
    }

    public function actionPrivacyPolicy()
    {
        $this->layout = 'home';
        return $this->render('privacy');
    }


    public function actionDiscovery()
    {
        $this->layout = "column3";

        $params = [];


        $discoveryFilterForm = new DiscoveryFilterForm();

        if ($discoveryFilterForm->load(Yii::$app->request->post()) && $discoveryFilterForm->validate()) {

            Yii::$app->response->cookies->remove('discoveryCurrentUserId');

            $discoveryFilterForm->saveChanges();

            $this->refresh();
        }

        $countries = Country::find()->orderBy(['code' => SORT_ASC])->asArray()->all();

        $countries = ArrayHelper::map($countries, 'id', 'name');

        $cities = [];


        if (Yii::$app->request->cookies->has('filterData')) {

            $filterData = Yii::$app->request->cookies->get('filterData')->value;


            $discoveryFilterForm->sex = $filterData['sex'];

            $discoveryFilterForm->countryId = $filterData['country'];

            $discoveryFilterForm->cityId = $filterData['city'];

            $discoveryFilterForm->ageRange = "[" . $filterData['ageMin'] . ',' . $filterData['ageMax'] . "]";

            $params['ageMin'] = $filterData['ageMin'];
            $params['ageMax'] = $filterData['ageMax'];


            $cities = City::find()
                ->where(['country_id' => $discoveryFilterForm->countryId])
                ->andWhere("name!='-'")
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();

            $cities = ArrayHelper::map($cities, 'id', 'name');
        } else {


            $discoveryFilterForm->sex = !Yii::$app->user->identity->sex;

            $discoveryFilterForm->countryId = Yii::$app->user->identity->country_id;

            $discoveryFilterForm->cityId = 0;

            $discoveryFilterForm->ageRange = "[" . User::AGE_MIN . ",40]";

            $cities = City::find()
                ->where(['country_id' => Yii::$app->user->identity->country_id])
                ->andWhere("name!='-'")
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();

            $cities = ArrayHelper::map($cities, 'id', 'name');

            $params['ageMin'] = User::AGE_MIN;
            $params['ageMax'] = 40;
        }

        $params['sex'] = $discoveryFilterForm->sex;
        $params['city_id'] = $discoveryFilterForm->cityId;
        $params['country_id'] = $discoveryFilterForm->countryId;


        $users = User::findUsersForDiscovery($params);

        if(count($users['current']) == 0 and !Yii::$app->request->cookies->has('filterData')){

            $params['ageRange'] = '18,41';
            $params['sex'] = 2;
            $params['city_id'] = 0;
            $params['country_id'] = 0;


            $discoveryFilter = new DiscoveryFilterForm();

            $discoveryFilter->ageRange = Yii::$app->params['defaultDiscoveryAgeRange'];
            $discoveryFilter->cityId = Yii::$app->params['defaultDiscoveryCityId'];
            $discoveryFilter->countryId = Yii::$app->params['defaultDiscoveryCountryId'];
            $discoveryFilter->sex = Yii::$app->params['defaultDiscoverySex'];

            $discoveryFilter->saveChanges();
            $this->refresh();
        }

        $vip_users = UserVip::find()
                ->select([
                    'U.full_name',
                    'U.age',
                    'U.id',
                    'U.sex',
                    'U.country_id',
                    'U.profile_photo',
                    'C.name as city_name',
                    'V.time'
                ])
                ->from('vip_user V')
                ->where('U.profile_photo!=""')
                ->innerJoin('user U','V.user_id=U.id')
                ->leftJoin('city C','U.city_id=C.id')
                ->orderBy(["V.time"=>SORT_DESC])
              //->orderBy([ 'rand()' => SORT_DESC  ])
                ->asArray()
                ->limit(4)
                ->all();
        
        return $this->render('discovery', [
            'countries' => $countries,
            'cities' => $cities,
            'discoveryFilterForm' => $discoveryFilterForm,
            'foundUsers' => $users,
            'vipUsers' => $vip_users
        ]);
    }



    public function actionDiscoveryNextUser()
    {

        if (Yii::$app->request->isAjax) {

            $cid = intval(Yii::$app->request->get('cid'));
            $direction = Yii::$app->request->get('direction');

            $response = User::getDiscoveryFindUser($cid, $direction);

            return \Yii::createObject([
                'class' => 'Yii\web\Response',
                'format' => Response::FORMAT_JSON,
                'data' => $response
            ]);
        } else {

            throw new BadRequestHttpException;
        }

    }


/*    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {

            //if (Yii::$app->request->post('g-recaptcha-response')) {
                if ($user = $model->sigxnup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        return $this->redirect(Url::to('profile/complete'));
                    }
                }
           // } else {
           //     Yii::$app->session->setFlash('error', Yii::t('app', 'Please complete the CAPTCHA'));
           //}
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }*/

    public function actionRequestPasswordReset()
    {
        $this->layout = 'home';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

             if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app','Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app','Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionRp($token)
    {
        $this->layout = 'home';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLanguage($id)
    {
        if (in_array($id, ['en', 'ru', 'az', 'tr'])) {

            Yii::$app->language = $id;
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'alochat_language',
                'value' => trim($id),
                'expire' => time() + 365 * 24 * 60 * 60,

            ]));
        }

         return $this->redirect(Url::home(), 302);
    }



    public function actionSetCurrentUser()
    {
        if (Yii::$app->request->isAjax) {

            $cid = intval(Yii::$app->request->get('cid'));

            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'discoveryCurrentUserId',
                'value' => $cid
            ]));

        } else {

            throw new BadRequestHttpException;
        }

    }



    public function actionUsers2()
    {
        $startTime = time()+microtime();


        if(intval(Yii::$app->user->identity->country_id) == 0){
            return $this->redirect(Url::to('/profile/complete'));
        }

        $this->layout = 'column3';

        $params = [];



        $userFilter  = new UserFilter();

        if($userFilter->load(Yii::$app->request->post()) /*&& $userFilter->validate()*/){

            $userFilter->saveChanges();

            $this->refresh();

        }

        $countries = Country::find()->orderBy(['code' => SORT_ASC])->asArray()->all();

        $countries = ArrayHelper::map($countries, 'id', 'name');

        $cities = [];

        if(Yii::$app->request->cookies->has('userFilterData')) {

            $userFilterData = Yii::$app->request->cookies->get('userFilterData')->value;

            $userFilter->sex = $userFilterData['sex'];

            $userFilter->countryId = $userFilterData['country'];

            $userFilter->cityId = $userFilterData['city'];

            $userFilter->ageRange = '[' . $userFilterData['ageMin'] . ',' . $userFilterData['ageMax'] . ']';

            $userFilter->onlineStatus = $userFilterData['online'];

            $userFilter->issetPhoto = $userFilterData['photo'];

            $params['ageMin'] = $userFilterData['ageMin'];

            $params['ageMax'] = $userFilterData['ageMax'];

            $cities = City::find()
                ->where(['country_id' => $userFilter->countryId])
                ->andWhere("name!='-'")
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();

            $cities = ArrayHelper::map($cities,'id','name');

        } else {

            $userFilter->sex = Yii::$app->params['defaultDiscoverySex'];

            $userFilter->ageRange = '['.User::AGE_MIN.','.User::AGE_MAX.']';

            if(User::getSameCountryUserCount() >= 50) {
                $userFilter->countryId = Yii::$app->user->identity->country_id;
            } else {
                $userFilter->countryId = 0;
            }

            $userFilter->cityId = 0;

            $params['ageMin'] = User::AGE_MIN;
            $params['ageMax'] = User::AGE_MAX;

        }

        $params['sex'] = $userFilter->sex;
        $params['country'] = $userFilter->countryId;
        $params['city'] = $userFilter->cityId;
        $params['online'] = $userFilter->onlineStatus;
        $params['photo'] = $userFilter->issetPhoto;

        $query = User::findUsersForFilter($params);

        if($query){
            //pagination

            $countQuery = clone $query;
            //$count = $countQuery->count('U.id');
            $pages = new Pagination(['totalCount' => $countQuery->count('U.id')]);

            $pages->pageSize = 12;
            //$pages->pageCount = 5;

            $users = $query->offset($pages->offset)
            ->orderBy(['last_activity_round' => SORT_DESC,'point' => SORT_DESC,'user_value' => SORT_DESC,'level' => SORT_DESC,'profile_photo_id' => SORT_DESC,'id'=> SORT_DESC])
            ->limit($pages->limit)
                //->asArray()
                ->all();

        }




        $vip_users = UserVip::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.country_id',
                'U.profile_photo',
                'C.name as city_name',
                'V.time'
            ])
            ->from('vip_user V')
            ->where('U.profile_photo!=""')
            ->innerJoin('user U','V.user_id=U.id')
            ->leftJoin('city C','U.city_id=C.id')
            ->orderBy(["V.time"=>SORT_DESC])
            //->orderBy([ 'rand()' => SORT_DESC  ])
            ->asArray()
            ->limit(6)
            ->all();

        $endTime = time()+microtime();

        $timeFerq = $endTime - $startTime;
        return $this->render('users',[
            'userFilter' => $userFilter,
            'countries'  => $countries,
            'cities'     => $cities,
            'users'  => $users,
            'pages' => $pages,
            'vipUsers' => $vip_users,
            'timeFerq' => $timeFerq,
            'startTime' => $startTime,
        ]);
    }


    public function actionUsers1()
    {
        $startTime = time()+microtime();


        if(intval(Yii::$app->user->identity->country_id) == 0){
            return $this->redirect(Url::to('/profile/complete'));
        }

        $this->layout = 'column3';

        $params = [];



        $userFilter  = new UserFilter();

        if($userFilter->load(Yii::$app->request->post()) /*&& $userFilter->validate()*/){

            $userFilter->saveChanges();

            $this->refresh();

        }

        $countries = Country::find()->orderBy(['code' => SORT_ASC])->asArray()->all();

        $countries = ArrayHelper::map($countries, 'id', 'name');

        $cities = [];

        if(Yii::$app->request->cookies->has('userFilterData')) {

            $userFilterData = Yii::$app->request->cookies->get('userFilterData')->value;

            $userFilter->sex = $userFilterData['sex'];

            $userFilter->countryId = $userFilterData['country'];

            $userFilter->cityId = $userFilterData['city'];

            $userFilter->ageRange = '[' . $userFilterData['ageMin'] . ',' . $userFilterData['ageMax'] . ']';

            $userFilter->onlineStatus = $userFilterData['online'];

            $userFilter->issetPhoto = $userFilterData['photo'];

            $params['ageMin'] = $userFilterData['ageMin'];

            $params['ageMax'] = $userFilterData['ageMax'];

            $cities = City::find()
                ->where(['country_id' => $userFilter->countryId])
                ->andWhere("name!='-'")
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();

            $cities = ArrayHelper::map($cities,'id','name');

        } else {

            $userFilter->sex = Yii::$app->params['defaultDiscoverySex'];

            $userFilter->ageRange = '['.User::AGE_MIN.','.User::AGE_MAX.']';

            if(User::getSameCountryUserCount1() >= 50) {
                $userFilter->countryId = Yii::$app->user->identity->country_id;
            } else {
                $userFilter->countryId = 0;
            }

            $userFilter->cityId = 0;

            $params['ageMin'] = User::AGE_MIN;
            $params['ageMax'] = User::AGE_MAX;

        }

        $params['sex'] = $userFilter->sex;
        $params['country'] = $userFilter->countryId;
        $params['city'] = $userFilter->cityId;
        $params['online'] = $userFilter->onlineStatus;
        $params['photo'] = $userFilter->issetPhoto;

        $query = User::findUsersForFilter($params);

        if($query){
            //pagination

            $countQuery = clone $query;
            //$count = $countQuery->count('U.id');
            $pages = new Pagination(['totalCount' => $countQuery->count('U.id')]);

            $pages->pageSize = 12;
            //$pages->pageCount = 5;

            $users = $query->offset($pages->offset)
                ->orderBy(['last_activity_round' => SORT_DESC,'point' => SORT_DESC,'user_value' => SORT_DESC,'level' => SORT_DESC,'profile_photo_id' => SORT_DESC,'id'=> SORT_DESC])
                ->limit($pages->limit)
                //->asArray()
                ->all();

        }




        $endTime = time()+microtime();

        $timeFerq = $endTime - $startTime;
        var_dump($users);
        echo $timeFerq;
    }

    public function actionUsers()
    {
        // lazimsiz action silinesi
        $this->layout = 'column3';
        $db = Yii::$app->db;
        $startTime = time() + microtime();
        $params = [];


        $userFilter  = new UserFilter();

        if($userFilter->load(Yii::$app->request->post()) /*&& $userFilter->validate()*/){

            $userFilter->saveChanges();

            $this->refresh();

        }

        //$countries = Country::find()->orderBy(['code' => SORT_ASC])->asArray()->all();
        $countries = $db->createCommand('SELECT `id`,`name` FROM country ORDER by `code` ASC')->queryAll();
        $countries = ArrayHelper::map($countries, 'id', 'name');



                $cities = [];

                if(Yii::$app->request->cookies->has('userFilterData')) {

                    $userFilterData = Yii::$app->request->cookies->get('userFilterData')->value;

                    $userFilter->sex = $userFilterData['sex'];

                    $userFilter->countryId = $userFilterData['country'];

                    $userFilter->cityId = $userFilterData['city'];

                    $userFilter->ageRange = '[' . $userFilterData['ageMin'] . ',' . $userFilterData['ageMax'] . ']';

                    $userFilter->onlineStatus = $userFilterData['online'];

                    $userFilter->issetPhoto = $userFilterData['photo'];

                    $params['ageMin'] = $userFilterData['ageMin'];

                    $params['ageMax'] = $userFilterData['ageMax'];

                    $cities = City::find()
                        ->where(['country_id' => $userFilter->countryId])
                        ->andWhere("name!='-'")
                        ->orderBy(['name' => SORT_ASC])
                        ->asArray()
                        ->all();

                    $cities = ArrayHelper::map($cities,'id','name');

                } else {

                    $userFilter->sex = Yii::$app->params['defaultDiscoverySex'];

                    $userFilter->ageRange = '['.User::AGE_MIN.','.User::AGE_MAX.']';

                    if(User::getSameCountryUserCount1() >= 50) {
                        $userFilter->countryId = Yii::$app->user->identity->country_id;
                    } else {
                        $userFilter->countryId = 0;
                    }

                    $userFilter->cityId = 0;

                    $params['ageMin'] = User::AGE_MIN;
                    $params['ageMax'] = User::AGE_MAX;

                }

                $params['sex'] = $userFilter->sex;
                $params['country'] = $userFilter->countryId;
                $params['city'] = $userFilter->cityId;
                $params['online'] = $userFilter->onlineStatus;
                $params['photo'] = $userFilter->issetPhoto;

                $rowCount = User::findUsersForFilterCount($params);
                    //$rowCount = 40000;
                if($rowCount>0){
                     //pagination

                    $pages = new Pagination(['totalCount' => $rowCount]);

                    $pages->pageSize = 14;

                    $params["offset"] = $pages->offset;
                    $params["limit"] = $pages->limit;

                    $users = User::findUsersForFilter1($params);

                }
        $vip_users = UserVip::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.country_id',
                'U.profile_photo',
                'C.name as city_name',
                'V.time'
            ])
            ->from('vip_user V')
            ->where('U.profile_photo!=""')
            ->innerJoin('user U','V.user_id=U.id')
            ->leftJoin('city C','U.city_id=C.id')
            ->orderBy(["V.time"=>SORT_DESC])
            //->orderBy([ 'rand()' => SORT_DESC  ])
            ->asArray()
            ->limit(6)
            ->all();

        $endTime = time()+microtime();

        $timeFerq = $endTime - $startTime;
        return $this->render('users',[
            'userFilter' => $userFilter,
            'countries'  => $countries,
            'cities'     => $cities,
            'users'  => $users,
            'pages' => $pages,
            'vipUsers' => $vip_users,
            'timeFerq' => $timeFerq,
            'startTime' => $startTime,
        ]);
    }


    public function actionT2()
    {
        $start = time() + microtime();

        $db = Yii::$app->db;


        $order = 'last_activity_round DESC,point DESC,user_value DESC,level DESC,profile_photo_id DESC,id  DESC';//'last_activity_round DESC,`point` DESC,`user_value` DESC,`level` DESC,`profile_photo_id` DESC,`id` DESC';


        $query = $db->createCommand("SELECT nickname,full_name,age,user_value,id,sex,point,profile_photo,last_activity,last_activity_round,last_post,city_id FROM `user` WHERE
	id!=1 and status=10 and role=2 and deactive=0 and age>=18 and age<=60
	ORDER BY ".$order." LIMIT 12,20")
            ->queryAll();

        foreach($query as $q){
            echo $q["nickname"]."<br />";
        }
        $end = time() + microtime();
        $ferq = $end - $start;

        echo $ferq;

    }


    public function actionTopUsers()
    {
        $this->layout = "column3";

        $users = [];


        $query = User::getTopUsers();

        if ($query) {
            // Pagination
            $countQuery = clone $query;

            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            $pages->pageSize = 12;

            $users = $query->offset($pages->offset)
                ->orderBy(['U.top_like_count' => SORT_DESC])
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }

        return $this->render('top-users', [

            'pages' => $pages,
            'users' => $users,
        ]);
    }

    public function actionSearchOld()
    {
        if(intval(Yii::$app->user->identity->country_id) == 0){
            return $this->redirect(Url::to('/profile/complete'));
        }

        $this->layout = 'column3';

        $issetPost = false;

        $users = [];

        $userFilter = new UserFilter();

        $countries = Country::find()->orderBy(['code' => SORT_ASC])->asArray()->all();

        $countries = ArrayHelper::map($countries, 'id', 'name');

        $cities = [];

        $searchUser  = new SearchUser();

        $searchUser->ageRange = '['.User::AGE_MIN.','.User::AGE_MAX.']';

        $searchUser->sex = Yii::$app->params['defaultDiscoverySex'];


        if($searchUser->load(Yii::$app->request->post()) /*&& $userFilter->validate()*/){
            //var_dump($searchUser); exit;
            $query =   $searchUser->findUsersForSearch();

            if($query){
                //pagination

                $countQuery = clone $query;
                //$count = $countQuery->count('U.id');
                $pages = new Pagination(['totalCount' => $countQuery->count('U.id')]);

                $pages->pageSize = 12;
                //$pages->pageCount = 5;

                $users = $query->offset($pages->offset)
                    ->orderBy(['last_activity' => SORT_DESC,'level' => SORT_DESC,'profile_photo_id' => SORT_DESC,'id'=> SORT_DESC])
                    ->limit($pages->limit)
                    //->asArray()
                    ->all();

            }
            $issetPost = true;
        }

        return $this->render('search',[
            'searchUser' => $searchUser,
            'userFilter' => $userFilter,
            'users'  => $users,
            'pages' => $pages,
            'action' => $this->action->id,
            'countries' => $countries,
            'cities' => $cities,
            'issetPost' => $issetPost
         ]);
    }

    public function actionSearch()
    {
        if(intval(Yii::$app->user->identity->country_id) == 0){
            return $this->redirect(Url::to('/profile/complete'));
        }

        $this->layout = 'column3';

        $issetPost = false;

        $users = [];

        $userFilter = new UserFilter();

        $countries = Country::find()->orderBy(['code' => SORT_ASC])->asArray()->all();

        $countries = ArrayHelper::map($countries, 'id', 'name');

        $cities = [];

        $searchUser  = new SearchUser();

        $searchUser->ageRange = '['.User::AGE_MIN.','.User::AGE_MAX.']';

        $searchUser->sex = Yii::$app->params['defaultDiscoverySex'];


        if($searchUser->load(Yii::$app->request->get()) /*&& $userFilter->validate()*/){
            //var_dump($searchUser); exit;
            $query =   $searchUser->findUsersForSearch();

            if($query){
                //pagination

                $countQuery = clone $query;
                //$count = $countQuery->count('U.id');
                $pages = new Pagination(['totalCount' => $countQuery->count('U.id')]);

                $pages->pageSize = 12;
                //$pages->pageCount = 5;

                $users = $query->offset($pages->offset)
                    ->orderBy(['last_activity' => SORT_DESC,'level' => SORT_DESC,'profile_photo_id' => SORT_DESC,'id'=> SORT_DESC])
                    ->limit($pages->limit)
                    //->asArray()
                    ->all();

            }
            $issetPost = true;
        }

        return $this->render('search',[
            'searchUser' => $searchUser,
            'userFilter' => $userFilter,
            'users'  => $users,
            'pages' => $pages,
            'action' => $this->action->id,
            'countries' => $countries,
            'cities' => $cities,
            'issetPost' => $issetPost
        ]);
    }




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


    public function actionChangedPhoto()
    {
        $db = Yii::$app->db;
        $users  = $db->createCommand('SELECT id,changed_photo_url FROM `user` WHERE changed_photo_url="" and emeliyyat!=21 limit 5000')->queryAll();
        foreach($users as $user){
            $aloUser = $db->createCommand('SELECT id,photo FROM  `aloaz_db`.`chat_users` WHERE  id=:id')->bindValue(':id',$user["id"])->queryOne();
            if(trim($aloUser["photo"])!=""){
                $db->createCommand('UPDATE `user` SET changed_photo_url=:photo,emeliyyat=21 WHERE id=:id LIMIT 1')->bindValues([":photo"=>$aloUser["photo"],":id"=>$aloUser["id"]])->execute();
                echo $user["id"]." -deyisdirildi<br />";
            }else{
                $db->createCommand('UPDATE `user` SET emeliyyat=21 WHERE id=:id LIMIT 1')->bindValues([":id"=>$aloUser["id"]])->execute();
                echo $user["id"]." - SEkli yoxdur<br />";
            }
        }
    }


    protected function SqlInjectFilter($str) {
        $str = str_replace(" ",'',$str);
       // $str = mysql_real_escape_string($str);
        $str = str_replace("\n",'',$str);
        $str = str_replace("\t",'',$str);
        $str = str_replace("\r",'',$str);
        $str = str_replace("\0",'',$str);
        $str = str_replace("\x0B",'',$str);
        $str = str_replace("'",'',$str);
        $str = str_replace('"','',$str);
        $str = str_replace('\\','',$str);
        $str = str_replace('/','',$str);
        $str = str_ireplace (" and ","",$str);
        $str = str_ireplace ("execute ","",$str);
        $str = str_ireplace ("update ","",$str);
        $str = str_ireplace ("count ","",$str);
        $str = str_ireplace ("chr ","",$str);
        $str = str_ireplace ("mid ","",$str);
        $str = str_ireplace ("master ","",$str);
        $str = str_ireplace ("truncate ","",$str);
        $str = str_ireplace ("char ","",$str);
        $str = str_ireplace ("declare ","",$str);
        $str = str_replace ("select ","",$str);
        $str = str_ireplace ("create ","",$str);
        $str = str_ireplace ("delete ","",$str);
        $str = str_ireplace ("insert ","",$str);
        $str = str_ireplace ("union ","",$str);
        $str = str_replace ("\"","",$str);
        $str = str_replace ('"',"",$str);
        //$str = str_replace (" ","",$str);
        $str = str_replace ("$","",$str);
        $str = str_ireplace ("or ","",$str);
        $str = str_replace ("=","",$str);
        $str = str_replace ("% 20 ","",$str);
        $str = addslashes($str);
        return $str;
    }


    protected function detectCountry()
    {
        $country = false;

        if (Yii::$app->request->cookies->has('alochat_user_country')) {
            $country = Yii::$app->request->cookies->get('alochat_user_country')->value;
        }

        $db = Yii::$app->db;
        if(!isset($_GET["country"]) and !$country){
            $ip = ip2long($_SERVER["REMOTE_ADDR"]);
            $ip_row =  $db->createCommand('SELECT * FROM `ip_country` WHERE INET_ATON(`ip_start`)<=:ip AND INET_ATON(`ip_end`)>=:ip')->bindValues([":ip" =>$ip])->queryOne();
            if($ip_row)  $country = strtolower($ip_row["country_code"]);
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'alochat_user_country',
                'value' => $country,
                'expire' => time() +  60 * 60,

            ]));
        }else{
            if(isset($_GET["country"])){
                $country = strtoupper(htmlspecialchars(htmlentities($_GET["country"])));
                $countryIsset = $db->createCommand('SELECT count(id) FROM `country` WHERE `code`=:code')->bindValue(':code',$country)->queryScalar();
                if($countryIsset==0){
                    $ip = ip2long($_SERVER["REMOTE_ADDR"]);
                    $ip_row =  $db->createCommand('SELECT * FROM `ip_country` WHERE INET_ATON(`ip_start`)<=:ip AND INET_ATON(`ip_end`)>=:ip')->bindValues([":ip" =>$ip])->queryOne();
                    if($ip_row)  $country = strtolower($ip_row["country_code"]);
                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'alochat_user_country',
                        'value' => $country,
                        'expire' => time() +  60 * 60,

                    ]));
                }else{
                    $country = htmlspecialchars($_GET["country"]);
                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'alochat_user_country',
                        'value' => $country,
                        'expire' => time() +  60 * 60,

                    ]));
                }

            }
            elseif (Yii::$app->request->cookies->has('alochat_user_country')) {
                $country = Yii::$app->request->cookies->get('alochat_user_country')->value;
            }


        }

        if(isset($_GET["country"]))
        $this->changeLanguage(strtolower($country));
        return $country;
    }


    public function changeLanguage($id)
    {
        if (in_array($id, ['ge','kg', 'ua', 'kz', 'tj', 'by', 'uz', 'tm', 'ru'])) {
            $lang = 'ru';

        }elseif($id=='az'){
            $lang = 'az';
        }elseif($id == 'tr' ){
            $lang = 'tr';
        }else{
            $lang = 'en';
        }

         if(Yii::$app->language != $lang){
            Yii::$app->language = $lang;

         }


      /*  Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'alochat_language',
            'value' => trim($lang),
            'expire' => time() + 365 * 24 * 60 * 60,

        ]));*/
        return true;
    }




}
