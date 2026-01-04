<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ConversationReplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Conversation Replies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-reply-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?/*= Html::a(Yii::t('app', 'Create Conversation Reply'), ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => 'ID',
                'options'=>['width'=>'30px']
            ],
            [
                'attribute' =>  'reply',
                'format' => 'ntext',
                'label' => 'Message',
            ],
            [
                'attribute' =>  'user_id',
                'label' => 'SenderName',
                'value' => function($data){  $user = \common\models\User::find()->where(['id' => $data->user_id])->one(); return $data->user_id.' - '.$user["full_name"]; },
                'options'=>['width'=>'150px']
            ],
            [
                'attribute' => 'send_photo_id',
                'label' => 'Photo',
                'format' => 'image',
                'value' => function($data){ $send_photo_id= $data->send_photo_id; if($send_photo_id > 0){
                    $sendPhoto = \common\models\UserImageSend::find()->where(['id' => $send_photo_id])->one();
                    if($sendPhoto) {
                        return $sendPhoto->path;
                    }
                }}
            ],
            [
                'label' => 'ReceiverName',
                'value' => function($data){
                    $conversation = \common\models\Conversation::find()->where(['id' => $data->conversation_id])->one();
                    if($conversation->user_one == $data->user_id){
                        $userId2 =  $conversation->user_two;
                    } else{
                        $userId2 = $conversation->user_one;
                    }
                    $user = \common\models\User::find()->where([ 'id' => $userId2])->one(); return $user['id'].' - '.$user["full_name"]; },
                'options'=>['width'=>'150px']

            ],
            [
                'attribute' => 'conversation_id',
                'label' => 'Conversation ID',
                'options'=>['width'=>'30px']
            ],
            // 'read',
            // 'deleted_by',
            [
                'attribute' => 'time',
                'label' => 'Time',
                'format' => 'datetime',
                'options' => ['width'=>'160px']
            ],
           //
           //  'time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
