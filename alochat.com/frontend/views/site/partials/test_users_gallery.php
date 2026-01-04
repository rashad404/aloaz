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
    <?php if ($users): ?>

        <?php foreach ($users as $user): ?>

            <div class="col-sm-2 col-md-2 col-xs-4 col-sm-4" style="margin-bottom: 8px;">

                <a href="<?= Url::to(['/profile/index/', 'id' => $user['id']]) ?>">
                    <div class="">
                        <img

                            class="img-responsive" data-placement="left" title="<?= $user["full_name"]; ?>"
                            src="<?= $user['profile_photo'] ?
                                Url::base() . $user['profile_photo'] :
                                Url::base() . Yii::$app->params['defaultProfilePicture_'.$user['sex']]
                            ?>"
                            >
                    </div>

                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="container">
            <p class="text-danger"><?= Yii::t('app', 'Nobody found.') ?></p>
        </div>
    <?php endif; ?>
</div>