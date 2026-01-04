<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ShareSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Shares');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="share-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Share'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
             [
                'attribute' =>  'user_id',
                'label' => 'SenderName',
                'value' => function($data){  $user = \common\models\User::find()->where(['id' => $data->user_id])->one(); return $data->user_id.' - '.$user["nickname"]; },
                'options'=>['width'=>'150px']
            ],
            'text:ntext',
            'like_count',
             'read_count',
             'comment_count',
            // 'permission',
            // 'country',
             'time:datetime',
            // 'status',
            [
                'attribute' => 'attach',
                'format' => ['image',['width' => 120, 'height' => 120]],
                'filter' => false,
                'value' => function($data){$path = "/images/share/resized/".date("Ym",$data["time"]).'/';  return $path.$data->attach; }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
