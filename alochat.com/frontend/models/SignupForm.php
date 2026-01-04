<?php
namespace frontend\models;

use common\models\User;
use common\models\Conversation;
use yii\base\Model;
use Yii;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $full_name;
    public $sex;
    //public $age;
    public $email;
    public $nickname;
    public $password;
    public $b_day;
    public $b_month;
    public $b_year;
    public $ref;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['full_name', 'filter', 'filter' => 'trim'],
            ['full_name', 'required'],
            ['full_name', 'string', 'min' => 2, 'max' => 25],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app','This email address has already been taken.')],

            ['nickname', 'filter', 'filter' => 'trim'],
            ['nickname', 'required'],
            ['nickname', 'string', 'min' => 4, 'max' => 20],
            ['nickname', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app','This nickname address has already been taken.')],

            [['sex'], 'required'],

            [['sex'], 'integer'],

            ['sex', 'in', 'range' => [USER::SEX_MAN, USER::SEX_WOMAN]],

            //[['age'], 'integer','max'=>User::AGE_MAX,'min'=>User::AGE_MIN ],

            [['b_day'], 'integer','min'=>1,'max'=>31 ],
             [['b_year'], 'integer','max'=>date('Y'),'min'=>date('Y')-60 ],
            ['b_month', 'integer'],


            ['password', 'required'],

            ['password', 'string', 'min' => 6],

            [['ref'],'safe']

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {

            $user = new User();
            $user->full_name = $this->full_name;
            $user->email = $this->email;
            $user->nickname = $this->nickname;
            $user->sex = $this->sex;
            $user->age = date('Y') - $this->b_year;
            $user->password = $this->password;
            $user->md5_pass = md5($this->password);
            $user->role = User::ROLE_USER;
            $user->last_post = '';
            $user->about = '';
            $user->ref = $this->ref;
            $user->regfrom = 'web';


            $user->birthday = $this->b_year."-".$this->b_month."-".$this->b_day;
            $ip =  Yii::$app->ipgeobase->getIP();
            $user->ip = $ip;

            $geoData = Yii::$app->ipgeobase->getLocation($ip);

            $countryId = intval($geoData['country_id']);

           $cityId = intval($geoData['city_id']);

            if($countryId)
                $user->country_id = $countryId;

           if($cityId && $geoData['city']!= '-')
               $user->city_id = $cityId;

            $user->setPassword($this->password);

            $user->generateAuthKey();

            if ($user->save(false)) {
                User::sendWelcomeMessage($user->id);
                return $user;
            }
        }

        return null;
    }




    public function attributeLabels()
    {


        return [

            'full_name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'nickname' => Yii::t('app', 'Nick'),
            'sex' => Yii::t('app', 'Sex'),
            'age' => Yii::t('app', 'Age'),
            'password' => Yii::t('app', 'Password'),

        ];
    }
}
