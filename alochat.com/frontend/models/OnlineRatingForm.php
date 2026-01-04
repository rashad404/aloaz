<?php
namespace frontend\models;

use common\models\CoinLogs;
use common\models\Conversation;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class OnlineRatingForm extends Model
{
    public $point;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['point', 'required'],

        ];
    }

    public function getPoints()
    {
        return [
            "1" => '1 xal (1 bal)',
            "5" => '5 xal (5 bal)',
            "10" => '10 xal (10 bal)',
            "50" => '50 xal (50 bal)',
            "100" => '100 xal (100 bal)',
            "500" => '500 xal (500 bal)',
            "1000" => '1000 xal (1000 bal)',
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function addPoint()
    {
        $user = User::findOne(Yii::$app->user->id);
        if(intval($this->point)>0){
            $coin  = $this->point;
            $user->coins = $user->coins - $coin;
            $user->point = $user->point + $this->point;
            if($user->save(false)){
                Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues([":user_id" => Yii::$app->user->id,":coins" => $coin,":type"=>1,":text" => CoinLogs::LOG_ADD_POINT,":date"=>date("Y-m-d H:i:s")])->execute();
                return $user;
            }else {
                return false;
            }
        }else{
            return false;
        }



    }


    public function attributeLabels(){

        return [

            'point' => Yii::t('app','Point'),
         ];
    }
}
