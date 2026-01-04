<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use frontend\assets\ProfileAsset;

$this->title = Yii::t('app', 'Profile Settings');

//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['/profile/index', 'id' => Yii::$app->user->id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
ProfileAsset::register($this);
?>

<div class="profile-page-container settings-page-container">
    <?= $this->render('partials/city_change_modal', ['citySelectForm' => $citySelectForm, 'countries' => $countries, 'cities' => $cities]); ?>
    <div class="settings-header">
        <h4><?= $this->title ?></h4>
    </div>
    <div class="settings-content">

        <?php $form = ActiveForm::begin(['id' => 'form-settings']); ?>
        <?= $form->field($formUser, 'full_name') ?>

        <hr/>

        <?= $form->field($formUser, 'sex')->dropDownList([
            User::SEX_MAN => Yii::t('app', 'Man'),
            User::SEX_WOMAN => Yii::t('app', 'Woman')
        ],
            ['prompt' => '---']
        ) ?>

        <hr/>
        <?= $form->field($formUser, 'age')->dropDownList(
            User::getAgeArray(),
            ['prompt' => '---']
        ) ?>

        <hr/>
        <?= $form->field($formUser, 'only_friend')->dropDownList([
            0 => Yii::t('app', 'Everyone'),
            1 => Yii::t('app', 'Only Friends')
        ]
         )->label(Yii::t('app','Who can write?')) ?>

        <hr/>
        <div class="form-group">

            <label class="control-label" for="city-select-modal"><?= Yii::t('app', 'City') ?>:</label>

            <!-- Button trigger modal -->
            <button name="city-select-modal" type="button" class="city-select-btn btn btn-primary btn-sm "
                    data-toggle="modal" data-target="#citySelectModal">
                <?= $userData->city_id ? $userData->city->name : '-- ' . Yii::t('app', 'Select city') . ' --' ?>
            </button>

        </div>
        <hr/>
        <?php echo $form->field($formUser, 'about')->textarea(); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>


        <?php if ($userData->social_login == 0): ?>

            <?= $this->render('partials/password_change_block', ['passwordChangeForm' => $passwordChangeForm]); ?>

        <?php endif ?>

</div>





