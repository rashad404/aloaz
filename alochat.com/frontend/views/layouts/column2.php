<?php

use frontend\components\LeftBarWidget;

$this->beginContent('@frontend/views/layouts/main.php'); ?>

<?php if (Yii::$app->user->id): ?>
    <div  class="left-bar hidden-xs" >

        <?= LeftBarWidget::widget() ?>

    </div>
<?php endif ?>

    <div class="center">
        <?= $content ?>
    </div>

<?php $this->endContent(); ?>