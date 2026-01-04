<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ShareSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="share-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create news'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
             [
                'attribute' =>  'author',
                'label' => 'Author',
                'value' => function($data) {
                    if ($data->author == 0) {
                        return "AloChat";
                    } else {
                        $user = \common\models\User::find()->where(['id' => $data->author])->one();
                        return $data->author . ' - ' . $user["nickname"];
                    }
                },



            ],
            'title',
            'time:datetime',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
