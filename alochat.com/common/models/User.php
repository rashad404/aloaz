<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use common\models\Conversation;

/**
 * User model
 *
 * @property integer $id
 * @property string $full_name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $nickname
 * @property string $about
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $ip
 * @property string $regfrom
 * @property string $onfrom
 * @property string $ref
 * @property integer $role
 * @property integer $sex
 * @property integer $age
 * @property integer $city_id
 * @property integer $phone
 * @property integer $country_id
 * @property integer $like_count
 * @property integer $top_like_count
 * @property integer $report_count
 * @property integer $user_value
 * @property string  $profile_photo
 * @property integer $profile_photo_id
 * @property integer $last_login
 * @property integer $last_activity
 * @property integer $last_activity_round
 * @property integer $block_time
 * @property integer $block_begin_time
 * @property integer $activity_coin_time
 * @property integer $emeliyyat
 * @property integer $social_login
 * @property integer $coins
 * @property integer $point
 * @property integer $verify
 * @property string $activation_code
 * @property string $md5_pass
 * @property integer $changed_photo
 * @property string $last_post
 * @property string birthday
 * @property int $only_friend
 * @property integer $deactive

 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    const SEX_MAN = 0;
    const SEX_WOMAN = 1;

    const AGE_MIN = 18;
    const AGE_MAX = 80;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const VIP_COINS = 50;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'sex', 'age','nickname'], 'required'],

            ['email', 'email'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['deactive', 'default', 'value' => 0],

            ['deactive', 'in', 'range' => [0, 1]],

            ['role', 'default', 'value' => self::ROLE_USER],

            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],

            ['sex', 'in', 'range' => [self::SEX_MAN, self::SEX_WOMAN]],

            [['age', 'coins','point', 'country_id', 'city_id'], 'integer'],

            ['age', 'in', 'range' => [self::AGE_MIN, self::AGE_MAX]],

            ['email', 'filter', 'filter' => 'trim'],

            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'This email address has already been taken.')],


            ['nickname', 'filter', 'filter' => 'trim'],

            ['full_name', 'string', 'min' => 4, 'max' => 20],

            ['nickname', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'This nickname has already been taken.')],

            ['phone', 'string', 'min' => 13, 'max' => 50],

            ['full_name', 'string', 'min' => 3, 'max' => 25],

            ['about', 'string', 'min' => 3, 'max' => 255],

            ['last_post', 'string', 'min' => 3, 'max' => 255],

            ['emeliyyat', 'safe'],['birthday', 'safe'],

            ['profile_photo', 'safe'],['changed_photo', 'safe'],

            ['last_post' , 'safe'],

            ['last_activity','safe'],

            ['last_activity_round','safe'],

            ['regfrom','safe'],

            ['onfrom','safe'],

            ['ref','safe'],

            ['block_time','safe'],

            ['block_begin_time','safe'],

            ['like_count','safe'],

            ['top_like_count','safe'],

            ['report_count','safe'],

            ['user_value','safe'],

            ['md5_pass','safe'],

            ['activity_coin_time','safe'],['activation_code','safe'],['verify','safe'],['last_login','safe'],['only_friend','safe'],['ip','safe'], ['social_login', 'safe']
        ];
    }


    public function attributeLabels()
    {
        return [

            'full_name' => 'Name',
            'last_post' => 'Status'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByNickname($nickname)
    {
        return static::findOne(['nickname' => $nickname, 'status' => self::STATUS_ACTIVE]);

    }

    public static function findByPhone($phone)
    {
        $phone = intval($phone);
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);

    }


    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    public static function isUserAdmin($email)
    {

        if (static::findOne(['email' => $email, 'role' => self::ROLE_ADMIN])) {

            return true;
        } else {

            return false;
        }

    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public static function getAgeArray()
    {

        $array = [];
        for ($i = self::AGE_MIN; $i <= self::AGE_MAX; $i++) {
            $array[$i] = $i;
        }

        return $array;

    }

    public static function getDays()
    {
        $array = [];
        for ($i = 1; $i <= 31; $i++) {
            $array[$i] = $i;
        }

        return $array;
    }

    public static function getMonths()
    {
        $array = [1=> 'Yanvar',2=>'Fevral',3=>'Mart',4=>'Aprel',5=>'May',6=>'İyun',7=>'İyul',8=>'Avqust',9=>'Sentyabr',10=>'Oktyabr',11=>'Noyabr',12=>'Dekabr'];


        return $array;
    }

    public static function getYears()
    {
        $array = [];
        for ($i = date('Y')-70; $i <= date('Y'); $i++) {
            $array[$i] = $i;
        }

        return $array;
    }

    public function getNewMessagesCount()
    {

        return ConversationReply::getNewMessagesCount($this->id);
    }

    public function getNewNotificationCount()
    {
        return  Notification::getNewNotificationCount($this->id);
    }

    public function readNotification()
    {
        return Notification::readNotification($this->id);
    }

    public function getNewNotificationText()
    {
        return Notification::getNewNotificationText($this->id);
    }

    public function getCity()
    {

        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function getCountry()
    {

        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    public function getImages()
    {
        return $this->hasMany(UserImage::className(), ['id', 'user_id']);
    }

    public static function getSameCountryUserCount() {
        return self::find()->where(['country_id' => Yii::$app->user->identity->country_id])->count('id');
    }

    public static function getSameCountryUserCount1() {
        return Yii::$app->db->createCommand('SELECT count(id) FROM `user` WHERE `country_id`=:country_id')->bindValue(":country_id",Yii::$app->user->identity->country_id)->queryScalar();
        //return self::find()->where(['country_id' => Yii::$app->user->identity->country_id])->count('id');
    }



    public function isOnline()
    {

        if ($this->last_activity > (time() - Yii::$app->params['userOnlineStatusCheckTime']))
            return true;
        else
            return false;
    }

    public static function isOnlineWithActivity($last_activity)
    {

        if ($last_activity > (time() - Yii::$app->params['userOnlineStatusCheckTime']))
            return true;
        else
            return false;
    }

    public static function getOnlineUserCount()
    {

        /*$users = User::find()->where([
            '>', 'last_activity', (time() - Yii::$app->params['userOnlineStatusCheckTime']
            )])->all();*/

        $users = Yii::$app->db->createCommand('SELECT count("id") FROM user WHERE no_dating=1 and last_activity>'.(time() - Yii::$app->params['userOnlineStatusCheckTimeForCount']))->queryScalar();

        return $users;
    }

    public static function getOnlineUserCountForAdmin()
    {

        /*$users = User::find()->where([
            '>', 'last_activity', (time() - Yii::$app->params['userOnlineStatusCheckTime']
            )])->all();*/

        $users = Yii::$app->db->createCommand('SELECT count("id") FROM user WHERE last_activity>'.(time() - Yii::$app->params['userOnlineStatusCheckTimeForCount']))->queryScalar();

        return $users;
    }

    public static function getOnlineUserCount1()
    {

        /*$users = User::find()->where([
            '>', 'last_activity', (time() - Yii::$app->params['userOnlineStatusCheckTime']
            )])->all();*/

        $web = Yii::$app->db->createCommand('SELECT count("id") FROM user WHERE onfrom="web" and last_activity>'.(time() - Yii::$app->params['userOnlineStatusCheckTimeForCount']))->queryScalar();
        $android = Yii::$app->db->createCommand('SELECT count("id") FROM user WHERE onfrom="android" and last_activity>'.(time() - Yii::$app->params['userOnlineStatusCheckTimeForCount']))->queryScalar();
        $mobile = Yii::$app->db->createCommand('SELECT count("id") FROM user WHERE onfrom="mobile" and last_activity>'.(time() - Yii::$app->params['userOnlineStatusCheckTimeForCount']))->queryScalar();

        $web = '<a href="'.Url::to(['/user/online/?onfrom=web']).'">'.$web.'</a>';
        $android = '<a href="'.Url::to(['/user/online/?onfrom=android']).'">'.$android.'</a>';
        $mobile = '<a href="'.Url::to(['/user/online/?onfrom=mobile']).'">'.$mobile.'</a>';

        return "(web ".$web.") - (android ".$android.") - (mobile ".$mobile.")";;
    }



    public static function getOnlineUsers($onform = null)
    {

        $users = User::find()->where([
            '>', 'last_activity', (time() - Yii::$app->params['userOnlineStatusCheckTime']
            )]);
        if($onform != null){
            if($onform == 'web'){
                $users->andWhere('onfrom="web"');
            }elseif($onform == 'android'){
                $users->andWhere('onfrom="android"');
            }elseif($onform == 'mobile'){
                $users->andWhere('onfrom="mobile"');
            }
        }
        return $users;
    }

    public static function getOnlineUsersForHome()
    {
        $db = Yii::$app->db;
        $order = 'last_activity_round DESC,user_value DESC,profile_photo_id DESC,id  DESC';
        $users = $db->createCommand('SELECT id,profile_photo,nickname,age FROM `user` WHERE last_activity>"'.(time() - Yii::$app->params['userOnlineStatusCheckTime']).'" and verify=1 and profile_photo!="" ORDER BY '.$order.' limit 10')->queryAll();
        return $users;
    }

    public static function getAllUserCount()
    {
        $users  = Yii::$app->db->createCommand("SELECT count('id') FROM `user`")->queryScalar();
      // $users = User::find()->all();

        return $users;
    }



    public function userLiked()
    {
        $liked = UserLike::findOne(['like_from' => Yii::$app->user->id, 'like_to' => $this->id]);
        if ($liked)
            return true;
        else
            return false;

    }

    public function userLikedById($user_id)
    {
        $liked = UserLike::findOne(['like_from' => Yii::$app->user->id, 'like_to' => $user_id]);
        if ($liked)
            return true;
        else
            return false;

    }



    public function userBlocked()
    {
        $blocked = UserBlock::findOne(['block_from' => Yii::$app->user->id, 'block_to' => $this->id]);


        if($blocked) {
            return true;
        } else {
            return false;
        }
    }

    public function userReported()
    {
        $reported = UserReport::findOne(['report_from' => Yii::$app->user->id, 'report_to' => $this->id]);


        if($reported) {
            return true;
        } else {
            return false;
        }
    }

    public function userIsFriend()
    {
        $friend = UserFriend::find()->where('(user_1='.Yii::$app->user->id.' and user_2='.$this->id.') or (user_2='.Yii::$app->user->id.' and user_1='.$this->id.')')->one();

        if($friend->ok == 0){

        }


        if($friend) {
            return true;
        } else {
            return false;
        }
    }


    public static function friendStatus($id)
    {
        $userId = Yii::$app->user->id;
        $friend = UserFriend::find()->where('(user_1='.Yii::$app->user->id.' and user_2='.$id.') or (user_2='.Yii::$app->user->id.' and user_1='.$id.')')->one();
        $status = 0;


        if($friend->ok == 0 and $friend->user_1 == $userId){
            // tesdiq olunmamisdir
            $status = 1;
        } elseif($friend->ok == 0 and $friend->user_2 == $userId){
            // sorgunuz gonderilib tesdiq olunmalidir
            $status = 2;
        } elseif($friend->ok == 1) {
            // dostsunuz
            $status = 3;
        }
        return $status;
    }


    public static function userBlockedId($id)
    {
        $blocked = UserBlock::findOne(['block_from' => Yii::$app->user->id, 'block_to' => $id]);

        if($blocked) {
            return true;
        } else {
            return false;
        }
    }

    public function getVipUsers()
    {
        $users = UserVip::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.profile_photo',
                'C.name as city_name',
                'V.time'
            ])
            ->from("vip_user V")
            ->innerJoin('user U','V.user_id=U.id')
            ->leftJoin('city C','U.city_id=C.id');

        return $users;
    }

    public function getLikedUsers()
    {
        $users = UserLike::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.profile_photo',
                'C.name as city_name',
                'L.time'
            ])
            ->from("user_like L")
            ->innerJoin('user U', 'L.like_to=U.id')
            ->leftJoin('city C', 'U.city_id=C.id')
            ->where('L.like_from=:id', [':id' => \Yii::$app->user->id]);

        return $users;
    }



    public function getFriendUsers()
    {

        $userId = Yii::$app->user->id;

        $users = UserFriend::findBySql("
        SELECT u.full_name,u.nickname,u.age,u.id,u.sex,u.profile_photo,C.name as city_name,F.request_time,u.last_activity,F.seen,F.user_2,F.user_1,u.last_post,F.ok
            FROM user_friend F, user u
            LEFT JOIN city C on u.city_id=C.id
            WHERE
              CASE
                  WHEN F.user_1 = $userId
                  THEN F.user_2 = u.id
                  WHEN F.user_2 = $userId
                  THEN F.user_1 = u.id
              END
        AND (F.user_1=$userId or F.user_2=$userId)
        ORDER BY F.ok ASC,F.id DESC
         ");

        return  $users;

    }


    public function getLikeUsers()
    {
        $users = UserLike::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.profile_photo',
                'C.name as city_name',
                'L.time'
            ])
            ->from("user_like L")
            ->innerJoin('user U', 'L.like_from=U.id')
            ->leftJoin('city C', 'U.city_id=C.id')
            ->where('L.like_to=:id', [':id' => \Yii::$app->user->id]);

        return $users;
    }

    public static function getLikeCountUsers($id)
    {
        $count = UserLike::find()
            ->from('user_like L')
            ->where('L.like_to=:id',[':id'=>$id])
            ->count();
        return $count;
    }

    public static function getSexArray()
    {
        return [self::SEX_MAN => Yii::t('app','Man'), self::SEX_WOMAN => Yii::t('app','Woman')];
    }

    public static function getVerifyArray()
    {
        return [0 => Yii::t('app','Not  verified'), 1 => Yii::t('app','Verified')];
    }

    public static function getSexValue($sex)
    {
        $value = '';
        if($sex == self::SEX_MAN){
            $value = Yii::t('app','Man');
        }elseif($sex == self::SEX_WOMAN){
            $value = Yii::t('app','Woman');
        }
        return $value;

    }

    public function getVisitors()
    {
        $users = UserVisit::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.profile_photo',
                'C.name as city_name',
                'v.time',
                'U.last_post',
                'U.last_activity'
            ])
            ->from("user_visit v")
            ->innerJoin('user U', 'v.visit_from=U.id')
            ->leftJoin('city C', 'U.city_id=C.id')
            ->where('v.visit_to=:id', [':id' => \Yii::$app->user->id]);

        return $users;
    }

    public static function getTopUsers()
    {
        $users = User::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.profile_photo',
                'C.name as city_name',
                'U.last_post',
                'U.last_activity'
            ])
            ->from("user U")
            ->leftJoin('city C', 'U.city_id=C.id')
            ->where('U.top_like_count>0');

        return $users;
    }

    public function getMutualLikes()
    {
        $users = UserLike::find()
            ->select([
                'U.full_name',
                'U.nickname',
                'U.age',
                'U.id',
                'U.sex',
                'U.profile_photo',
                'C.name as city_name',
                'p.time'
            ])
            ->from("user_like p")
            ->innerJoin('user_like p2', 'p.like_from=p2.like_to AND p.like_to=p2.like_from ')
            ->innerJoin('user U', 'p.like_from=U.id')
            ->leftJoin('city C', 'U.city_id=C.id')
            ->where('p.like_to=:id', [':id' => \Yii::$app->user->id]);

        return $users;
    }

    public function updateLastActivity()
    {
        $time = time();
        $this->last_activity = $time;
        $this->last_activity_round = substr($time,0,7);
        $this->onfrom = 'web';
        $this->update(false, ['last_activity','onfrom','last_activity_round']);
    }

    public function updateActivityCoin()
    {
        //online-de qaldigi vaxta gore bal artirmaq
        return true;

        if((($this->last_activity - $this->last_login) > Yii::$app->params["activityTimeForCoin"]) and (time() - $this->activity_coin_time) > Yii::$app->params["activityTimeForCoin"]){
            $newCoin = $this->coins + Yii::$app->params["activityCoin"];
            $this->coins = $newCoin;
            $this->activity_coin_time = time();
            if($this->save(false, ['coins', 'activity_coin_time'])){
                $message = Yii::t('app','AloChat was presented to you by {coin} Coins. Have been active for {minute} minutes and earn {coin} Coins. Be active!',['coin' => Yii::$app->params["activityCoin"],'minute' => (Yii::$app->params["activityTimeForCoin"]/60)]);
              //  Conversation::sendBySystemMessage($this->id,$message);
                Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues([":user_id" => Yii::$app->user->id,":coins" => Yii::$app->params["activityCoin"],":type"=>2,":text" => CoinLogs::LOG_RECEIVE_COIN_ALOCHAT,":date"=>date("Y-m-d H:i:s")])->execute();
                Notification::setNotification(Yii::$app->user->id,Notification::NOT_ALOCHAT_COIN,time(),1,'',Yii::$app->params["activityCoin"],0);
            }
        }
    }

    public function setLoginDate()
    {
        if (Yii::$app->params['userLastLoginCheckTime'] < (time() - $this->last_activity))
        {
            $this->last_login = time();

            $this->save(false, ['last_login']);
        }
    }

    public function loginTime()
    {
        //$this->last_activity-$this->last_login;
    }

    public static function getShares()
    {
        $qr = (new Query());

        $qr->select(['S.user_id','S.text','S.attach','U.id','U.profile_photo','U.nickname','S.time','U.last_activity'])
        ->from('share S')
        ->leftJoin('user U', 'S.user_id=U.id');

        return $qr;
    }

    public static function findUsersForFilter($params)
    {
        $users = [];

        $qr = (new Query());

        $qr->select(['U.full_name','U.nickname',
            'U.age',
            'U.user_value',
            'U.id',
            'U.sex',
            'U.point',
            'U.profile_photo',
            'U.last_activity',
            'U.last_activity_round',
            'U.last_post',
            'C.name as city_name'])
        ->from('user U')
        ->leftJoin('city C', 'U.city_id=C.id')
        ->where(['U.status' => User::STATUS_ACTIVE, 'U.role' => User::ROLE_USER, 'U.deactive' => 0 ])
         ->andWhere(['!=','U.id',Yii::$app->user->id])
        //->andWhere(['!=','U.f_row',1])
        ->andWhere(['between','U.age',$params['ageMin'], $params['ageMax']]);

        if($params['photo']>0){
            $qr->andWhere("U.profile_photo!='' AND U.profile_photo IS NOT NULL");
        }

        if($params['country'] > 0) {
            $qr->andWhere(['U.country_id' => $params['country']]);
        }

        if($params['city'] > 0) {
            $qr->andWhere(['U.city_id' => $params['city']]);
        }

        if($params['online'] > 0) {

            $qr->andWhere([ '>', 'last_activity', (time() - Yii::$app->params['userOnlineStatusCheckTime'])]);
        }

        if($params['sex'] < 2) {
            $qr->andWhere(['U.sex' => $params['sex']]);
        }



        return $qr;

    }


    public static function findUsersForFilter1($params)
    {
        $users = [];
        $db = Yii::$app->db;

        $select = 'U.nickname,U.full_name,U.age,U.user_value,U.id,U.sex,U.point,U.profile_photo,U.last_activity,U.last_activity_round,U.last_post,U.city_id,C.`name` ';
        $from = '`user` U LEFT JOIN city C ON U.city_id=C.id';
        $where = 'U.id!=:id and U.status=:status and U.role=:role and U.deactive=0 and U.age>=:ageMin and U.age<=:ageMax';
        $order = 'last_activity_round DESC,point DESC,user_value DESC,level DESC,profile_photo_id DESC,id  DESC';//
        $values = [
            ":id" => Yii::$app->user->id,
            ":status" => User::STATUS_ACTIVE,
            ":role" => User::ROLE_USER,
            ":ageMin" => $params["ageMin"],
            ":ageMax" => $params["ageMax"],
        ];
        if($params['country'] > 0) {
            $where .= ' and U.country_id=:country_id';
            $values[":country_id"] = $params["country"];
        }

        if($params['city'] > 0) {
            $where .= ' and U.city_id=:city_id';
            $values[":city_id"] = $params["city"];
        }

        if($params['online'] > 0) {
            $where .= ' and U.last_activity>:last_activity';
            $values[":last_activity"] = (time() - Yii::$app->params['userOnlineStatusCheckTime']);
        }

        if($params['sex'] < 2) {
            $where .= ' and U.sex=:sex';
            $values[":sex"] = $params["sex"];
        }

        $limit = $params["offset"].",".$params["limit"];

        $query = $db->createCommand('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY '.$order.' LIMIT '.$limit)
            ->bindValues($values)
            ->queryAll();

        return $query;

    }

    public static function findUsersForFilterCount($params)
    {
        $users = [];
        $db = Yii::$app->db;

        $select = ' count(U.id)';
        $from = '`user` U';
        $where = 'U.id!=:id and U.status=:status and U.role=:role and U.deactive=0 and U.age>=:ageMin and U.age<=:ageMax';
        //$order = 'last_activity_round DESC,`point` DESC,`user_value` DESC,`level` DESC,`profile_photo_id` DESC,`id` DESC';
        $values = [
            ":id" => Yii::$app->id,
            ":status" => User::STATUS_ACTIVE,
            ":role" => User::ROLE_USER,
            ":ageMin" => $params["ageMin"],
            ":ageMax" => $params["ageMax"],
        ];
        if($params['country'] > 0) {
            $where .= ' and U.country_id=:country_id';
            $values[":country_id"] = $params["country"];
        }

        if($params['city'] > 0) {
            $where .= ' and U.city_id=:city_id';
            $values[":city_id"] = $params["city"];
        }

        if($params['online'] > 0) {
            $where .= ' and U.last_activity>:last_activity';
            $values[":last_activity"] = (time() - Yii::$app->params['userOnlineStatusCheckTime']);
        }

        if($params['sex'] < 2) {
            $where .= ' and U.sex=:sex';
            $values[":sex"] = $params["sex"];
        }


        $query = $db->createCommand('SELECT '.$select.' FROM '.$from.' WHERE '.$where)
            ->bindValues($values)
            ->queryScalar();

        return $query;

    }

    public static function findUsersForDiscovery($params)
    {

       if((Yii::$app->request->cookies->get('discoveryCurrentUserId')) !== null)
       {
           $discoveryCurrentUserId = (Yii::$app->request->cookies->get('discoveryCurrentUserId')->value);
        }else{
           $discoveryCurrentUserId = 0;
       }

        $users = [];

        $previous = [];
        $current = [];
        $next = [];



        $qr = (new Query());
        $qrc = (new Query());


        $qrc->select('U.id,U.profile_photo,U.full_name,U.age')
            ->from('user U')
            ->where(['U.status' => User::STATUS_ACTIVE, 'U.role' => User::ROLE_USER])
            ->andWhere(['!=', 'U.id', Yii::$app->user->id])
            ->andWhere(['>=', 'U.id', $discoveryCurrentUserId])
            ->andWhere("U.profile_photo!='' AND U.profile_photo IS NOT NULL ")
            ->andWhere(['between', 'U.age', $params['ageMin'], $params['ageMax']]);

         $qr->select('U.id,U.profile_photo,U.full_name,U.age,C.name as city_name')
            ->from('user U')
            ->leftJoin('city C', 'C.id=U.city_id')
            ->where(['U.status' => User::STATUS_ACTIVE, 'U.role' => User::ROLE_USER])
            ->andWhere(['!=', 'U.id', Yii::$app->user->id])
            ->andWhere("U.profile_photo!='' AND U.profile_photo IS NOT NULL ")
            ->andWhere(['between', 'U.age', $params['ageMin'], $params['ageMax']]);





        if (intval($params['country_id']) > 0) {

            $qrc->andWhere(['U.country_id' => $params['country_id']]);
            $qr->andWhere(['U.country_id' => $params['country_id']]);
        }
        if (intval($params['city_id']) > 0) {

            $qrc->andWhere(['U.city_id' => $params['city_id']]);
            $qr->andWhere(['U.city_id' => $params['city_id']]);
        }
        if (intval($params['sex']) < 2) {

            $qrc->andWhere(['U.sex' => $params['sex']]);
            $qr->andWhere(['U.sex' => $params['sex']]);
        }


        if($qrc->count()<3){
            $qr->andWhere(['<=', 'U.id', $discoveryCurrentUserId]);
        }else {
            $qr->andWhere(['>=', 'U.id', $discoveryCurrentUserId]);
        }

        $qr->orderBy(['id' => SORT_ASC])->limit(3);

        $command = $qr->createCommand();

        $res = $command->queryAll();

        $count = count($res);

        if ($count > 0) {

            if ($count == 3) {

                $previous = $res[0];
                $previous['profile_photo'] = Url::base() . $previous['profile_photo'];

                $current = $res[1];
                $next = $res[2];
                $next['profile_photo'] = Url::base() . $next['profile_photo'];

            } elseif ($count == 2) {
                $current = $res[0];
                $next = $res[1];
                $next['profile_photo'] = Url::base() . $next['profile_photo'];

            } elseif ($count == 1) {

                $current = $res[0];
            }

            $details = (new Query())->select('U.age,U.full_name,C.name as city_name,I.path as main_photo,I.id as profile_photo_id')
                ->from('user U')
                ->where(['U.id' => $current['id']])
                ->leftJoin('city C', 'C.id=U.city_id')
                ->innerJoin('user_image_resized I', 'I.id=U.profile_photo_id')
                ->limit(1)
                ->one();


            $details['main_photo'] = Url::base() . $details['main_photo'];

            $allImages = (new Query())->select('path')->from('user_image_thumb')
                ->where(['user_id' => $current['id']])
                ->andWhere(['!=', 'id', $details['profile_photo_id']])
                ->all();

            $allImageCount = count($allImages);

            if ($allImageCount >= 2) {

                $details['images'] = [Url::base() . $allImages[0]['path'], Url::base() . $allImages[1]['path']];
            }

            if ($allImageCount > 2)
                $details['remainingImageCount'] = $allImageCount - 2;

            $current = array_merge($current, $details);
        }
        $res = [
            'previous' => $previous,
            'current' => $current,
            'next' => $next
        ];

        return $res;
    }

    public static function getDiscoveryFindUser($currentId, $direction)
    {
        $currentId = intval($currentId);

        // Get current user details
        $currentUser = (new Query())->select('U.id,U.age,U.full_name,C.name as city_name,I.path as main_photo,I.id as profile_photo_id')
            ->from('user U')
            ->where(['U.id' => $currentId])
            ->leftJoin('city C', 'C.id=U.city_id')
            ->innerJoin('user_image_resized I', 'I.id=U.profile_photo_id')
            ->limit(1)
            ->one();

        if (!empty($currentUser)) {
             // Get user images
            $allImages = (new Query())->select('path')->from('user_image_thumb')
                ->where(['user_id' => $currentId])
                ->andWhere(['!=', 'id', $currentUser['profile_photo_id']])
                ->all();

            $allImageCount = count($allImages);

            $currentUser['main_photo'] = Url::base() . $currentUser['main_photo'];
            if ($allImageCount >= 2) {
                $currentUser['images'] = [Url::base() . $allImages[0]['path'], Url::base() . $allImages[1]['path']];
            }

            if ($allImageCount > 2)
                $currentUser['remainingImageCount'] = $allImageCount - 2;

            unset($currentUser['profile_photo_id']);
        }

        // Find next or previous user by direction parameter
        $params = self::getFilterParams();

        $qr = (new Query());

        $qr->select('U.id,U.profile_photo,U.full_name,U.age,C.name as city_name')
            ->from('user U')
            ->leftJoin('city C', 'C.id=U.city_id')
            ->where(['U.status' => User::STATUS_ACTIVE, 'U.role' => User::ROLE_USER])
            ->andWhere(['!=', 'U.id', Yii::$app->user->id])
            ->andWhere("U.profile_photo!='' AND U.profile_photo IS NOT NULL ")
            ->andWhere(['between', 'U.age', $params['ageMin'], $params['ageMax']]);

        if ( intval($params['country']) > 0)
            $qr->andWhere(['U.country_id' => $params['country']]);

        if (intval($params['city']) > 0)
            $qr->andWhere(['U.city_id' => $params['city']]);

        if (intval($params['sex']) != 2)
            $qr->andWhere(['U.sex' => $params['sex']]);

        if ($direction == "right") {

            $qr->andWhere(['>', 'U.id', $currentId]);
            $qr->orderBy(['id' => SORT_ASC])->limit(1);
        } else {

            $qr->andWhere(['<', 'U.id', $currentId]);
            $qr->orderBy(['id' => SORT_DESC])->limit(1);
        }

        $command = $qr->createCommand();
        $nextUser = $command->queryOne();

        if (!empty($nextUser)) {
            $nextUser['profile_photo'] = Url::base() . $nextUser['profile_photo'];
        }

        return ['currentUser' => $currentUser, 'nextUser' => $nextUser];
    }

    public static function getFilterParams()
    {
        // Get filter parameters

        $filterData = [];

        if (Yii::$app->request->cookies->has('filterData')) {

            $filterData =Yii::$app->request->cookies->get('filterData')->value;

        } else {
            $filterData['city'] = 0;
            $filterData['country'] = Yii::$app->user->identity->country_id;
            $filterData['sex'] = !Yii::$app->user->identity->sex;
            $filterData['ageMin'] = User::AGE_MIN;
            $filterData['ageMax'] = 40;
        }

        return $filterData;
    }


    public static function getNickname($full_name)
    {
        $nick = self::toAscii($full_name);
        $nickname = self::getNewNickname($nick);
        return $nickname;
    }

    public static function getNewNickname($nickname)
    {
        $db = Yii::$app->db;
        $isset = $db->createCommand("select count(id) from `user` where nickname='".$nickname."'")->queryScalar();
        if($isset and strlen($nickname)>3){
            return self::getNewNickname($nickname."_".rand(110,999));
        }else {
            return $nickname;
        }
    }

    public static function toAscii($str, $replace=array(), $delimiter='.') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    public static function sendWelcomeMessage($user_id)
    {
        $message = Yii::t('app','welcome_message')."<br />".Yii::t('app','This message was sent by {site_name} automatically. For this reason it is impossible to answer.',['site_name'=>'Alochat']);
        Conversation::sendBySystemMessage($user_id,$message);
    }

    public static function filterword($word)
    {
        $filter_words = array('<script','</script>');
        $f_word = str_replace($filter_words,'*',$word);
        return $f_word;
    }


    public static function func_strip_tags($str)
    {
        $string = htmlentities(strip_tags(trim($str)));
        return $string;

    }

    public static function generatePassword($length = '')
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max = strlen($str);
        $length = @round($length);
        if (empty($length)) {
            $length = rand(8, 12);
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $str{rand(0, $max - 1)};
        }
        return $password;
    }
}
