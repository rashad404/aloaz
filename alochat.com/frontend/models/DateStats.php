<?php
namespace frontend\models;

use common\models\City;
use common\models\Country;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * ProfileSettings form
 */
class DateStats extends Model
{

    public $date_start;
    public $date_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['date_start', 'date'],
            ['date_end', 'date'],
        ];
    }




    public function attributeLabels()
    {

        return [

            'date_start' => Yii::t('app', 'Date Begin'),
            'date_end' => Yii::t('app', 'Date End'),


        ];
    }
}
