<?php
use yii\helpers\Html;
use yii\helpers\Url;

$langId = trim(Yii::$app->language);

?>

<div class="pull-left language-change-block">

    <div class="btn-group btn-input clearfix">

        <button type="button"
                class="btn btn-default dropdown-toggle form-control"
                data-toggle="dropdown">

            <img src="<?= Url::base() . "/images/icons/" . Yii::$app->language . ".png" ?>"/>

                                <span data-bind="label">
                                <?= $languages[$langId]['short'] ?>
                            </span>
            <span class="caret"></span>

        </button>

        <ul class="dropdown-menu language-change" role="menu">

            <?php foreach ($languages as $l => $lVal): ?>
                <li>
                    <a href="<?= Url::to(['site/language', 'id' => $l]) ?>">
                        <img class="language-dropdown-icon"
                             src="<?= Url::base() . "/images/icons/" . $l . ".png" ?>"/>
                        <span><?= $languages[$l]['short'] ?></span>
                    </a>
                </li>
            <?php endforeach ?>

        </ul>

    </div>

</div>