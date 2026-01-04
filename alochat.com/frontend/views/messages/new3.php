<?php
use  \yii\helpers\Url;
use \frontend\assets\MessageAsset;
use frontend\assets\PhotoSendAsset;
use frontend\assets\PingAsset;
MessageAsset::register($this);
?>

<?php
PhotoSendAsset::register($this);



$this->registerJsFile(Yii::$app->request->baseUrl . '/js/fileinput_send/fileinput_send_locale_' . Yii::$app->language . '.js', ['depends' => [PhotoSendAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/image_upload_init.js', ['depends' => [PhotoSendAsset::className()]]);
$this->title = \yii\helpers\Html::encode($currentConversation['nickname']);


$this->getAssetManager()->getBundle(PingAsset::className(), false)->js = [];

$userProfilePhoto = Yii::$app->user->identity->profile_photo!=""?Url::base() .Yii::$app->user->identity->profile_photo:Yii::$app->params['defaultProfilePicture_'.Yii::$app->user->identity->sex];

?>

<script>
    var labelMe = '<?=Yii::t('app','Me')?>';
    var globalCid = '<?=$currentConversation['conversation_id']?>';
    var globalProfilePhoto = '<?=$currentConversation['profile_photo']?>';
    var userProfilePhoto = '<?=$userProfilePhoto?>';
    var globalConversationUser = '<?= \yii\helpers\Html::encode($currentConversation['nickname'])?>';
</script>

<!--Header and menu begin--->
<div class="container-fluid menu-block">
    <div class="container" style="padding-left: 0px; padding-right: 0px;">
        <div class="row">
            <div class="col-md-3 hidden-xs menu-header-left">
                <div class="message-left-title"><a class="navbar-brand" href="/site/users"></a>
                    <div class="pull-right">
                        <a class="header-message-icon" href="<?= Url::to(['/site/notifications']) ?>" style="margin-right: 10px;height: 50px;">
                        <span
                            style="display: none"
                            data-val='0'
                            class='new-notification-count1 notification-mob'>
                         </span>

                            <div class='notification-ic' style="margin-top: 13px;"></div>

                        </a>

                        <a class="header-message-icon" style="margin-right: 10px;height: 50px" href="<?= Url::to(['/site/shares']) ?>">


                            <div class='share-ic'></div>

                        </a>
                        <a class="header-message-icon" href="<?= Url::to(['/messages']) ?>" style="margin-right: 10px;height: 50px;">
                        <span
                            <?= $newMsgCount == 0 ? "style='display:none;'" : '' ?>
                            data-val='<?= $newMsgCount ?>'
                            class='new-message-count'><?= $newMsgCount ?>
                         </span>

                            <div class='message-ic'></div>

                        </a>

                        <a class="header-message-icon" style="margin-right: 10px;height: 50px;" href="<?= Url::to(['/site/users']) ?>">


                            <div class='users-ic'></div>

                        </a>

                        <div class="clear"></div>
                        <!--<a class="mobile-message-icon" style="margin-top: 12px;margin-right: 10px" href="<?/*= Url::to(['/site/search']) */?>">
                            <div class='search-ic'></div>
                        </a>
                        <a class=" mobile-message-icon" style="margin-top: 12px;margin-right: 10px" href="<?/*= Url::to(['/site/shares']) */?>">
                            <div class='share-ic'></div>
                        </a>
                        <a class="mobile-message-icon" href="<?/*= Url::to(['/messages']) */?>" style="margin-right: 10px">
                        <span
                            <?/*= $newMsgCount == 0 ? "style='display:none;'" : '' */?>
                            data-val='<?/*= $newMsgCount */?>'
                            class='new-message-count'><?/*= $newMsgCount */?>
                         </span>
                            <div class='message-ic'></div>
                        </a>
                        <a class="mobile-message-icon" style="margin-top: 12px;margin-right: 10px" href="<?/*= Url::to(['/site/users']) */?>">
                            <div class='users-ic'></div>
                        </a>

                        <div class="clear"></div>-->
                    </div>
                </div>

            </div>
            <div class="col-md-9 col-xs-12">
                <div class="back hidden-sm hidden-md hidden-lg"><a  href="<?= Url::to(["/messages/index"])?>" style="color: #FFF;margin-left: -5px;"><i class="fa fa-chevron-left" style="margin-top: 10px;"></i> </a><img src="<?= Url::base() . $currentConversation['profile_photo'] ?>" draggable="false"/></div>
                <div class="name"><a href="<?= Url::to(["/u/".$currentConversation["user_id"]])?>"><?= \yii\helpers\Html::encode($currentConversation['nickname'])?></a></div>
                <!--<div class="last"><?php
/*                    $today = date("d");

                    $day = date("d", $currentConversation['last_activity']);

                    if ($day == $today) {
                        $last = Yii::t('app', 'today') . ' ' . date('H:i', $currentConversation['last_activity']);
                    } elseif ($day == ($today - 1)) {

                        $last = Yii::t('app', 'yesterday') . ' ' . date('H:i', $currentConversation['last_activity']);
                    } else
                        $last = date('d.M.Y H:i', $currentConversation['last_activity']);

                    echo $last;

                    */?></div>-->
                <?php
                if(!Yii::$app->user->isGuest){
                    $newMsgCount = intval(Yii::$app->user->identity->getNewMessagesCount(Yii::$app->user->id));
                } else {
                    $newMsgCount = 0;
                }
                ?>
                <div class="pull-right">
                    <a href="#" id="dropdown-id" class="pull-right dropdown-toggle dropdown-id message-menu" >
                        <div class="dropdown-ic"></div>
                    </a>

                    <a href="javascript:;"  data-toggle="modal" data-target="#photoUploadModal"class="pull-right message-menu" style="margin-right: 20px;" >
                        <img src="/images/icons/camera.png" width="21" height="21" style="margin-top: 5px;">
                    </a>
                    <a class="header-message-icon hidden-sm hidden-md hidden-lg pull-right" href="<?= Url::to(['/messages']) ?>" style="margin-right: 10px;height: 50px;">
                        <span
                            <?= $newMsgCount == 0 ? "style='display:none;'" : '' ?>
                            data-val='<?= $newMsgCount ?>'
                            class='new-message-count'><?= $newMsgCount ?>
                         </span>
                        <div class='message-ic'></div>

                    </a>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>

</div>
<!--Header and menu end--->

    <div class="container conversations-block">
        <div class="row">
            <div class="col-md-3 hidden-xs col-sm-6 col-lg-3 conversations-inner">
                <div class="contacts-list" id="contactListWrapper">
                    <?php foreach($conversations as $conversation):?>

                        <div
                            id="conversation_<?= $conversation['conversation_id'] ?>"
                            data-counter="<?= $conversation['new_message_count']?>"
                            class="conversation-block contact fast
                            <?= $conversation['userOnline'] ? ' online ' : '' ?>
                             <?= $conversation['conversation_id'] == $id ? ' current ' : '' ?>
                            <?= $conversation['read'] != 1 ? ' is_new ' : '' ?> "
                            data-id="<?= $conversation['conversation_id'] ?>">


                            <a href="<?= Url::to(['/messages/view', 'id' => $conversation['conversation_id']]) ?>" class="conversation-link">
                                <div class="pull-left">
                                    <img alt="images" class="avatar user-image" width="40" height="40" src="<?= Url::base() . $conversation['profile_photo'] ?>" title="<?= $conversation['nickname'] ?>">
                                </div>
                            </a>

                            <div class="pull-left conversation-user">
                                <a href="<?= Url::to(['/messages/view', 'id' => $conversation['conversation_id']]) ?>" class="conversation-link" style="display: block;min-height: 40px; min-width: 180px;;">
                                    <span class="status"></span> <?= $conversation['nickname'] ?><br />
                                    <span class="conversation-user-reply"><?= $conversation["reply"]?></span>
                                </a>

                            </div>
                            <a href="<?= Url::to(['/messages/view', 'id' => $conversation['conversation_id']]) ?>" class="conversation-link">

                                <div class="pull-left" style=""></div>
                            </a>
                            <div class="clearfix"><?= \yii\helpers\Html::a('', ['messages/delete-conversation/', 'id' => $conversation['conversation_id']], [
                                    'class' => 'close',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure delete this conversation ?'),
                                        'method' => 'post',
                                    ],
                                ]) ?></div>
                        </div>
                    <?php endforeach;?>
                </div>

            </div>
        </div>
    </div>

<!--Messages block begin-->
<div class="container messages-block">
    <div class="row">
         <div class="col-md-9 col-md-offset-3 col-sm-6 col-sm-offset-6 col-lg-9 col-lg-offset-3 messages-block-inner">
             <ol class="chat">
                 <div  id="activity">
                     <?php
                     $messages = krsort($currentConversation['messages']);
                     foreach ($currentConversation['messages'] as $message):

                         if($message["user_id"] == Yii::$app->user->id){
                             $li_class = 'self';
                             $img_user_id = $userProfilePhoto;


                         }else {
                             $li_class = 'other';
                             $img_user_id = Url::base() . $currentConversation["profile_photo"];
                         }

                         if(date("d-m",$message["o_time"]) == date("d-m")){
                             $time_today = "style='display:block !important;margin-top:4px;'";
                             $time_otherday = "style='display:none'";
                             $p_class = 'style="padding: 0px 0px 6px;margin-right:0px;"';
                         }else {
                             $time_today = "style='display:none'";
                             $time_otherday = "style='display:block'";
                             $p_class = '';
                         }
                         ?>
                         <li class="<?= $li_class?>">
                             <div class="avatar"><img src="<?= $img_user_id ?>" draggable="false"/></div>
                             <div class="msg message">
                                 <p <?= $p_class?>><?= \yii\helpers\HtmlPurifier::process($message['reply']) ?><time  <?= $time_today?>><?= $message['time']?></time></p>
                                 <time <?= $time_otherday?>><?= $message['time'] ?></time>
                             </div>

                         </li>
                     <?php     endforeach ?>
                     <?php if(isset($result_text) && !empty($result_text)):?>
                         <div class="alert alert-danger" role="alert" style="margin: 0px !important; padding: 5px;padding-left: 15px"><?= $result_text; ?></div>
                         <br />
                     <?php endif;?>
                 </div>

             </ol>

         </div>
    </div>
</div>
<!--Messages block end-->





<div class="container textarea-block">
    <div class="row">
        <div class="col-md-9 col-md-offset-3 col-sm-6 col-sm-offset-6 col-lg-9 col-lg-offset-3 textarea-block-inner">
            <div class="emojis"  id="control-wink" style="float: left;">
                <a href="javascript:;" class="icon-smileys chat-input-smile control-wink"></a></div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([

                'action' => "/messages/send/",
                'method' => 'post',
                'options' => [
                    'name' => 'message-form',
                    'id' => 'message-form',
                    'onsubmit' => "return false;"
                ]
            ]);
            ?>
             <input id="messagesendform-cid" class="yusif" name="MessageSendForm[cid]" value="<?= $messageSendForm["cid"]?>" type="hidden">
            <textarea id="message-text" class="textarea pull-left" name="MessageSendForm[message]" placeholder="Mesaj yaz..." <?php  if($input_readonly_status) echo 'readonly'; ?>></textarea>
            <button  id="message-send-button" style="float: right"
                    type="submit" onclick="sendMessage2();" <?= $submit_button_status; ?>><i class='fa fa-chevron-right  fa-2x'></i></button>
            <div class="clear"></div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end() ?>


<!--includes-->
    <div class="container smile-block">
        <div class="row">
            <div class="col-md-9 col-md-offset-3 col-sm-6 col-sm-offset-6 col-lg-9 col-lg-offset-3">
                <ul class="wink-actions1"  data-show="0" id="wink-box">
                    <?php
                    $smilesArray = \common\models\ConversationReply::getSmiles();
                    foreach($smilesArray as $key=>$value){
                    ?>
                        <li class="do">
                            <a onclick="sendSmile(this);"
                               href="javascript:;"  rel="<?= $key; ?>">
                                <img class="smile" src="/images/smiles/<?= $value ?>.png" alt="<?= $key; ?>"/>            </a>
                        </li>
                    <?php }
                    ?>

                </ul>
            </div>
        </div>
    </div>

<!--<ul class="wink-actions"  data-show="0" id="wink-box">
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
-->
<?php
    if($imageSendStatus) {
        if($currentConversation['user_id'] != Yii::$app->params['adminUserId']) {
            echo $this->render('partials/photo_upload_modal.php', ['imageForm' => $imageForm]);
        }
    } else {
        echo $this->render('partials/no_photo_upload_modal.php', ['imageForm' => $imageForm]);

    }
?>