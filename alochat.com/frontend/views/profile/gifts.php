<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;

PhotoUploadAsset::register($this);
$this->title = $user->full_name." ".Yii::t('app','Gifts');
echo $this->render('partials/gift_modal');
?>




<div class="row">

    <div class="col-md-12" style="background-color: #f5f5f5">
        <div class="row profile-title-block" id="user-filter1">
            <div class="col-md-12">
             <ul class="nav nav-tabs profile-nav-tabs">
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/timeline/'.$user["id"]])?>">Timeline</a></li>
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/photos/'.$user["id"]])?>"><?= Yii::t('app','Photos')?></a></li>
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/friends/'.$user["id"]])?>"><?= Yii::t('app','Friends')?></a></li>
                    <li role="present   ation"  class="active"><a href="<?php echo  Url::to(['/profile/gifts/'.$user["id"]])?>"><?= Yii::t('app','Gifts')?></a></li>
             </ul>
            </div>
        </div>
    </div>
    <div class="center-block col-md-12">



        <div id="modal_id"></div>


        <?php if(!$isOwnProfile) {?>
            <div  id="usergifts">
                 <h5><?= Yii::t('app','Choose one of the following gifts')?></h5>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" style="float: left !important;" role="tablist">

                    <?php
                    $cat_i = 1;
                    foreach($categories as $category){
                        ?>
                        <li role="presentation" class="<?php echo $cat_i==1?'active':'';?>"><a href="#giftTab<?php echo $category["id"];?>" aria-controls="home" role="tab" data-toggle="tab"><?= $category["name"]; ?></a></li>
                        <?php
                        $cat_i++;
                    }
                    ?>
                </ul>
                <div class="clearfix"></div>

                <!-- Tab panes -->
                <div class="tab-content">

                    <?php
                    $cat_i = 1;
                    foreach($categories as $category){
                        ?>
                        <div role="tabpanel" class="tab-pane <?php echo $cat_i==1?'active':''; ?>" id="giftTab<?php echo $category["id"];?>" style="padding-top: 10px;padding-left: 10px;">
                            <div class="row">
                                <?php

                                foreach($gifts as $gift) {

                                    if ($gift['category_id'] == $category['id']){
                                        ?>
                                        <div class="col-xs-6 col-md-2 col=lg-2 col-sm-3">
                                            <a  class="thumbnail giftId" style="cursor: pointer;width: 100px;border: none" data-uid="<?= $id; ?>" id="<?= $gift['id']; ?>" data-toggle="modal"
                                                data-target="#giftModal">
                                                <img src="<?php echo $gift['icon']; ?>" alt="..." class="img-responsive" >
                                                <p style="text-align: center;"><?php  echo $gift['coin']?> <?= Yii::t('app','Coins')?></p>
                                            </a>
                                        </div>
                                    <?php
                                    }
                                }
                                ?>


                            </div>
                        </div>
                        <?php
                        $cat_i++;
                    }
                    ?>
                </div>

            </div>
        <?php } ?>
        <div>
            <h3><?php echo Yii::t('app','Received gifts'); ?></h3>


            <?php
            if(count($userGifts) > 0):
                foreach($userGifts  as $userGift)
                {
                    ?>
                    <div class="row" style="border-bottom: 1px solid #ccc">
                        <div class="col-lg-1">
                            <img src="<?php echo $userGift['icon']?>" height="72" width="72">
                        </div>
                        <div class="col-lg-5">
                            <h4><p><?php echo $userGift['comment']; ?></p></h4>

                            <p>
                                <?php if($isOwnProfile): ?>

                                    <span style="float: right"><a  href="/profile/delete-gift?id=<?= $userGift['id']?>" onclick="return confirm('<?= Yii::t('yii','Are you sure you want to delete this item?')?>')"><span class="glyphicon glyphicon glyphicon-trash"></span> <?= Yii::t('app','Delete')?></a></span>

                                <?php endif; ?>

                                <a href="<?php echo Url::to('/u/'.$userGift['user_id'])?>">
                                    <?php
                                    if($userGift['path']==''){
                                        $userGift['path'] =  $userGift['sex']==User::SEX_WOMAN?'/images/icons/female_0.png':'/images/icons/male_0.png';
                                    }
                                    ?>

                                    <img src="<?php echo $userGift['path']; ?>" width="32" height="32" class="img-circle"> <?php echo $userGift['full_name']; ?>
                                </a> <span style="padding-left: 10px">  <?php echo date('d/m/Y H:i',$userGift['time'])?></span>
                            </p>
                        </div>
                    </div>
                <?php
                }
            else:
                echo Yii::t('app','This user has not a gift.').' '.Yii::t('app','Be the first sender.');

            endif;
            ?>

        </div>



    </div>
</div>
 