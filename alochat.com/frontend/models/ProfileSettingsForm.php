<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * ProfileSettings form
 */
class ProfileSettingsForm extends Model
{
    public $full_name;
    public $about;
    public $sex;
    public $age;
    public $phone;
    public $only_friend;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['full_name', 'filter', 'filter' => 'trim'],

            ['full_name', 'required'],

            ['full_name', 'string', 'min' => 2, 'max' => 25],

            [['sex', 'age','only_friend'], 'required'],

            [['sex'], 'integer'],

            ['sex', 'in', 'range' => [USER::SEX_MAN, USER::SEX_WOMAN]],

            [['age'], 'integer', 'max' => User::AGE_MAX, 'min' => User::AGE_MIN],

            ['phone', 'string', 'min' => 13, 'max' => '50'],

            ['about', 'string'],
        ];
    }

    public function changeSettings()
    {
        if ($this->validate()) {

            $user = User::findOne(Yii::$app->user->id);
            $user->full_name = $this->full_name;
            $user->about = User::filterword(User::func_strip_tags($this->about));
            $user->sex = $this->sex;
            $user->age = $this->age;
            $user->only_friend = $this->only_friend;

            if ($user->save(false))
                return true;
            else
                return false;
        }

        return null;
    }


    public function attributeLabels()
    {
        return [

            'full_name' => Yii::t('app', 'Name'),
            'sex' => Yii::t('app', 'Sex'),
            'age' => Yii::t('app', 'Age'),
            'phone' => Yii::t('app', 'Phone'),
            'about' => Yii::t('app', 'About me'),

        ];
    }
}
