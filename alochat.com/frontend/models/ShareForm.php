<?php

namespace frontend\models;

use common\models\Share;
use common\models\User;
use common\models\UserImage;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * ContactForm is the model behind the contact form.
 */
class ShareForm extends ActiveRecord
{
    public $text;
    public $attach;
    public $user_id;
    public $permission;

    const MAX_FILE_SIZE = 5120000;
    const MAX_UPLOADED_FILE_COUNT = 10;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['text', 'safe'],
            ['user_id', 'safe'],
            ['attach', 'safe'],
            ['permission', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "text" => Yii::t('app','Text'),
            'attach' => Yii::t('app', 'File')

        ];
    }

    public function validateImages($images)
    {

        $validMimeTypes = [
            "image/gif",
            "image/png",
            "image/jpeg",
            "image/pjpeg",
        ];

        $minWidth = 220;
        $minHeight = 220;

        $error = false;

        if (count($images) > self::MAX_UPLOADED_FILE_COUNT) {

            $this->addError('image',
                Yii::t('app', 'You can upload maximum ' . self::MAX_UPLOADED_FILE_COUNT . ' image for per upload.'));

            $error = true;
        }


        foreach ($images as $image) {

            if (!in_array($image->type, $validMimeTypes)) {

                $this->addError('image', Yii::t('app',
                    'One or more of your images has an invalid image type. Please only select jpg, png or gif images.'));
                $error = true;
            }

            if ($image->size > self::MAX_FILE_SIZE) {

                $this->addError('image', Yii::t('app',
                    'One or more of your images is an invalid size. Please only select images less than 5MB in size.'));
                $error = true;
            }

            $image_dimensions = getimagesize($image->tempName); // returns an array of image info [0] = width, [1] = height

            $image_width = $image_dimensions[0]; // Image width
            $image_height = $image_dimensions[1]; // Image height

            if (($image_width < $minWidth) || ($image_height < $minHeight)) {

                $this->addError('image', Yii::t('app',
                    'One or more of your images has invalid dimensions. Images must be at least 220px by 220px.'));
                $error = true;
            }
            if ($error) {

                break;
            }
        }

        if (!$error)
            return true;
        else
            return false;

    }


    public function saveImages($image)
    {
         $webRoot = Yii::getAlias('@webroot');

        $userId = $this->user_id;

        $imagesDir = $webRoot . '/images/share';

        if (!is_dir($imagesDir)) {

            mkdir($imagesDir, 0777, true);
        }


        $imagesDir = $imagesDir . '/';

        $maxId = 1992;

        $imageIndex = $maxId;

        $image_d = $imagesDir . $userId . '_' . $imageIndex . ".jpg";


        $saved = $image->saveAs($image_d);

        /*$imgObj = new UserImage();

        $imgObj->user_id = $userId;

        $imgObj->path = '/images/user/' . $userId . '/' . $userId . '_' . $imageIndex . ".jpg";

        $imgObj->save();


        if ($saved && $imgObj->primaryKey) {


            if (UserImage::resizeImageAndSave($image_d, $image_t, 120, 120)) {
                $thumb = new UserImageThumb();
                $thumb->user_id = $userId;
                $thumb->path = '/images/user/' . $userId . '/thumbs/' . $userId . '_' . $imageIndex . ".jpg";
                $thumb->add_date = time();
                $thumb->id = $imgObj->primaryKey;
                $thumb->save();
            }

            if ($userImageCount == 0 && $thumb->id) {

                $user = User::findOne($userId);

                $user->profile_photo = $thumb->path;
                $user->profile_photo_id = $thumb->id;
                $user->changed_photo = 1;
                $user->save(false);
            }


            if (UserImage::resizeImageAndSave($image_d, $image_r, 320, 240) && $thumb->id) {
                $resized = new UserImageResized();
                $resized->user_id = $userId;
                $resized->path = '/images/user/' . $userId . '/resized/' . $userId . '_' . $imageIndex . ".jpg";
                $resized->add_date = time();
                $resized->id = $imgObj->primaryKey;
                $resized->save();
            }

            UserImage::compressImage($image_d,$image_d,75);
        }*/


    }



    public function sendShare()
    {
        $share = new Share();

        $share->text = User::filterword(User::func_strip_tags($this->text));
        $share->user_id = $this->user_id;
        $share->permission = $this->permission;
        $share->like_count = 0;
        $share->read_count = 0;
        $share->comment_count = 0;
        $share->time = time();
        if(Yii::$app->user->identity->verify == 1){
            $share->status = 1;
        }else {
            $share->status = 0;
            Yii::$app->session->setFlash('warning','Nömrənizi təsdiqləmədiyinizə görə paylaşımınız yalnız öz profilinizdə görünəcək. Saytdan tam yararlanmaq, eləcə də paylaşdıqlarınızın "Paylaşımlar"-da görünməsi üçün nömrənizi <a href="'.Url::to(["/profile/verify"]).'">buradan</a> təsdiqləyin');
        }


        if($this->attach){
            $image = $this->attach;

            $webRoot = Yii::getAlias('@webroot');

            $userId = $this->user_id;
            $date_folder = date('Ym');
            $imagesDir = $webRoot . '/images/share/uploads/'.$date_folder;
            $thumbsDir = $webRoot . '/images/share/thumbs/'.$date_folder.'/';
            $resizedImagesDir = $webRoot . '/images/share/resized/'.$date_folder.'/';


            if (!is_dir($imagesDir)) {

                mkdir($imagesDir, 0777, true);
            }

            if (!is_dir($thumbsDir)) {

                mkdir($thumbsDir, 0777, true);
            }

            if (!is_dir($resizedImagesDir)) {

                mkdir($resizedImagesDir, 0777, true);
            }


            $imagesDir = $imagesDir . '/';

            $maxId = time();

            $imageIndex = $maxId;

            $image_d = $imagesDir . $userId . '_' . $imageIndex . ".jpg";
            $image_t = $thumbsDir . $userId . '_' . $imageIndex . ".jpg";
            $image_r = $resizedImagesDir . $userId . '_' . $imageIndex . ".jpg";


            $saved = $image->saveAs($image_d);
            if($saved){
                UserImage::resizeImageAndSave2($image_d, $image_t, 600, 400);
                UserImage::resizeImageAndSave2($image_d, $image_r, 220,250);
                $share->attach = $userId . '_' . $imageIndex . ".jpg";
            }
        }

        if((strlen(trim($share->text))>=3 or $share->attach!="") and  $share->save(false)){
            return true;
        } else {

            return false;
        }
    }
}
