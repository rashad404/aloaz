<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_like".
 *
 * @property integer $id
 * @property integer $report_from
 * @property integer $report_to
 * @property integer $time
 * @property integer $seen
 */
class UserReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_from', 'report_to', 'time'], 'required'],
            [['report_from', 'report_to', 'time', 'seen'], 'integer'],
            [['report_from', 'report_to'], 'unique', 'targetAttribute' => ['report_from', 'report_to'], 'message' => 'The combination of Like From and Like To has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'report_from' => Yii::t('app', 'Report From'),
            'report_to' => Yii::t('app', 'Report To'),
            'time' => Yii::t('app', 'Time'),
            'seen' => Yii::t('app', 'Seen'),
        ];
    }
}
