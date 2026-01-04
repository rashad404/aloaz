<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Compotetion */

$this->title = Yii::t('app', 'Create Compotetion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Compotetions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compotetion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
