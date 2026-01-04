<?php
/**
 * User: Yusif
 * Date: 7/13/2015
 * Time: 11:09 AM
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use frontend\assets\DiscoveryAsset;
use common\models\User;
use yii\helpers\Url;
use common\models\Notification;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Bildirişlər');
DiscoveryAsset::register($this);
 \frontend\assets\PhotoUploadAsset::register($this);

 ?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">   <?= $this->title ?> </div>
            <div class="pull-right" style="margin-top: -4px;">


                <select class="form-control" onchange="location = this.options[this.selectedIndex].value;" style="height: auto !important;padding: 3px;width: 148px;">
                    <option value="<?= Url::to(["/site/notifications2/?p=0"])?>" <?php if($not_read_filter==0) echo 'selected';else echo ''; ?>><?= Yii::t('app','Bütün bildirişlər')?></option>
                    <option value="<?= Url::to(["/site/notifications2/?p=1"])?>" <?php if($not_read_filter==1) echo 'selected';else echo ''; ?>><?= Yii::t('app','Baxılmayanlar')?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">



    <!--------------->
        <div class="center-block notification-block-outer" >
            <div>
                <?php
                if($notifications){

                    foreach($notifications as $notification){?>
                        <?php
                        $text = '';
                        $link = '';

                        if($notification["type"]==Notification::NOT_ALOCHAT_COIN){
                            $text = 'Alochat sizə bal hədiyyə etdi';
                            $link = Url::to(["/coins"."?ref=notification"]);
                        }elseif($notification["type"] == Notification::NOT_SHARE_COMMENT){
                            $text = 'Sizin paylaşıma rəy bildirdi';
                            $link = Url::to(["/profile/post/".$notification["share_id"]."?ref=notification"]);

                        }elseif($notification["type"] == Notification::NOT_SHARE_LIKE){
                            $text = 'Sizin paylaşımı bəyəndi';
                            $link = Url::to(["/profile/post/".$notification["share_id"]."?ref=notification"]);

                        }elseif($notification["type"] == Notification::NOT_USER_COIN){
                            $text =' sizə '.$notification["coin"]."bal hədiyyə etdi";
                            $link = Url::to(["/coins"."?ref=notification"]);

                        }elseif($notification["type"] == Notification::NOT_USER_FRIEND){
                            $text = ' sizə dostluq göndərdi';
                            $link = Url::to(["/profile/friend"."?ref=notification"]);


                        }elseif($notification["type"] == Notification::NOT_USER_FRIEND_REQUEST_CONFIRM){
                            $text = ' sizin dostluq təklifinizi qəbul etdi';
                            $link = Url::to(["/u/".$notification["user_id_from"]."?ref=notification"]);

                        }elseif($notification["type"] == Notification::NOT_USER_FRIEND_REQUEST_REMOVE){
                            $text = ' sizin dostluq sorğunuzu sildi';
                            $link = Url::to(["/u/".$notification["user_id_from"]."?ref=notification"]);


                        }elseif($notification["type"] == Notification::NOT_USER_GIFT){
                            $text = ' sizə hədiyyə göndərdi';
                            $link = Url::to(["/gift/".$notification["user_id"]."?ref=notification"]);


                        }elseif($notification["type"] == Notification::NOT_USER_LIKE){
                            $text = ' sizin profili bəyəndi';
                            $link = Url::to(["/profile/like"."?ref=notification"]);

                        }elseif($notification["type"] == Notification::NOT_USER_VISIT){
                            $text = ' sizin profili ziyarət etdi';
                            $link = Url::to(["/profile/visitors"."?ref=notification"]);

                        }elseif($notification["type"] == Notification::NOT_IMAGE_COMMENT){
                            $text = ' sizin şəklinizə rəy bildirdi';
                            $link = Url::to(["/profile/image/".$notification["share_id"]."?ref=notification"]);

                        }elseif($notification["type"] == Notification::NOT_IMAGE_LIKE){
                            $text = ' sizin şəklinizi bəyəndi';
                            $link = Url::to(["/profile/image/".$notification["share_id"]."?ref=notification"]);

                        }
                        $time = date("d-m-Y H:i",$notification["time"]);
                        $image  = $notification["image"];

                        if($image == ''){
                            $image = '/images/icons/sample.png';
                        }

                        $read_style = '';
                        if($notification["read"] != 2){
                            $read_style = 'notification-read';
                        }

                        $newNotificationText = '
                                <div class="notification-block '.$read_style.'">
                                    <div class="notification">
                                        <a href="'.$link.'">
                                            <div class="media-left"><img src="'.$image.'" class="media-object" alt="" style="width: 50px; height: 50px;"></div>
                                            <div class="notification-body media-body">

                                                <h6 class="media-heading">'.$notification["username"].'</h6>
                                                <p>'.$text.' <span class="pull-right">'.$time.'</span></p>
                                            </div>
                                        </a>
                                     </div>
                                </div>
                                      <hr />
                                 ';
                        echo $newNotificationText;
                    }

                }
                 ?>

            </div>
        </div>
    <!--------------->
    </div>
    <?php
    if ($pages) {
        // display pagination
        echo '<div class="text-center">';
        echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
            'options' => [
                'class' => 'pagination',
                'style' => 'display:inline-block'
            ],
            'maxButtonCount' => 6
        ]);
        echo '</div>';
    }
    ?>


</div>

<div style="display: none">
    <?= 'Opened: '.$timeFerq?>
</div>