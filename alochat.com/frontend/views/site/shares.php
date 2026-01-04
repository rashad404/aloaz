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
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Shares');
DiscoveryAsset::register($this);
 \frontend\assets\PhotoUploadAsset::register($this);


// echo count($users)."<br />";
?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">
    <?php
        /*if($_SERVER["REMOTE_ADDR"] == '37.32.67.22'){
            echo $ferqTime;
        }*/
    ?>
    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">   <?= $this->title ?> </div>
            <div class="pull-right" style="margin-top: -4px;">


                <select class="form-control" onchange="location = this.options[this.selectedIndex].value;" style="height: auto !important;padding: 3px;width: 148px;">
                    <option value="<?= Url::to(["/site/shares/?p=0"])?>" <?php if($friend_filter==0) echo 'selected';else echo ''; ?>><?= Yii::t('app','Bütün paylaşımlar')?></option>
                    <option value="<?= Url::to(["/site/shares/?p=1"])?>" <?php if($friend_filter==1) echo 'selected';else echo ''; ?>><?= Yii::t('app','Dostların paylaşdıqları')?></option>
                    <option value="<?= Url::to(["/site/top-day-shares"])?>" <?php if($topParam=='day') echo 'selected';else echo ''; ?>><?= Yii::t('app','24 saat ərzində top paylaşımlar')?></option>
                    <option value="<?= Url::to(["/site/top-week-shares"])?>" <?php if($topParam=='week') echo 'selected';else echo ''; ?>><?= Yii::t('app','1 həftə ərzində top paylaşımlar ')?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="send-share-block col-md-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form',
                'options' => ['enctype'=>'multipart/form-data']
            ]); ?>
            <?= $form->field($model,'text')->textarea(['class' => 'share-textarea','id' => 'share-text'])->label(false);?>
            <?= $form->field($model,'attach')->fileInput(['style' => 'display:none'])->label(false); ?>
            <div class="share-form-icons">
                <div style="float:left">
                    <img class="share-smile-icon cursor" src="/images/icons/share/smile.png" id="control-smile">

                        <ul class="wink-actions1"  data-show="0" id="wink-box">
                            <?php
                            $smilesArray = \common\models\ConversationReply::getSmiles();
                            foreach($smilesArray as $key=>$value){
                                ?>
                                <li class="do" style="float: left;
                                    width: 34px;
                                    height: 40px;">
                                    <a onclick="addSmile(this);"
                                       href="javascript:;"  rel="<?= $key; ?>">
                                        <img class="smile" src="/images/smiles/<?= $value ?>.png" alt="<?= $key; ?>"/>            </a>
                                </li>
                            <?php }
                            ?>

                        </ul>
                     <img class="share-photo-icon cursor" id="share-upload-photo" src="/images/icons/share/photo.png">
                    <span id="share_filename"></span>
                </div>
                <div style="float: right">
                    <?= \yii\helpers\Html::submitButton(Yii::t('app','Share'),['class' => 'btn blue-btn pull-right'])?>
                </div>
                <div class="share-permission">
                    <?//= $form->field($model,'permission')->dropDownList([0=>Yii::t('app','Everyone'),1=>Yii::t('app','Friends')],['class' => 'form-select'])->label(false); ?>
                    <?= $form->field($model,'permission')->checkbox([1])->label(Yii::t('app','Only Friends')); ?>
                </div>

                <div class="clear"></div>

            </div>

            <?php ActiveForm::end(); ?>
        </div>



    <!--------------->
         <?= $this->render('partials/share_gallery.php', ['shares' => $shares]); ?>


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