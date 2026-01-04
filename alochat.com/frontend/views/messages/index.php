<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Messages');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/messages.js');

?>

<div class="profile-page-container profile-page-main">
    <?php
    /*        if($_SERVER["REMOTE_ADDR"]=='37.32.67.22'){
                echo "Opened: ".$vaxt;
            }
        */?>

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">   <?= $this->title ?> </div>
            <div class="pull-right" style="margin-top: -4px;">
                <select class="form-control" onchange="location = this.options[this.selectedIndex].value;" style="height: auto !important;padding: 3px;width: 148px;">
                    <option value="<?= Url::to(["/messages/"])?>" <?php if($p==0) echo 'selected';else echo ''; ?>><?= Yii::t('app','Bütün mesajlar')?></option>
                    <option value="<?= Url::to(["/messages/?p=1"])?>" <?php if($p==1) echo 'selected';else echo ''; ?>><?= Yii::t('app','Oxunmamış mesajlar')?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="messages row">
        <div class="messages-area">

            <?php foreach ($conversations as $conversation): ?>
                <?php
                if($conversation["user_one"] == Yii::$app->user->id){
                    $newMessageCount = $conversation["not_read_one"];
                }else {
                    $newMessageCount = $conversation["not_read_two"];
                }
                ?>
                <div class="fast message
                     <?= $conversation['userOnline'] ? 'online' : '' ?>
                     <?= $conversation['read'] != 1 ? 'unread' : '' ?>"
                     data-id="<?= $conversation['conversation_id'] ?>"
                     data-counter="<?php echo $newMessageCount ?>"
                     id="<?= $conversation['conversation_id'] ?>"

                     data-href="<?= Url::to(['messages/view/', 'id' => $conversation['conversation_id']]) ?>"
                    >

                    <div class="fast" >
                        <?php
                        $display_con = 'none';
                        if($newMessageCount > 0){
                            $display_con = 'block';
                        }
                        ?>
                        <span data-val="<?= $newMessageCount?>" class="new-conversation-message-count" style="display: <?= $display_con?>" id="new-message-count-<?= $conversation['conversation_id']?>">+<?= $newMessageCount?></span>

                        <img src="<?= Url::base() . $conversation['profile_photo'] ?>" class="avatar" title="Mary"
                             border="0">
                    </div>

                    <div class="message-author  <?= $conversation['userOnline'] ? 'online' : '' ?>">
                        <span class="name">

                          <a class="fast"
                             href="<?= Url::to(['messages/view/', 'id' => $conversation['conversation_id']]) ?>#chat"><?= Html::encode($conversation['nickname']) ?></a>

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
        <div class="text-center">
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