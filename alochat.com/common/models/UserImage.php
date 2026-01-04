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
 * @property integer $read_count
 * @property integer $comment_count
 * @property integer $like_count
 * @property integer $add_date
 */
class UserImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['comment_count'], 'integer'],
            [['like_count'], 'integer'],
            [['read_count'], 'integer'],
            [['path'], 'string', 'max' => 255],
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

        ];
    }


    public static function getNextButton($id, $user_id)
    {
        $iNext = UserImage::find()
            ->where(['<', 'id', $id])
            ->andWhere(['user_id' => $user_id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->asArray()
            ->all();

        if (!empty($iNext))
            $iNext = $iNext[0]['id'];
        else
            $iNext = false;


        $sNextBtn = ($iNext) ? '<div class="preview_next" onclick="getPhotoPreviewAjx(\'' . $iNext . '\',\'' . $user_id . '\')">
        <i class="glyphicon glyphicon-chevron-right"></i></div>' : '';

        return $sNextBtn;
    }

    public static function getPreviousButton($id, $user_id)
    {

        $iPrev = UserImage::find()
            ->where(['>', 'id', $id])
            ->andWhere(['user_id' => $user_id])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->asArray()
            ->all();

        if (!empty($iPrev))
            $iPrev = $iPrev[0]['id'];
        else
            $iPrev = false;

        $sPrevBtn = ($iPrev) ?
            '<div class="preview_prev" onclick="getPhotoPreviewAjx(\'' . $iPrev . '\',\'' . $user_id . '\')">
            <i class="glyphicon glyphicon-chevron-left"></i></div>' : '';

        return $sPrevBtn;
    }

    public function getUser()
    {

        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function resizeImageAndSave2($source, $destination = null, $wdt, $height = null)
    {
        $maxWidth = $wdt; $maxHeight = $height;
        list($width, $height) = getimagesize($source);
        if($width>$height){
            $d = $width/$maxWidth;
        }else{
            $d = $height/$maxHeight;
        }


        $h = $height/$d;
        $w = $width/$d;

        if($w>$maxWidth){
            $wdt=$maxWidth;
        }else{
            $wdt= $w;
        }

        if($h>$maxHeight){
            $d = $height/$maxHeight;
            $wdt = $width/$d;
        }


        UserImage::resizeImageAndSave($source, $destination, $wdt, null);
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

    public static function saveFacebookImage($facebookId,$userId)
    {
        $user = User::findOne($userId);

        $userImage = new UserImage();
        $userImage->user_id = $userId;
        $path = 'images/user/'.$userId.'/';
        $filePath = 'images/user/'.$userId.'/'.$userId."_0.jpg";
        BaseFileHelper::createDirectory($path,0777,false);
        $userImage->path = '/images/user/'.$userId.'/'.$userId."_0.jpg";
        $userImage->add_date = time();
        $file = file_get_contents('https://graph.facebook.com/'.$facebookId.'/picture?width=590&height=600');
       // var_dump($file); exit;
         file_put_contents($filePath, $file);
        $userImage->save(false);

        $userImageResized = new UserImageResized();
        $userImageResized->user_id = $userId;
        $pathResized = 'images/user/' . $userId . '/resized/';
        BaseFileHelper::createDirectory($pathResized, 0777, false);
        $filePathResized = 'images/user/' . $userId . "/resized/" . $userId . "_0.jpg";
        $userImageResized->path = '/images/user/' . $userId . "/resized/" . $userId . "_0.jpg";
        $userImageResized->add_date = time();
        $file = file_get_contents('https://graph.facebook.com/' . $facebookId . '/picture?width=320&height=240');
        file_put_contents($filePathResized, $file);
        $userImageResized->save(false);

        $userImageThumb = new UserImageThumb();
        $userImageThumb->user_id = $userId;
        $pathThumb = 'images/user/' . $userId . '/thumbs/';
        BaseFileHelper::createDirectory($pathThumb, 0777, false);
        $filePathThumb = 'images/user/' . $userId . "/thumbs/" . $userId . "_0.jpg";
        $userImageThumb->path = '/images/user/' . $userId . "/thumbs/" . $userId . "_0.jpg";
        $userImageThumb->add_date = time();
        $file = file_get_contents('https://graph.facebook.com/' . $facebookId . '/picture?width=120&height=120');
        file_put_contents($filePathThumb, $file);
        $userImageThumb->save(false);

        $user->profile_photo = $userImageThumb->path;
        $user->profile_photo_id = $userImage->id;

        $user->save(false);


    }


    public static function liked($user_id, $image_id)
    {
        $db = Yii::$app->db;
        $count = $db->createCommand('SELECT count(`id`) FROM image_like WHERE image_id="'.$image_id.'" and user_id="'.$user_id.'"')->queryScalar();
        if($count>0){
            return true;
        }else {
            return false;
        }
    }
}
