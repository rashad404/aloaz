<?php
use yii\helpers\Url;
use yii\widgets\Menu;

$messagesLabel = Yii::t('app', 'Messages');
if(!Yii::$app->user->isGuest){
    $newMsgCount = intval(Yii::$app->user->identity->getNewMessagesCount());
    if(Yii::$app->user->identity->block_time>0){
        Yii::$app->controller->redirect(Url::to(["site/block"]));
    }
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
 <nav id="w0" class="navbar-inverse navbar-fixed-top navbar" style="<?=$headerPositionStyle?>">
    <div class="container">
        <div class="navbar-header">

            <!--             <span class="custom-toggle navbar-toggle" data-toggle="collapse"-->
            <!--                   data-target="#w0-collapse"> </span>-->


            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse">

                <span class="custom-toggle"></span>

            </button>

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
                    ['label' => '<div class="users-ic"></div> '.Yii::t('app','Users'), 'url' => ['site/users']],
                    ['label' => '<div class="discovery-ic"></div> '.Yii::t('app','Discovery'), 'url' => ['site/discovery']],
                    ['label' => $spanMsg.' <div class="message-ic"></div> '.Yii::t('app','Messages'), 'url' => ['messages/index']],
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


            <div class="header-sub-menu hidden-lg hidden-sm hidden-md">
                <ul class="hidden-lg hidden-sm hidden-md">
                    <li>
                        <a href="<?= Url::to(['/site/users']) ?>">
                            <p> <span class="users-ic"></span> <?= Yii::t('app','Users')?> </p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/site/discovery']) ?>">
                            <p> <span class="discovery-ic"></span> <?= Yii::t('app','Discovery')?> </p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['messages/index/']) ?>">
                            <p> <span class="message-ic"></span> <?= Yii::t('app','Messages')?>  </p>
                        </a>
                    </li>
                </ul>
                <hr/>

            </div>


        </div>

        <div class="profile-short pull-right hidden-xs">


        </div>
    </div>
</nav>
