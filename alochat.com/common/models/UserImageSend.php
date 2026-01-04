<?php

namespace common\models;

use Yii;
use yii\helpers\BaseFileHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "user_image".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $path
 * @property string $path_original
 * @property integer $add_date
 */
class UserImageSend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_image_send';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['path','path_original'], 'string', 'max' => 255],
            ['add_date', 'default', 'value' => time()],
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
            'path' => Yii::t('app', 'Path'),
            'path_original' => Yii::t('app', 'Original Path'),

        ];
    }



    public function getUser()
    {

        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function resizeImageAndSave($source, $destination = null, $wdt, $height = null)
    {
        if (empty($height)) {
            // Height is nit set so we are keeping the same aspect ratio.
            list($width, $height) = getimagesize($source);
            //if($width > $height){
            $w = $wdt;
            $h = ($height / $width) * $w;
            $w = $w;
            /*}else{
            $w = $wdt;
            $h = $w;
            $w = ($width / $height) * $w;
            }*/
        } else {
            // Both width and Height are set.
            // this will reshape to the new sizes.
            $w = $wdt;
            $h = $height;
        }
        $source_image = @file_get_contents($source) or die('Could not open' . $source);
        $source_image = @imagecreatefromstring($source_image) or die($source .
            ' is not a valid image');
        $sw = imagesx($source_image);
        $sh = imagesy($source_image);
        $ar = $sw / $sh;
        $tar = $w / $h;
        if ($ar >= $tar) {
            $x1 = round(($sw - ($sw * ($tar / $ar))) / 2);
            $x2 = round($sw * ($tar / $ar));
            $y1 = 0;
            $y2 = $sh;
        } else {
            $x1 = 0;
            $y1 = 0;
            $x2 = $sw;
            $y2 = round($sw / $tar);
        }
        $slate = @imagecreatetruecolor($w, $h) or die('Invalid thumbnail dimmensions');
        imagecopyresampled($slate, $source_image, 0, 0, $x1, $y1, $w, $h, $x2, $y2);
        // If $destination is not set this will output the raw image to the browser and not save the file
        if (!$destination)
            header('Content-type: image/jpeg');
        @imagejpeg($slate, $destination, 90) or die('Directory permission problem');
        ImageDestroy($slate);
        ImageDestroy($source_image);
        if (!$destination)
            exit;
        return true;
    }

    public static function deleteImageById($id)
    {

        $imageThumb = UserImageThumb::findOne($id);
        $imageResized = UserImageResized::findOne($id);
        $image = UserImage::findOne($id);

        $user = User::findOne(Yii::$app->user->id);

        $webroot = Yii::getAlias('@webroot');

        if ($user->profile_photo_id == $id) {

            $user->profile_photo_id = null;
            $user->profile_photo = null;

            $user->save(false);
        }

        $imagePath = $webroot . $image->path;

        $thumbPath = $webroot . $imageThumb->path;

        $resizedPath = $webroot . $imageResized->path;

        $image->delete();

        $imageThumb->delete();

        $imageResized->delete();

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        if (file_exists($thumbPath)) {
            unlink($thumbPath);
        }

        if (file_exists($resizedPath)) {
            unlink($resizedPath);
        }


    }
    public static function compressImage($source, $destination, $quality) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);


       list($width, $height) = getimagesize($source);

       if($width>1000){
           UserImage::resizeImageAndSave($source, $destination, 724, 482);
       }

        return $destination;
    }

    public static function getImagesCount()
    {
        $count = UserImage::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->count('id');
        return $count;
    }


}
