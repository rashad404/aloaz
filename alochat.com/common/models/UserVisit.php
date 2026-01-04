<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_visit".
 *
 * @property integer $id
 * @property integer $visit_from
 * @property integer $visit_to
 * @property integer $time
 * @property integer $seen
 * @property integer $count
 */
class UserVisit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_visit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visit_from', 'visit_to', 'time'], 'required'],
            [['visit_from', 'visit_to', 'time', 'seen', 'count'], 'integer'],
            [['visit_from', 'visit_to'], 'unique', 'targetAttribute' => ['visit_from', 'visit_to'], 'message' => 'The combination of Visit From and Visit To has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'visit_from' => Yii::t('app', 'Visit From'),
            'visit_to' => Yii::t('app', 'Visit To'),
            'time' => Yii::t('app', 'Time'),
            'count' => Yii::t('app', 'Count'),
            'seen' => Yii::t('app', 'Seen'),
        ];
    }
}
