<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23.04.2015
 * Time: 10:01
 */
?>

<div class="comments" id="comments">

    <div class="owner">

        <img height="50" width="50" class="pull-left img-rounded"
             src="<?= $user->profile_photo
                 ? Url::base() . $user->profile_photo
                 : Url::base() . Yii::$app->params['defaultProfilePicture_'.$user->sex] ?>"
            >

        <div class="t pull-left">
            <span class="profile-name"><?= Html::encode($user->full_name) ?></span>
            <span class="label online"></span>

            <div class="clearfix"></div>

            <span class="t-profile-meta"> <?= isset($user->age) ? $user->age : '' ?> <?= Yii::t('app', 'years') ?>,&nbsp;<?= $user->city ? $user->city->name : '' ?></span>

            <?php if ($user->isOnline()): ?>
                <div class="status"></div>
            <?php endif ?>
        </div>

    </div>

    <div class="clearfix"></div>

    <?php if ($isOwnProfile): ?>

        <div class="pull-left">
            <a class="link1" onclick="goToLink(this);"
               href="<?= Url::to(['/profile/set-profile-picture/', 'id' => $photoId]) ?>"><span
                    class="glyphicon glyphicon glyphicon-user"></span><?= Yii::t('app', 'Set as profile picture') ?></a>
        </div>

        <div class="clearfix"></div>


        <div class="pull-left">
            <a onclick="goToLink(this);" href="<?= Url::to(['/profile/delete-image/', 'id' => $photoId]) ?>"><span
                    class="glyphicon glyphicon glyphicon-trash"></span><?= Yii::t('app', 'Delete') ?></a>
        </div
        <div class="clearfix"></div>


    <?php endif ?>

    <?php if (!$isOwnProfile): ?>

        <div id="comments_warning2" style="display:none">
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'form-comment',
            // 'onsubmit' =>"return false;"
        ]); ?>

        <?= $form->field($commentForm, 'photo_id')->hiddenInput()->label(false) ?>

        <?= $form->field($commentForm, 'text')->textInput()->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Comment'),
                [
                    'class' => 'btn btn-primary pull-right',
                    'name' => 'signup-button',
                    'onclick' => 'submitComment(' . $photoId . '); return false;',

                ]) ?>
        </div>

        <?php ActiveForm::end() ?>

    <?php endif ?>
</div>