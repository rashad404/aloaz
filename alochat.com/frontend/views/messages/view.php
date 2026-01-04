<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use frontend\assets\InboxAsset;
use yii\widgets\ActiveForm;
use frontend\assets\PingAsset;


use frontend\assets\PhotoSendAsset;
PhotoSendAsset::register($this);



$this->registerJsFile(Yii::$app->request->baseUrl . '/js/fileinput_send/fileinput_send_locale_' . Yii::$app->language . '.js', ['depends' => [PhotoSendAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/image_upload_init.js', ['depends' => [PhotoSendAsset::className()]]);
$this->title = \yii\helpers\Html::encode($currentConversation['full_name']);


$this->getAssetManager()->getBundle(PingAsset::className(), false)->js = [];
InboxAsset::register($this);

?>

<script>
    var labelMe = '<?=Yii::t('app','Me')?>';
    var globalCid = '<?=$currentConversation['conversation_id']?>';
    var globalConversationUser = '<?= \yii\helpers\Html::encode($currentConversation['full_name'])?>';
</script>
<div class="profile-page-container chat-container">
    <div class="chat-page">
        <div class="dating-split">
            <div  id="chat" class="chat">
                <div class="left-col hidden-sm hidden-xs">
                    <div class="header-cont"><?= Yii::t('app', 'Contacts') ?></div>
                    <div class="contacts-list" id="contactListWrapper">

                        <?php foreach ($conversations as $conversation): ?>

                            <div
                                id="conversation_<?= $conversation['conversation_id'] ?>"
                                data-counter="<?= $conversation['new_message_count'] ?>"
                                class="contact fast
                            <?= $conversation['userOnline'] ? ' online ' : '' ?>
                             <?= $conversation['conversation_id'] == $id ? ' active ' : '' ?>
                            <?= $conversation['read'] != 1 ? ' is_new ' : '' ?> "
                                data-id="<?= $conversation['conversation_id'] ?>"
                                >

                                <?= \yii\helpers\Html::a('', ['messages/delete-conversation/', 'id' => $conversation['conversation_id']], [
                                    'class' => 'close',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure delete this conversation ?'),
                                        'method' => 'post',
                                    ],
                                ]) ?>

                                <a href="<?= Url::to(['/messages/view', 'id' => $conversation['conversation_id']]) ?>">

                                    <div class="author">

                                        <img alt="images" class="avatar"
                                             src="<?= Url::base() . $conversation['profile_photo'] ?>"
                                             title="<?= $conversation['full_name'] ?>">

                                        <div class="name"><?= $conversation['full_name'] ?><span class="status"></span>

                                        </div>

                                    </div>
                                    <!-- / author -->
                                </a>

                            </div><!-- / contact -->

                        <?php endforeach ?>

                    </div>
                </div>
                <!-- / left-col -->

                <div class="rigt-col ">
                    <div class="rigt-col-hold">
                        <div class="contact-details user-info <?= $currentConversation['userOnline'] ? 'online' : '' ?>">
                            <div class="col-md-8 col-lg-8 col-sm-8 col-xs-8">
                                <div class="avatar-big">
                                    <a href="<?= Url::to(['/profile/index', 'id' => $currentConversation['user_id']]) ?>">
                                        <img src="<?= Url::base() . $currentConversation['profile_photo'] ?>">
                                    </a>
                                </div>

                                <div class="details">
                                    <a href="<?= $currentConversation['user_id'] != Yii::$app->params['adminUserId']?Url::to(['/profile/index', 'id' => $currentConversation['user_id']]):'' ?>">
                                        <div class="name">
                                            <?= $currentConversation['full_name'] ?>
                                            <span class="status"></span>
                                        </div>
                                    </a>

                                    <div class="meta">
                                        <?php
                                            if($currentConversation['user_id'] != Yii::$app->params['adminUserId']){
                                                echo $currentConversation['age'].' '.Yii::t('app', 'years').',';
                                            }
                                        ?>
                                         <?= $currentConversation['city'] ?></div>
                                </div>
                            </div>

                            <div class="col-md-4 col-md-4 col-xs-4 col-sm-4">
                                <div class="details pull-right">
                                    <button id="block-user" style="border-radius: 2px;padding: 5px;" onclick="blockUser1(<?= $currentConversation['user_id'] ?>,'<?= \common\models\User::userBlockedId($currentConversation['user_id']) ? Yii::t('app','Are you sure you want to cancel block this user?') : Yii::t('app','Are you sure you want to block this user?'); ?>');" type="button"
                                            class="btn btn-primary btn-sm">
                                        <i class="glyphicon glyphicon-ban-circle
                        <?= \common\models\User::userBlockedId($currentConversation['user_id']) ? 'text-danger' : '' ?>

                        "></i> <?= Yii::t('app', 'Add block') ?>
                                    </button>

                                </div>
                            </div>

                        </div>

                        <div class="chat-area">

                            <div id="activity" class="activity">

                                <?php
                                $messages = krsort($currentConversation['messages']);
                                foreach ($currentConversation['messages'] as $message): ?>
                                    <div class="chat-item">

                                        <div class="head">

                                            <div class="name"><?= $message['full_name'] ?></div>
                                            <div class="when"><?= $message['time'] ?></div>

                                        </div>

                                        <div class="message">
                                           <div class="text">
                                                <?= \yii\helpers\HtmlPurifier::process($message['reply']) ?>
                                                <!-- / preview -->
                                           </div>
                                        </div>

                                    </div>
                                <?php endforeach ?>
                                <?php if(isset($result_text) && !empty($result_text)):?>
                                <div class="alert alert-danger" role="alert" style="margin: 0px !important; padding: 5px;padding-left: 15px"><?= $result_text; ?></div>
                                <?php endif;?>
                            </div>
                            <!-- / activity -->
                        </div>
                        <!-- / chat-area -->

                        <div class="chat-form">

                            <?php $form = ActiveForm::begin([

                                'action' => "/messages/send/",
                                'method' => 'post',
                                'options' => [
                                    'name' => 'message-form',
                                    'id' => 'message-form',
                                    'onsubmit' => "return false;"
                                ]
                            ]);

                            ?>
                            <div class="message-wrap">
                                <?=$form->field($messageSendForm, 'cid')->hiddenInput()->label(false) ?>

                                <?= $form->field($messageSendForm, 'message')->textarea([
                                    'id' => "message-text",
                                    'class' => "message-field chat-input",
                                    'placeholder' => Yii::t('app', 'Write a message') . "...",
                                    'readonly' => $input_readonly_status

                                ])->label(false) ?>

                                <div class="smiles" style="top: 4px !important;">
                                    <a href="javascript:;" class="icon-smileys chat-input-smile control-wink"
                                       id="control-wink"></a>
                                </div>
                                <div class="send-photo">
                                    <a href="javascript:;" class="icon-send-photo"
                                       id="control-wink" data-toggle="modal" data-target="#photoUploadModal"></a>
                                </div>
                                <!-- / smiles -->
                            </div>

                            <div class="btns">
                                <input class="btn" id="message-send-button" value="<?= Yii::t('app', 'Send') ?>"
                                       type="submit" onclick="sendMessage();" <?= $submit_button_status; ?>>
                            </div>

                            <div id="error-message-label" class="error message-error hidden"></div>

                            <?php ActiveForm::end() ?>

                            <ul class="wink-actions" id="wink-box" data-show="0">
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-watch hint" rel="kitty-watch">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-hide hint" rel="kitty-hide">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-wash hint" rel="kitty-wash">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-drink hint" rel="kitty-drink" title="Get drunk">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-happy hint" rel="kitty-happy" title="Happy">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-hunts hint" rel="kitty-hunts" title="Hunt">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-sad hint" rel="kitty-sad" title="Sad">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-angry hint" rel="kitty-angry" title="Rage">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-threaten hint" rel="kitty-threaten"
                                       title="To threaten">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-scared hint" rel="kitty-scared"
                                       title="Get scared">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-mur hint" rel="kitty-mur" title="Purr">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-think hint" rel="kitty-think"
                                       title="To fall to thinking">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="kitty-play hint" rel="kitty-play"
                                       title="Play">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kitty-leave hint" rel="kitty-leave" title="Go away">
                                    </a></li>
                                <br>&nbsp;
                                <hr id="separator">
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="wink hint" rel="wink" title="Wink">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="sad hint" rel="sad" title="To miss">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="kiss hint" rel="kiss" title="Kiss">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="spell hint" rel="spell" title="Enchant">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="tongue hint" rel="tongue"
                                       title="To put out a tongue">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="love hint" rel="love"
                                       title="Confess love">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="evil hint" rel="evil" title="To become angry">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="congrat hint" rel="congrat" title="Congratulate ">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="angel hint" rel="angel" title="to make a compliment">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="angry hint" rel="angry" title="To become angry">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="batman hint" rel="batman" title="Save">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="sunglasses hint" rel="sunglasses" title="Cool">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="bad hint" rel="bad" title="I like">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="smile hint" rel="smile" title="Smile">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="hipnotize hint" rel="hipnotize"
                                       title="To hypnotize">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="hipnotized hint" rel="hipnotized"
                                       title="To obey">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="kissed hint" rel="kissed"
                                       title="To thank for the kiss">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="laughing hint" rel="laughing" title="Laugh">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       href="javascript:;" class="watermelon hint" rel="watermelon"
                                       title="To be very surprised">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="pirate hint" rel="pirate"
                                       title="To take captive">
                                    </a></li>
                                <li class="do">
                                    <a onclick="sendSmile(this);"
                                       data-price="0" href="javascript:;" class="thinking hint" rel="thinking"
                                       title="To fall to thinking">
                                    </a></li>
                            </ul>
                        </div>
                        <!-- / chat-form -->
                    </div>
                    <!-- rigt-col-wrap -->
                </div>
                <!-- .rigt-col -->
            </div>
            <!-- / chat -->
            <?php
            if($imageSendStatus) {
                if($currentConversation['user_id'] != Yii::$app->params['adminUserId']) {
                    echo $this->render('partials/photo_upload_modal.php', ['imageForm' => $imageForm]);
                }
            } else {
                echo $this->render('partials/no_photo_upload_modal.php', ['imageForm' => $imageForm]);

            }
            ?>


        </div>
        <!-- / content -->
    </div>

</div>

<script type="text/javascript">


</script>