<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use frontend\assets\ProfileAsset;

$this->title = Yii::t('app', 'Complete Profile');

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

        <?php $form = ActiveForm::begin(['id' => 'form-settings','options' => ['enctype' => 'multipart/form-data']]); ?>

        <hr/>
        <div class="form-group">

            <?= $form->field($citySelectForm, 'countryId')->dropDownList($countries,
                ['onchange' => 'getCities(this,0);']) ?>

            <?= $form->field($citySelectForm, 'cityId')->dropDownList($cities,
                ['class' => 'dynamic-city-input form-control']) ?>

            <?= $form->field($imageUploadForm,'image')->fileInput();

            ?>


            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>



        </div>


        <?php ActiveForm::end(); ?>

    </div>




</div>





