<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_activity".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $activity_id
 * @property integer $activity_count
 */
class UserActivity extends \yii\db\ActiveRecord
{

    /**
     *   activities id's
     * */
        const ACTIVITY_REGISTER = 1;
        const ACTIVITY_FIRST_PHOTO = 2;
        const ACTIVITY_MANY_PHOTO = 3;
        const ACTIVITY_ABOUT = 4;
        const ACTIVITY_FIRST_MESSAGE = 5;
        const ACTIVITY_MANY_CONVERSATION = 6;
        const ACTIVITY_FIRST_LIKE = 7;
        const ACTIVITY_VIP_USER = 8;
        const ACTIVITY_ONLINE_TIME = 9;
        const ACTIVITY_COUNT = 9;

        public static $activityParams = [
            'ACTIVITY_FIRST_PHOTO' => 1,
            'ACTIVITY_MANY_PHOTO'  => 3,
            'ACTIVITY_ABOUT'  => 50,
            'ACTIVITY_ONLINE_TIME'  => 3600,
            'ACTIVITY_MANY_CONVERSATION'  => 10,
        ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'activity_id', 'activity_count'], 'required'],
            [['user_id', 'activity_id', 'activity_count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'activity_id' => Yii::t('app', 'Activity ID'),
            'activity_count' => Yii::t('app', 'Activity Count'),
        ];
    }

    public static function activityOnlineTime()
    {
        if (!self::issetActivity(self::ACTIVITY_ONLINE_TIME))
        {
            $user = Yii::$app->getUser(Yii::$app->user->id)->identity;
            $last_activity = $user->last_activity;
            $last_login = $user->last_login;
            if((($last_activity - $last_login) > self::$activityParams["ACTIVITY_ONLINE_TIME"])) {

                self::activity(self::ACTIVITY_ONLINE_TIME);
            }
        }
    }


    public static function activityManyConversation()
    {
        if(!self::issetActivity(self::ACTIVITY_MANY_CONVERSATION)){
            $user_id = Yii::$app->user->identity->getId();
            $conversationCount = Conversation::find()->where('(user_one='.$user_id.' or user_two='.$user_id.') AND deleted_by=0')->count('id');
            if($conversationCount>= self::$activityParams['ACTIVITY_MANY_CONVERSATION']){
                self::activity(self::ACTIVITY_MANY_CONVERSATION);
            }
        }

    }

    public static function activityFirstMessage()
    {
        if(!self::issetActivity(self::ACTIVITY_FIRST_MESSAGE)){
            self::activity(self::ACTIVITY_FIRST_MESSAGE);
        }

    }

    public static function activityVipUser()
    {
        if(!self::issetActivity(self::ACTIVITY_VIP_USER)){
                self::activity(self::ACTIVITY_VIP_USER);
        }

    }

    public static function activityLike()
    {
        if(!self::issetActivity(self::ACTIVITY_FIRST_LIKE)){
            self::activity(self::ACTIVITY_FIRST_LIKE);
        }

    }

    public static function activityAbout()
    {
        if(!self::issetActivity(self::ACTIVITY_ABOUT)){
            if(strlen(Yii::$app->getUser(Yii::$app->user->id)->identity->about) >= self::$activityParams['ACTIVITY_ABOUT']){
                self::activity(self::ACTIVITY_ABOUT);
            }
        }

    }

    public static function activityPhoto()
    {
        if(!self::issetActivity(self::ACTIVITY_FIRST_PHOTO)){
            if(UserImage::getImagesCount()>=self::$activityParams['ACTIVITY_FIRST_PHOTO']){
                self::activity(self::ACTIVITY_FIRST_PHOTO);
            }
        }

        if(!self::issetActivity(self::ACTIVITY_MANY_PHOTO)){
            if(UserImage::getImagesCount()>=self::$activityParams['ACTIVITY_MANY_PHOTO']){
                self::activity(self::ACTIVITY_MANY_PHOTO);
            }
        }
    }




    public static function issetActivity($activityId)
    {
        $userId= Yii::$app->user->id;
        $activityIsset = self::find()
            ->where(["user_id" => $userId, "activity_id" => $activityId])
            ->count();
        if($activityIsset>=1) {
            return true;
        }else {
            return false;
        }
    }

    public static function activity($activityId)
    {
        $user_id = Yii::$app->user->id;

            if(self::setActivity($user_id,$activityId,1)){
                return true;
            }else
            {
                return false;
            }

    }


    public static function setActivity($user_id,$activity_id,$activity_count)
    {
        $userActivity = new UserActivity();

        $userActivity->user_id = $user_id;
        $userActivity->activity_id = $activity_id;
        $userActivity->activity_count = $activity_count;
        if($userActivity->save()){
            $user = User::find()->where(['id' => $user_id])->one();
            $level = $user->level;
            $user->level = $level + 1;
            $user->save(false);
            return true;
        }else {
            return false;
        }
    }

    public static function getActivityUser($user_id = NULL)
    {
        if($user_id == NULL){
            $level = Yii::$app->user->identity->level;
        }else{
            $user = User::find()->where(['id' => $user_id])->one();
            $level = $user->level;
        }

        $activity = ($level*100)/self::ACTIVITY_COUNT;
        return round($activity);

    }
}
