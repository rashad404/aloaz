<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Messages');
?>

<div class="messages-container">
    <h3><?= $this->title ?></h3>

    <div class="messages">
        <div class="messages-area">

            <?php foreach ($conversations as $conversation): ?>

                <div class="fast message
                     <?= $conversation['userOnline'] ? 'online' : '' ?>
                     <?= $conversation['read'] != 1 ? 'unread' : '' ?>"
                     data-id="<?= $conversation['conversation_id'] ?>"
                     id="<?= $conversation['conversation_id'] ?>"

                     data-href="<?= Url::to(['messages/view/', 'id' => $conversation['conversation_id']]) ?>"
                    >

                    <div class="fast" >

                        <img src="<?= Url::base() . $conversation['profile_photo'] ?>" class="avatar" title="Mary"
                             border="0">
                    </div>

                    <div class="message-author  <?= $conversation['userOnline'] ? 'online' : '' ?>">
                        <span class="name">

                          <a class="fast"
                             href="<?= Url::to(['messages/view/', 'id' => $conversation['conversation_id']]) ?>#chat"><?= Html::encode($conversation['full_name']) ?></a>

                        </span>

                        <span class="status"></span>

                    </div>
                    <!-- / message-author -->
                    <div class="message-body" data-type="1">
                        <?= \yii\helpers\HtmlPurifier::process($conversation['reply']) ?>
                    </div>

                    <span style="color: #787878;font-size: 12px;">

                        <?php
                        $today = date("d");

                        $day = date("d", $conversation['last_time']);

                        if ($day == $today) {
                            $conversation['last_time'] = Yii::t('app', 'today') . ' ' . date('H:i', $conversation['last_time']);
                        } elseif ($day == ($today - 1)) {

                            $conversation['last_time'] = Yii::t('app', 'yesterday') . ' ' . date('H:i', $conversation['last_time']);
                        } else
                            $conversation['last_time'] = date('d.M.Y H:i', $conversation['last_time']);

                        echo $conversation['last_time'];
                        ?>
                    </span>

                    <a class="btn btn-xs reply" role="button"
                       href="<?= Url::to(['messages/view/', 'id' => $conversation['conversation_id']]) ?>"><?= Yii::t('app', 'Reply') ?></a>

                    <?= \yii\helpers\Html::a('', ['messages/delete-conversation/', 'id' => $conversation['conversation_id']], [
                        'class' => 'close del delete-thread',
                        'name'=>'del-link',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure delete this conversation ?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
                <!-- / message -->
            <?php endforeach ?>


        </div>
        <!-- / messages-area -->
    </div>


    <div class="row">
        <div class="container">
            <?php
            if ($pages) {
                // display pagination
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                ]);
            }
            ?>
        </div>
    </div>

</div>

<div style="display: none;"><?= "Vaxt: ".$vaxt?></div>