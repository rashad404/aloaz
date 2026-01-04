<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "share".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $text
 * @property string $attach
 * @property integer $like_count
 * @property integer $read_count
 * @property integer $comment_count
 * @property integer $permission
 * @property integer $country
 * @property integer $time
 * @property integer $status
 */
class Share extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'share';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'like_count', 'read_count', 'comment_count', 'permission', 'country', 'time', 'status'], 'integer'],
            [['text'], 'string'],
            [['attach'], 'string', 'max' => 150],
            [['file'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'text' => Yii::t('app', 'Text'),
            'attach' => Yii::t('app', 'Attach'),
            'like_count' => Yii::t('app', 'Like Count'),
            'read_count' => Yii::t('app', 'Read Count'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'permission' => Yii::t('app', 'Permission'),
            'country' => Yii::t('app', 'Country'),
            'time' => Yii::t('app', 'Time'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public static function getDate($time)
    {
        $date = '';
        $today = date("d");

        $day = date("d", $time);

        if ($day == $today) {
            $date = Yii::t('app', 'today') . ' ' . date('H:i', $time);
        } elseif ($day == ($today - 1)) {

            $date= Yii::t('app', 'yesterday') . ' ' . date('H:i', $time);
        } else
            $date = date('d M Y', $time).'&nbsp;&nbsp; '.date('H:i', $time);

        return $date;
    }

    public static function liked($user_id, $share_id)
    {
        $db = Yii::$app->db;
        $count = $db->createCommand('SELECT count(`id`) FROM share_like WHERE sid="'.$share_id.'" and uid="'.$user_id.'"')->queryScalar();
        if($count>0){
            return true;
        }else {
            return false;
        }
    }

    public static function getShareKeywords($text)
    {
        $words = $text;
        $exp_words = explode(" ",$words);
        $i = 1;
        $keys='';
        foreach($exp_words as $word){
            if(strlen(trim($word))>3 and $i<=8){
                $word = ($i>1?",".$word:$word);
                $keys.=$word;
                $i++;

            }
        }

        return $keys;
    }

    public static function substrText($text,$length)
    {
      $last_word = strlen($text)>$length?"<span style='color: #24A2F1;font-weight: bold'> ...</span>":'';
      return   mb_substr($text, 0,$length, 'UTF-8').$last_word;
    }
}
