<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            //['email', 'email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(){

        return [
            'email' => Yii::t('app','Email'),
            'password' => Yii::t('app','Password')
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app','Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        $type = $this->getType($this->email);
        if($type == 'email'){
            if ($this->_user === false) {
                $this->_user = User::findByEmail($this->email);
            }

            if ($this->_user === NULL) {
                $this->_user = User::findByNickname($this->email);
            }
        }elseif($type == 'phone'){
            if ($this->_user === false) {
                $this->_user = User::findByPhone($this->email);
            }

            if ($this->_user === NULL) {
                $this->_user = User::findByNickname($this->email);
            }
        }else {
            if ($this->_user === false) {
                $this->_user = User::findByNickname($this->email);
            }
        }

        return $this->_user;
    }

    public function getType($str)
    {
        if (filter_var($str, FILTER_VALIDATE_EMAIL) !== false) {
            $type = 'email';
        }elseif(strlen(intval($str))>=10 and intval($str)>0){
            $type = 'phone';
        }else{
            $type = 'nickname';
        }


        return $type;
    }
}
