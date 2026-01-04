<?php

use yii\helpers\Html;
use yii\grid\GridView;
echo Yii::$app->basePath;
/* @var $this yii\web\View */
/* @var $searchModel common\models\GiftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Gifts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gift-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Gift'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
       $giftCategories = \common\models\GiftCategory::getGiftCategories();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
             [
                'attribute' => 'category_id',
                'format' => 'raw',
                'filter' => \common\models\GiftCategory::getGiftCategories(),
                'value' => function($data){
                    return \common\models\GiftCategory::getGiftCategories()[$data->category_id];
                }
            ],
            'name',
             [
                'attribute' => 'icon',
                'format' => 'image',
                'filter' => false
            ],

            'coin',
            [
                'attribute' => 'status',
                'filter' => \common\models\GiftCategory::getStatusArray(),
                'format' => 'raw',
                'value' => function($data){
                    return \common\models\GiftCategory::getStatusArray()[$data->status];
                }
            ],
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
