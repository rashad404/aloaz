<?php
namespace frontend\models;

use common\models\CoinLogs;
use common\models\Conversation;
use common\models\Notification;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class SendCoinForm extends Model
{
    public $nickname;
    public $coin;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['nickname', 'required'],
            ['coin', 'required'],
       //     ['coin', 'int', 'min' => 1],
            ['nickname', 'string', 'min' => 3],
         //   ['password', 'validatePassword'],

        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function sendCoin()
    {
        $user = User::findOne(Yii::$app->user->id);

        $coin = round($this->coin*100/80);

        $user->coins = $user->coins - $coin;

        if($user->save(false)){
            $user2 = User::findByNickname($this->nickname);
            $user2->coins =  $user2->coins + $this->coin;
            $message_text = $user->nickname.' niki tərəfindən sizə '.$this->coin.' bal göndərildi ';
            Conversation::sendBySystemMessage($user2->id,$message_text);
            Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,user_id2=:user_id2,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues([":user_id" => Yii::$app->user->id,":user_id2"=>$user2->id,":coins" => $coin,":type"=>1,":text" => CoinLogs::LOG_SEND_COIN,":date"=>date("Y-m-d H:i:s")])->execute();
            Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,user_id2=:user_id2,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues(["user_id"=>$user2->id,":user_id2" => Yii::$app->user->id,":coins" => $this->coin,":type"=>2,":text" => CoinLogs::LOG_RECEIVE_COIN,":date"=>date("Y-m-d H:i:s")])->execute();
            Notification::setNotification($user2->id,Notification::NOT_USER_COIN,time(),Yii::$app->user->id,Yii::$app->user->identity->nickname,intval($this->coin),0);

            return  $user2->save(false);
        }else {
            return false;
        }


     }


    public function attributeLabels(){

        return [

            'coin' => Yii::t('app','Coin'),
            'nickname' => Yii::t('app','Nickname')
        ];
    }
}
