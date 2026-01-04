<?php

use frontend\components\LeftBarWidget;

$this->beginContent('@frontend/views/layouts/alochat-main.php'); ?>
<?= $this->render('/site/partials/modals/coins_modal'); ?>
<?php if (Yii::$app->user->id): ?>
    <div class="left-bar hidden-xs">

        <?= LeftBarWidget::widget() ?>

    </div>
<?php endif ?>

    <div class="center">
        <?= $content ?>
    </div>
    <div class="right-bar hidden-md hidden-sm hidden-xs">

        <aside id="right-bar" style="text-align: center">

            <a href="http://bescore.com" target="_blank"><img class="img-responsive inline-block" align="center" src="<?= \yii\helpers\Url::base() ?>/images/livescore_ads.jpg" style="width:100%;"></a>

        </aside>

    </div>
<?php $this->endContent(); ?>