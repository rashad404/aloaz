<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Compotetion */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Compotetions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compotetion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'start_date',
            'end_date',
            'status',
        ],
    ]) ?>

</div>
<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompotetionImagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Konkursun şəkilləri');
?>
<div class="compotetion-images-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'user_id',
            [
                'attribute' => 'user_id',
                'filter' => false,
                'header' => 'Nickname',
                'value' => function($data){
                    $user = Yii::$app->db->createCommand('SELECT nickname FROM `user` WHERE `id`=:id')->bindValue(':id',$data["user_id"])->queryOne();
                    return $user["nickname"];
                }

            ],
            'user_image_id',
            [
                'attribute' => 'user_image_id',
                'filter' => false,
                'header' => 'Şəkil',
                'format' => ['image',['width'=>'100','height'=>'100']],
                'value' => function($data){
                    $user_image = Yii::$app->db->createCommand('SELECT path FROM `user_image` WHERE `id`=:id')->bindValue(':id',$data["user_image_id"])->queryOne();
                    return $user_image["path"];
                }

            ],
             'like_count',
            // 'status',
            // 'image_time:datetime',

            [
                'header' => 'Bax',
                'format' => 'raw',
                'value' => function($data){
                    $user_image = Yii::$app->db->createCommand('SELECT path FROM `user_image` WHERE `id`=:id')->bindValue(':id',$data["user_image_id"])->queryOne();
                    return '<a class="btn btn-primary btn-sm" href="'.$user_image["path"].'" target="_blank" title="Bax" aria-label="Bax" data-pjax="0"><span class="glyphicon glyphicon-picture white"></span></a>';
                }
            ],
            [
                'header' => 'Sil',
                'format' => 'raw',
                'value' => function($data){
                    $url = \yii\helpers\Url::to(["compotetion/delete-image","id" => $data->id]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ]);
                    //return '<a class="btn btn-danger btn-sm" href="'.\yii\helpers\Url::to(["compotetion/delete-image","id" => $data->id]).'" target="_blank" title="Qəbul etmə" aria-label="Bax" data-pjax="0"><span class="glyphicon glyphicon-remove white"></span></a>';
                }
            ],

        ],
    ]); ?>

</div>


