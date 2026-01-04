<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GiftCategory */

$this->title = Yii::t('app', 'Create Gift Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gift Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gift-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
