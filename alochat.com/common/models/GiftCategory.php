<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "gift_category".
 *
 * @property integer $id
 * @property string $name_az
 * @property string $name_en
 * @property string $name_ru
 * @property string $name_tr
 * @property integer $status
 */
class GiftCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gift_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_az','name_ru','name_en','name_tr'], 'required'],
            [['status'], 'integer'],
            [['name_az','name_ru','name_en','name_tr'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_az' => Yii::t('app', 'Kateqoriya adı (Azərbaycanca)'),
            'name_en' => Yii::t('app', 'Kateqoriya adı (İngiliscə)'),
            'name_ru' => Yii::t('app', 'Kateqoriya adı (Rusca)'),
            'name_tr' => Yii::t('app', 'Kateqoriya adı (Türkcə)'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public static function getStatusArray()
    {
        return ['0' => 'Deaktiv' , '1' => 'Aktiv'];
    }

    public static function getGiftCategories()
    {
        $categories  = self::find()->all();
        return ArrayHelper::map($categories,'id','name_az');
    }
}
