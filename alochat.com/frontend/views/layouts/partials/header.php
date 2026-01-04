<?php
use yii\helpers\Url;
use yii\widgets\Menu;

$messagesLabel = Yii::t('app', 'Messages');
if(!Yii::$app->user->isGuest){
    if(Yii::$app->user->identity->block_time>0){
       Yii::$app->controller->redirect(Url::to(["site/block"]));
    }
    $newMsgCount = intval(Yii::$app->user->identity->getNewMessagesCount(Yii::$app->user->id));
    $newNotificationCount = intval(Yii::$app->user->identity->getNewNotificationCount(Yii::$app->user->id));
} else {

    $newMsgCount = 0;
    $newNotificationCount = 0;
}

$headerPositionStyle ='border:0px none !important;';
$controllerId = $this->context->id;
$actionId = $this->context->action->id;

if($controllerId == "messages" && $actionId =="view"){
    $headerPositionStyle = "position:absolute;";
}
?>
<nav id="w0" class="navbar-inverse navbar-fixed-top navbar z-depth-1" style="<?=$headerPositionStyle?>">
    <div class="container">
        <div class="navbar-header">

            <div class="hidden-lg hidden-md hidden-sm  profile-short pull-right">
                <div class="nav navbar-nav navbar-right">
                    <a href="#" id="dropdown-id mobile-message-icon" class="dropdown-toggle dropdown-id" >
                        <div class="dropdown-ic"></div>

                    </a>

                </div>
                <a id="profile-page-link"
                   href="<?= Url::to(['profile/index/', 'id' => Yii::$app->user->id]) ?>">
                    <span class="name hidden-xs"><?= Yii::$app->user->identity->full_name ?> </span>
                    <img class="profile-photo img-circle" width="30" height="30"
                         src="<?= Yii::$app->user->identity->profile_photo
                             ? Url::base() . Yii::$app->user->identity->profile_photo
                             : Url::base() . Yii::$app->params['defaultProfilePicture_'.Yii::$app->user->identity->sex] ?>">
                </a>

            </div>
            <div style="float: right;margin-right: 11px;">
                <a class="hidden-lg hidden-md hidden-sm mobile-message-icon" href="<?= Url::to(['/site/notifications']) ?>" style="height: 57px;">
                        <span
                            style="display: none"
                            data-val='0'
                            class='new-notification-count1 notification-mob'>
                         </span>

                    <div class='notification-ic' style="margin-top: 13px;"></div>

                </a>

                <a class="hidden-lg hidden-md hidden-sm mobile-message-icon" style="height: 57px" href="<?= Url::to(['/site/shares']) ?>">


                    <div class='share-ic'></div>

                </a>
                <a class="hidden-lg hidden-md hidden-sm mobile-message-icon" href="<?= Url::to(['/messages']) ?>" style="height: 57px;">
                        <span
                            <?= $newMsgCount == 0 ? "style='display:none;'" : '' ?>
                            data-val='<?= $newMsgCount ?>'
                            class='new-message-count'><?= $newMsgCount ?>
                         </span>

                    <div class='message-ic'></div>

                </a>

                <a class="hidden-lg hidden-md hidden-sm mobile-message-icon" style="height: 57px;" href="<?= Url::to(['/site/users']) ?>">


                    <div class='users-ic'></div>

                </a>

                <div class="clear"></div>
            </div>

            <a class="navbar-brand" href="<?= Url::to(['/site/users/']) ?>"></a>


        </div>
        <div id="w0-collapse" class="collapse navbar-collapse">
            <?php
            $spanMsg ="";
            $spanMsgStyle ='';

            if($newMsgCount<1){
                $spanMsgStyle = 'style="display:none"';
            }
            $spanMsg = '<span data-val='.$newMsgCount.' class="new-message-count" '.$spanMsgStyle.'>'.$newMsgCount.'</span>';



            echo Menu::widget([
                'items' => [
                    ['label' => '<div class="users-ic"></div> <div class="nav-menu-title">'.Yii::t('app','Users').'</div>', 'url' => ['site/users']],
                    ['label' => $spanMsg.' <div class="message-ic"></div> <span class="nav-menu-title">'.Yii::t('app','Messages').'</span>', 'url' => ['messages/index']],
                    ['label' => '<div class="share-ic"></div> <span class="nav-menu-title">'.Yii::t('app','Shares').'</span>', 'url' => ['site/shares']],
                    ['label' => '<div class="search-ic"></div> <div class="nav-menu-title">'.Yii::t('app','Search').'</div>', 'url' => ['site/search']],


                ],
                'activeCssClass'=>'navbar-active',
                'options' => [
                    'class' => 'navbar-nav nav hidden-xs',
                    'id'   => 'w1'
                ],
                'encodeLabels' => false,
                'linkTemplate' => '<a href="{url}"><span>{label}</span></a>',
            ]);
            ?>

        </div>

        <div class="profile-short pull-right hidden-xs">
            <?php
           // if($_SERVER["REMOTE_ADDR"]=='37.32.67.22'){
                ?>
                <ul class="nav navbar-nav  notification-id hidden-xs hidden-sm" style="float:left ; margin-left: 0px;margin-top: -12px;">
                    <li class="dropdown">
                        <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14" style="padding: 10px 0px 10px 5px">
                            <div class="notification-ic"></div>
                            <span class="label new-notification-count" data-val="0" style="display: none">0</span>
                        </a>
                        <ul class="dropdown-menu media-list pull-right animated fadeInDown" id="notificationText">
                            <li class="dropdown-header">Yeni bildiriş yoxdur</li>
                            <li class="dropdown-footer text-center">
                                <a href="<?= Url::to(["/site/notifications"])?>">Bütün bildirişlərə bax</a>
                            </li>
                        </ul>
                    </li>
                    <li style="padding: 5px 0px 5px 0px;">
                        <a id="profile-page-link"
                           href="<?= Url::to(['profile/index/', 'id' => Yii::$app->user->id]) ?>" style="padding: 10px 0px 10px 5px;">
                            <span class="name"><?= Yii::$app->user->identity->full_name ?> </span>
                            <img class="profile-photo img-circle" width="30" height="30"
                                 src="<?= Yii::$app->user->identity->profile_photo
                                     ? Url::base() . Yii::$app->user->identity->profile_photo
                                     : Url::base() . Yii::$app->params['defaultProfilePicture_'.Yii::$app->user->identity->sex] ?>"
                                >
                        </a>
                    </li>
                    <li style="padding: 5px 0px 5px 5px;">
                        <a href="#" id="dropdown-id" class="dropdown-toggle dropdown-id" style="padding: 10px 0px;">
                            <div class="dropdown-ic"></div>

                        </a>
                    </li>

                </ul>
            <?php
           // }
            ?>



        </div>

    </div>
</nav>