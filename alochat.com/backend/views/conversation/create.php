<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConversationReply */

$this->title = Yii::t('app', 'Create Conversation Reply');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conversation Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-reply-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
