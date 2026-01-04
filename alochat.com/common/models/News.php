<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "share".
 *
 * @property integer $id
 * @property integer $author
 * @property string $title
 * @property string $body
 * @property integer $time
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author','time'], 'integer'],
            [['title','body'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author' => Yii::t('app', 'Author'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'time' => Yii::t('app', 'Time'),
        ];
    }
 
}
