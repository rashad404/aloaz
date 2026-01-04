<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
PhotoUploadAsset::register($this);

$this->title = $user->nickname." ". Yii::t('app','Timeline');
?>

<div class="row">


    <div class="col-md-12" style="background-color: #f5f5f5">
        <div class="row profile-title-block" id="user-filter1">
            <div class="col-md-12">
                <ul class="nav nav-tabs profile-nav-tabs">
                    <li role="presentation" class="active"><a href="<?php echo Url::to(['/profile/timeline/'.$user["id"]])?>">Timeline</a></li>
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/photos/'.$user["id"]])?>"><?= Yii::t('app','Photos')?></a></li>
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/friends/'.$user["id"]])?>"><?= Yii::t('app','Friends')?></a></li>
                    <li role="presentation"><a href="<?php echo  Url::to(['/profile/gifts/'.$user["id"]])?>"><?= Yii::t('app','Gifts')?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="center-block col-md-12" style="background-color: #f5f5f5;border: 0px !important;">
        <div class="row">
            <!--------------->
            <?= $this->render('/site/partials/share_gallery.php', ['shares' => $shares]); ?>
            <!--------------->
        </div>

        <?php
        if ($pages) {
            // display pagination
            echo '<div class="text-center">';

            echo \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
            ]);
            echo '</div>';
        }
        ?>
    </div>

</div>