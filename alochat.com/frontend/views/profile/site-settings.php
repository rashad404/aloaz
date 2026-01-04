<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Settings');

?>
<div class="profile-page-container settings-page-container">
    <div class="settings-header">
        <h4><?= $this->title ?></h4>
    </div>

    <div class="settings-content site-settings">
        <div class="form-group ">
            <label class="control-label" for="profilesettingsform-sex"><?= Yii::t('app', 'Language') ?></label>

            <select class="form-control" onchange="if (this.value) window.location.href=this.value">
                <?php foreach ($languages as $l => $lVal): ?>
                    <option id="<?= $l ?>"
                        <?= Yii::$app->language == $l ? "selected='selected'" : '' ?>
                            value="<?= Url::to(['site/language', 'id' => $l]) ?>"
                        >
                        <?= $languages[$l]['full'] ?>
                    </option>
                <?php endforeach ?>

            </select>

        </div>

    </div>
</div>
