<?php
use yii\helpers\Html;
use frontend\assets\DiscoveryAsset;
use yii\helpers\Url;

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Discovery');
DiscoveryAsset::register($this);
?>
<?= $this->render('partials/discovery_settings_modal', ['discoveryFilterForm' => $discoveryFilterForm, 'countries' => $countries, 'cities' => $cities]); ?>
<?= $this->render('partials/set_vip_user_modal'); ?>
<script>
    var lblImages = '<?= Yii::t('app', 'images')?>';
    var lblYears = '<?= Yii::t('app', 'years')?>';
</script>

<div class="container dating-container">
    <?php if (!empty($foundUsers)): ?>
        <div class="dating-split">

            <div class="dating-area">

                <div class="rate">

                    <div class="user-info">

                        <?php if (!empty($foundUsers['current'])): ?>
                            <div class="user-name">

                                <a id="currentUserName" data-id="<?= $foundUsers['current']['id'] ?>"
                                   class="currentUserLink"
                                   href="<?= Url::to(['/profile/index/', 'id' => $foundUsers['current']['id']]) ?>">

                                    <?= Html::encode($foundUsers['current']['full_name']) ?>
                                </a>
                            </div>

                            <div id="currentUserMeta" class="user-meta">
                                <span
                                    id="userAge"> <?= $foundUsers['current']['age'] ?></span>&nbsp;<?= Yii::t('app', 'years') ?>
                                <span
                                    id="userCity"> <?= isset($foundUsers['current']['city_name']) ? ', ' . $foundUsers['current']['city_name'] : '' ?></span>
                            </div>
                        <?php endif; ?>


                    </div>
                    <!-- / user-info -->

                    <div class="users-dating">

                        <div style="display: <?= isset($foundUsers['previous']['id']) ? 'block' : 'none' ?>;"
                             id="previewPrevious" class="user-dating level-2 left">

                            <b id="prevName"><?= isset($foundUsers['previous']['full_name']) ? Html::encode($foundUsers['previous']['full_name']) : '' ?></b>
                            <p id="prevMeta">
                                <span> <?= isset($foundUsers['previous']['age'])?$foundUsers['previous']['age']:'' ?></span>&nbsp;<?= Yii::t('app', 'years') ?>
                                <span > <?= isset($foundUsers['previous']['city_name']) ? ', ' . $foundUsers['previous']['city_name'] : '' ?></span>
                             </p>

                            <a href="#" id="discoveryPreviousBtn"
                               data-id="<?= isset($foundUsers['previous']['id']) ? $foundUsers['previous']['id'] : '' ?>">
                                <img id="prevUserImg" class="prevPhoto"
                                     src="<?= $foundUsers['previous']['profile_photo']!=null ? $foundUsers['previous']['profile_photo'] : '/images/icons/male_0.png' ?>">
                                <button
                                    style="display: <?= isset($foundUsers['previous']['id']) ? 'block' : 'none' ?>;"
                                    title="<?= Yii::t('app', 'Previous') ?>"
                                    class="btn btn-sm btn-default pull-left discoveryPreviousBtn">
                                    <i></i>
                                </button>

                            </a>

                        </div>

                        <?php if (!empty($foundUsers['current'])):

                            ?>
                            <div class="user-dating level-1">
                                <a class="currentUserLink"
                                   href="<?= Url::to(['/profile/index/', 'id' => $foundUsers['current']['id']]) ?>">
                                    <img src="<?= $foundUsers['current']['main_photo'] ?>"
                                         id="avatar" border="0">
                                </a>
                            </div>

                        <?php else: ?>

                            <p class="text-error"><?= Yii::t('app', "Nobody found.") ?></p>

                        <?php endif; ?>

                        <div  style="display: <?= isset($foundUsers['next']['id']) ? 'block' : 'none' ?>;"
                            id="previewNext" class="user-dating level-2 right">

                            <b id="nextName" ><?= isset($foundUsers['next']['full_name']) ? Html::encode($foundUsers['next']['full_name']) : '' ?></b>
                            <p id="nextMeta">
                                <span> <?= isset($foundUsers['next']['age'])?$foundUsers['next']['age']:'' ?></span>&nbsp;<?= Yii::t('app', 'years') ?>
                                <span > <?= isset($foundUsers['next']['city_name']) ? ', ' . $foundUsers['next']['city_name'] : '' ?></span>
                            </p>

                            <a id="discoveryNextBtn" href="#"
                               data-id="<?= isset($foundUsers['next']['id']) ? $foundUsers['next']['id'] : '' ?>">
                                <img id="nextUserImg" class="prevPhoto"
                                     src="<?= $foundUsers['next']['profile_photo']!=null ? $foundUsers['next']['profile_photo'] : '/images/icons/male_0.png' ?>">
                                <button
                                    style="display: <?= isset($foundUsers['next']['id']) ? 'block' : 'none' ?>;"


                                    title="<?= Yii::t('app', 'Next') ?>"
                                    class="btn btn-sm btn-default pull-right discoveryNextBtn">
                                    <i></i>
                                </button>

                            </a>
                        </div>

                    </div>

                    <!-- / users-dating -->
                    <?php if (isset($foundUsers['current']['images'])): ?>
                        <ul class="preview hidden-xs  hidden-sm " id="userOtherImages">

                            <?php foreach ($foundUsers['current']['images'] as $image): ?>
                                <li>
                                    <a class="currentUserLink"
                                       href="<?= Url::to(['/profile/index/', 'id' => $foundUsers['current']['id']]) ?>"
                                       class="album-photos">
                                        <img src="<?= $image ?>">
                                    </a>
                                </li>
                            <?php endforeach ?>

                        </ul>
                        <?php if (isset($foundUsers['current']['remainingImageCount'])): ?>

                            <p id="otherImagesCount" class="hidden-xs  hidden-sm">
                                +<?= $foundUsers['current']['remainingImageCount'] . ' ' . Yii::t('app', 'images') ?></p>

                        <?php endif ?>
                    <?php endif ?>
                    <div class="clearfix"></div>

                    <button id="discovery-filter-button" data-toggle="modal" data-target="#dating-filter-modal"
                            title="<?= Yii::t('app', 'Settings') ?>" class="btn btn-sm btn-default pull-center">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>
                <!-- / rate -->

            </div>
            <!-- Dating Area -->

        </div>  <!--  Dating split -->
    <?php endif; ?>

    <div class="discovery-vip-title">
        <div class="inline-block">

            <div class="vip-ic"></div>
            <div class="pull-left"> <a href="<?= Url::to(['/profile/vip-users/']);?>" class="link-default"><?= Yii::t('app','VIP USERS')?></a></div>

            <div class="vip-ic"></div>

            <div class="clearfix"></div>
        </div>
    </div>

    <div class="">
        <div class="vip-gallery-block">

            <div class="rate-vip">

                <div class="container-fluid inline-block">

                    <p>

                        <button id="vip-user-button" data-toggle="modal" data-target="#vip-user-modal"
                                title="<?= Yii::t('app', 'Become a vip user') ?>" class="btn btn-sm btn-default pull-center">
                            <i class="glyphicon glyphicon-ok"></i>
                            <?= Yii::t('app', 'Become a VIP user'); ?>
                        </button>

                    </p>

                    <div class="row">
                          <?php
                          if(count($vipUsers)>0) $countVipUser = count($vipUsers);
                          else $countVipUser = 1;
                          $col =  12/$countVipUser;
                          foreach($vipUsers as $user){?>

                         <div class="col-md-<?=$col?> col-sm-6 col-lg-<?=$col?> col-xs-6 vip-photo">

                                <div class="inline-block">

                                    <a class="link-default" href="<?= Url::to(['/profile/index/', 'id' => $user['id']]) ?>">


                                        <img src="<?=
                                            Url::base() . $user['profile_photo']

                                        ?>" class="vip-img img-rounded img-circle img-responsive" alt="<?php echo $user["full_name"]; ?>">

                                        <div class="vip-user">

                                            <?=$user["full_name"];?> <br />

                                            <?php echo $user["age"]; ?> <?= Yii::t('app','years')?>, <?php echo $countries[$user["country_id"]]; ?>

                                        </div>
                                    </a>

                                </div>

                            </div>

                        <?php  }     ?>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
