<?php
use yii\helpers\Url;
use yii\widgets\Menu;


$messagesLabel = Yii::t('app', 'Messages');
if(!Yii::$app->user->isGuest){
    $newMsgCount = intval(Yii::$app->user->identity->getNewMessagesCount(Yii::$app->user->id));
} else {
    $newMsgCount = 0;
}

$headerPositionStyle ='';
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
                    <a href="#" id="dropdown-id" class="dropdown-toggle dropdown-id" >
                        <div class="dropdown-ic"></div>

                    </a>

                </div>
                <a id="profile-page-link"
                   href="<?= Url::to(['profile/index/', 'id' => Yii::$app->user->id]) ?>">
                    <span class="name"><?= Yii::$app->user->identity->full_name ?> </span>
                    <img class="profile-photo img-circle" width="30" height="30"
                         src="<?= Yii::$app->user->identity->profile_photo
                             ? Url::base() . Yii::$app->user->identity->profile_photo
                             : Url::base() . Yii::$app->params['defaultProfilePicture_'.Yii::$app->user->identity->sex] ?>">
                </a>

            </div>
            <a class="hidden-lg hidden-md hidden-sm mobile-message-icon" href="<?= Url::to(['/messages']) ?>">
                        <span
                            <?= $newMsgCount == 0 ? "style='display:none;'" : '' ?>
                            data-val='<?= $newMsgCount ?>'
                            class='new-message-count'><?= $newMsgCount ?>
                         </span>

                <div class='message-ic'></div>

            </a>
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
                    ['label' => '<div class="discovery-ic"></div> <span class="nav-menu-title">'.Yii::t('app','Discovery').'</span>', 'url' => ['site/discovery']],
                    ['label' => $spanMsg.' <div class="message-ic"></div> <span class="nav-menu-title">'.Yii::t('app','Messages').'</span>', 'url' => ['messages/index']],
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

            <a id="profile-page-link"
               href="<?= Url::to(['profile/index/', 'id' => Yii::$app->user->id]) ?>">
                <span class="name"><?= Yii::$app->user->identity->full_name ?> </span>
                <img class="profile-photo img-circle" width="30" height="30"
                     src="<?= Yii::$app->user->identity->profile_photo
                         ? Url::base() . Yii::$app->user->identity->profile_photo
                         : Url::base() . Yii::$app->params['defaultProfilePicture_'.Yii::$app->user->identity->sex] ?>"
                    >
            </a>

            <a href="#" id="dropdown-id" class="dropdown-toggle dropdown-id" >
                <div class="dropdown-ic"></div>

            </a>
        </div>

    </div>
</nav>