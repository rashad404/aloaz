<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class PasswordChangeForm extends Model
{
    public $oldPassword;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['oldPassword', 'required'],
            ['password', 'string', 'min' => 6],
            ['oldPassword', 'string', 'min' => 6],
            ['oldPassword', 'validateOldPassword'],
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

        $user->password = $this->password;
        $user->md5_pass = md5($this->password);

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
