<?php

use frontend\components\LeftBarWidget;

$this->beginContent('@frontend/views/layouts/main.php'); ?>
<?= $this->render('/site/partials/modals/coins_modal'); ?>
<?php if (Yii::$app->user->id): ?>
    <div class="left-bar hidden-xs">

        <?= LeftBarWidget::widget() ?>
    </div>

<?php endif ?>

    <div class="center">
        <?= $content ?>
    </div>
    <div class="right-bar hidden-md hidden-sm hidden-xs" style="margin-bottom: 30px;">

        <div class="" style="margin-bottom: 20px;">
                        <?= \frontend\components\LastShareBarWidget::widget(['share_count' => 5,'topShareDay' => true]); ?>
            <div style="text-align: center;margin-top: 10px;">
                <a href="<?= \yii\helpers\Url::to(["/site/shares"])?>" class=" text-center"><?=  Yii::t('app','Bütün paylaşımlar')?></a>
            </div>
        </div>

      <!--  <aside id="right-bar" style="text-align: center">

            <a href="http://bescore.com" target="_blank"><img class="img-responsive inline-block" align="center" src="<?/*= \yii\helpers\Url::base() */?>/images/livescore_ads.jpg" style="width:100%;"></a>

        </aside>-->

    </div>

<?php $this->endContent(); ?>