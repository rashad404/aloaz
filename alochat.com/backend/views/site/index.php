<?php
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
$this->title = 'ALOCHAT Statistika';
?>

<div class="site-index">

         <h2>Statistika!</h2>

    <div class="body-content">

        <div class="col-md-12" style="margin: 15px 0;border: 1px solid #CCC;padding-top: 15px;">
            <p style="font-size: 18px;">
                <b>
                    Tarixə görə online olanlarının sayı
                </b>
            </p>

            <?php $form = ActiveForm::begin(); ?>
            <div class="form-group col-md-4">
                <label for="exampleInputBegin">Başlanğıc Tarix</label>
                <input type="text" name="begin_date" class="form-control" value="<?= $begin_date?>" id="exampleInputBegin" placeholder="<?= date("Y-m-d")?>">
            </div>
            <div class="form-group col-md-4">
                <label for="exampleInputBegin">Bitmə Tarix</label>
                <input type="text" name="end_date" class="form-control" value="<?= $end_date?>" id="exampleInputBegin" placeholder="<?= date("Y-m-d")?>">
            </div>
            <div class="form-group col-md-4">
                <input type="submit" name="dateOnline" class="btn btn-success" value="Bax" style="margin-top:25px;">
            </div>
            <?php ActiveForm::end(); ?>
            <div class="clearfix"></div>
            <br />
            <p>
                <?php echo date("d-m-Y",strtotime($begin_time_date))." tarixindən indiki vaxta kimi  online olmuş istifadəçi sayı:" . $statsForDate. "<br /><br />"; ?>
                <?php
                    foreach($statsForDateRows as $row ){
                        echo date("d-m-Y",strtotime($row["date"])).' tarixində online olmuş istifadəmilərin sayı:'. $row["all_day"]."<br />";
                    }
                ?>
            </p>
        </div>

        <div class="col-md-12" style="margin-bottom: 15px;">
            <ul class="nav nav-pills">
                <li  role="presentation"><a href="<?= \yii\helpers\Url::to(['/site/country-stats'])?>">Ölkə üzrə statistika</a></li>
                <li  role="presentation"><a href="<?= \yii\helpers\Url::to(['/site/city-stats'])?>">Şəhər üzrə statistika</a></li>
                <li  role="presentation"><a href="<?= \yii\helpers\Url::to(['/site/back-stats'])?>">İstifadəçilərin günlük statistikası</a></li>
                <li  role="presentation"><a href="<?= \yii\helpers\Url::to(['/site/diagram'])?>">Diaqram</a></li>
                <li  role="presentation"><a href="<?= \yii\helpers\Url::to(['/site/ref-stats'])?>">Ref-lərin statistikası</a></li>
                <li  role="presentation"><a href="<?= \yii\helpers\Url::to(['/site/transactions'])?>">Tranzaksiyalar</a></li>
                <li  role="presentation"><a href="<?= \yii\helpers\Url::to(['/site/coin-logs'])?>">Bal ödənişləri</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Ümumi istifadəçilərin sayı</th>
                    <th>Online istifadəçilərin sayı</th>
                    <th>Bu gün ərzində Online olmuş istifadəçilərin sayı</th>
                    <th>24 saat ərzində Online istifadəçilərin sayı</th>
                    <th>24 saat ərzində geri dönən istifadəçilərin sayı</th>
                    <th>Ümumi sohbetlerin sayı</th>
                    <th>Ümumi mesajların sayı</th>
                </tr>
                <tr>
                    <td>
                        <?php echo $users_count;?>
                    </td>
                    <td>
                        <a href="<?php echo \yii\helpers\Url::to(['user/online'])?>">
                            <?php echo $online_users_count;?></a>
                         <br /><?= $online_users_for_device?>
                    </td>
                    <td>
                        <?php echo $countActiveToday;?><br />

                    </td>
                    <td>
                        <?php echo $countActive24;?>
                    </td>
                    <td>
                        <?php echo $countActive;?>
                    </td>
                    <td>
                        <?php echo $conversations_count;?>
                    </td>
                    <td>
                        <?php echo $messages_count;?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Tarix</th>
                    <th>Gün ərzində Online olmuş istifadəçilərin sayı</th>
                    <th>24 saat ərzində Online istifadəçilərin sayı</th>
                    <th>24 saat ərzində geri dönən istifadəçilərin sayı</th>
                    <th>3 gün ərzində geri dönən istifadəçilərin sayı</th>
                    <th>7 gün ərzində geri dönən istifadəçilərin sayı</th>
                    <th>10 gün ərzində geri dönən istifadəçilərin sayı</th>
                    <th>30 gün ərzində geri dönən istifadəçilərin sayı</th>
                </tr>
                <?php
                foreach($stats as $stat) {
                    ?>
                    <tr>

                        <td><?php echo $stat["date"];?></td>

                        <td>  <?php echo $stat["all_day"];?> </td>

                        <td>  <?php echo $stat["all_24"];?>  </td>

                        <td>  <?php echo $stat["back_24"];?>  </td>

                        <td>  <?php echo $stat["back_3"];?>  </td>

                        <td>  <?php echo $stat["back_7"];?>  </td>

                        <td>  <?php echo $stat["back_10"];?>  </td>

                        <td>  <?php echo $stat["back_30"];?>  </td>

                    </tr>
                <?php
                }

                ?>

            </table>
        </div>




    </div>
</div>
acilmasi : <?= $vaxt; ?>