<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Gift */

$this->title = Yii::t('app', 'Create Gift');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gift-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
