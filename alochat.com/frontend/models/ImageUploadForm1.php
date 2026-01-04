<?php
namespace frontend\models;

use common\models\User;
use common\models\UserImage;
use common\models\UserImageResized;
use common\models\UserImageThumb;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ImageUploadForm extends Model
{
    public $image;

    const MAX_FILE_SIZE = 5120000;
    const MAX_UPLOADED_FILE_COUNT = 10;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['image', 'safe'],
        ];
    }


    public function attributeLabels()
    {

        return [

            'image' => Yii::t('app', 'Photo')
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
                    'One or more of your images is an invalid size. Please only select images less than 2MB in size.'));
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



    public function saveImages($images)
    {
        $image = $images[0];

        $webRoot = Yii::getAlias('@webroot');

        $userId = Yii::$app->user->id;

        $thumbsDir = $webRoot . '/images/user/' . $userId . '/thumbs/';

        $resizedImagesDir = $webRoot . '/images/user/' . $userId . '/resized/';

        $imagesDir = $webRoot . '/images/user/' . $userId;

        $userImageCount = UserImage::find()->where(['user_id' => $userId])->count();


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

        $thumbsDir = $thumbsDir . '/';

        $maxId = UserImage::find()->select('id')
            ->where(
                [
                    'user_id' => $userId,

                ])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();

        if ($maxId)
            $maxId = $maxId->id;
        else
            $maxId = 0;


        $imageIndex = $maxId;

        $image_d = $imagesDir . $userId . '_' . $imageIndex . ".jpg";

        $image_t = $thumbsDir . $userId . '_' . $imageIndex . ".jpg";

        $image_r = $resizedImagesDir . $userId . '_' . $imageIndex . ".jpg";


        $saved = $image->saveAs($image_d);

        $imgObj = new UserImage();

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
        }


    }
}
