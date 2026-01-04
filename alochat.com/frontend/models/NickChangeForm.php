<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class NickChangeForm extends Model
{
    public $nickname;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['nickname', 'required'],
            ['password', 'string', 'min' => 6],
            ['nickname', 'string', 'min' => 3],
            ['password', 'validatePassword'],
            ['nickname', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'This nickname has already been taken.')],

        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function updateNick()
    {
        $user = User::findOne(Yii::$app->user->id);

        $user->coins = $user->coins - Yii::$app->params["changeNicknameCoin"];

        $user->nickname = $this->nickname;


        return $user->save(false);
    }

    public function validatePassword($attribute){

        $oldPassword = $this->$attribute;

        $user = User::findOne(Yii::$app->user->id);

        if (!$user || !$user->validatePassword($oldPassword)) {

            $this->addError($attribute, Yii::t('app','Incorrect password.'));
        }


    }

    public function attributeLabels(){

        return [

            'password' => Yii::t('app','Password'),
            'nickname' => Yii::t('app','New Nickname')
        ];
    }
}
