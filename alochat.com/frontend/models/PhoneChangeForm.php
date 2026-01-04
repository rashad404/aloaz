<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class PhoneChangeForm extends Model
{
     public $phone;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['phone', 'required'],
             ['phone', 'string', 'min' => 12],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function updatePassword()
    {
        $user = User::findOne(Yii::$app->user->id);

        $user->setPassword($this->password);

        $user->generateAuthKey();

        return $user->save(false);
    }

    public function validateOldPassword($attribute){

        $oldPassword = $this->$attribute;

        $user = User::findOne(Yii::$app->user->id);

        if (!$user || !$user->validatePassword($oldPassword)) {

            $this->addError($attribute, Yii::t('app','Incorrect password.'));
        }


    }

    public function attributeLabels(){

        return [

            'password' => Yii::t('app','New password'),
            'oldPassword' => Yii::t('app','Old password')
        ];
    }
}
