<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GiftCategory */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Gift Category',
]) . ' ' . $model->name_az;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gift Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_az, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="gift-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
