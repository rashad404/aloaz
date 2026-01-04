<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 04.05.2015
 * Time: 18:07
 */
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

?>


<div class="row">

    <?php
    $i=1;$st= '';
    if ($users): ?>

        <?php foreach ($users as $user): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                <div class="row new-user-padding" <!--style="--><?php /*if($i%2==1) echo 'padding-right: 10px;'; else echo 'padding-left:10px;';*/?>">
                    <div class="new-user-block">
                        <div style="float: left">
                            <a href="<?= Url::to(['/profile/index/', 'id' => $user['id']])?>">
                                <img class="pull-left" src="<?= $user['profile_photo']?Url::base() . $user['profile_photo'] : Url::base() . Yii::$app->params['defaultProfilePicture_'.$user['sex']] ?>" data-sec-id="35" id="2686" height="92" width="92">
                            </a>
                        </div>
                        <div class="new-user-block-about">
                            <a href="<?= Url::to(['/profile/index/', 'id' => $user['id']])?>">
                                <div>

                                    <div class="new-user-block-name">
                                        <?php if (\common\models\User::isOnlineWithActivity($user['last_activity'])){ ?>

                                            <span class="online" title="Online"><div class="status online"></div> </span>
                                        <?php } else{
                                            ?>
                                            <span class="online2" title="Offline"><div class="status2 online2"></div> </span>

                                        <?php
                                        } ?>
                                        <?= mb_substr(Html::encode($user['nickname'])."", 0, 18,'utf-8' ) ?>

                                    </div>

                                    <p class="new-user-block-city">
                                        <?= $user['age'] . ' ' .Yii::t('app', 'years')?><?=isset($user['city_name'])?', ' . $user['city_name'] : '' ?>
                                    </p>
                                    <p class="user-block-last-post">
                                        <?= mb_substr(Html::encode($user['last_post'])."", 0, 55,'utf-8' ) ?>
                                    </p>
                                </div>
                            </a>

                        </div>
                        <div class="new-user-block-send">
                            <a href="<?= Url::to(['/messages/view', 'u' => $user['id']]) ?>#chat">
                                <img src="/images/icons/send-message.png" style="margin-top: 10px;">
                            </a>
                            <a href="<?= Url::to(['/profile/gift/', 'id' => $user['id']])?>">
                                <img src="/images/icons/send-gift.png" style="margin-top: 18px;">
                            </a>
                        </div>
                        <div style="float: right;width: 20px;min-height: 80px; margin-top: 10px;margin-right: 5px;padding-left: 5px;">
                            <?php if($user['sex']==User::SEX_MAN) $sex_icon = 'man-icon.png';
                            elseif($user['sex'] == User::SEX_WOMAN) $sex_icon = 'woman-icon.png'?>

                            <img src="/images/icons/<?= $sex_icon?>"><br /><br /><br />

                        </div>

                    </div>
                </div>
            </div>
            <?php $i++;?>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="container">
            <p class="text-danger"><?= Yii::t('app', 'Nobody found.') ?></p>
        </div>
    <?php endif; ?>
</div>