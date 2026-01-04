<?php
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Visitors');
?>

<div class="profile-page-container profile-page-main">


     <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div style="border-radius: 50%;float: left ;width: 24px;height: 24px;background: #67b638;margin-right: 5px;margin-top: -1px">
                <img src="/images/icons/visitors.png" style="margin-top:6px; margin-left:4px;  " height="11" width="16">
            </div> <div class="pull-left">   <?= $this->title ?> </div>
        </div>
    </div>
    <?= $this->render('partials/users_gallery.php', ['users' => $users]); ?>


    <?php
    if ($pages) {
        // display pagination
        echo '<div class="text-center">';

        echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);
        echo '</div>';
    }
    ?>


</div>