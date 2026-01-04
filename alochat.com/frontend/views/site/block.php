<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Alochat.com - Free dating,Online dating, dating singles, best online free dating site';
?>

<div id="main" class="container-fluid">
    <div class="row main1">
        <div class="col-md-12  col-lg-12 col-xs-12 col-sm-12">

            <hr style="width: 100%; background-color: #82c6f0; height: 1px; ">


            <div class="row" style="margin: 20px 0;height: 300px">

                <div class="col-md-11 col-xs-12 col-sm-12 col-lg-11 about-page-text">


                    <div style="color: #78c2f0;font-size: 20px; font-weight: bold; margin-bottom: 20px;">
                        <?= Yii::t('app', 'Warning') ?>
                    </div>
                    <div style="font-size: 18px;">
                        <?php
                        echo "Sizin girişinizə ".$timeString."  ərzində (".date("d-m-Y H:i:s",($user->block_begin_time+$user->block_time)).") tarixinədək qadağa qoyulub";
                        echo "<br /><b>Səbəb:</b> ".$block["reason"];
                        ?>
                    </div>


                    </div>
            </div>

        </div>
    </div>
</div>
