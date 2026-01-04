<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<div class="site-index">

         <h2>Ödəmələr!</h2>

    <div class="body-content">

        <div class="col-md-12">
            <p style="font-size: 18px;margin-top: 20px;">
                <b>
                    Tarixə görə ödəmələrin siyahısı
                </b>
            </p>

            <?php $form = \yii\widgets\ActiveForm::begin(); ?>
            <div class="form-group col-md-4">
                <label for="exampleInputBegin">Başlanğıc Tarix</label>
                <input type="text" name="begin_date" class="form-control" value="<?= date("Y-m-d 00:00",strtotime($begin_date))?>" id="exampleInputBegin" placeholder="<?= date("Y-m-d")?>">
            </div>
            <div class="form-group col-md-4">
                <label for="exampleInputBegin">Bitmə Tarix</label>
                <input type="text" name="end_date" class="form-control" value="<?= date("Y-m-d 23:59",strtotime($end_date))?>" id="exampleInputBegin" placeholder="<?= date("Y-m-d")?>">
            </div>
            <div class="form-group col-md-4">
                <input type="submit" name="dateOnline" class="btn btn-success" value="Bax" style="margin-top:25px;">
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
            <div class="clearfix"></div>
            <br />
        </div>
        <div class="col-md-12">
            <?php
                echo $begin_date." və ".$end_date." aralığında cəmi ödəniş məbləği - ".$transaction_sum["sum"]." AZN <br /> <br />";
            ?>
        </div>

         <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Tranzaksiya id</th>
                    <th>İstifadəçi</th>
                    <th>Məbləğ(AZN)</th>
                    <th>Bal</th>
                    <th>Tarix</th>
                    <th>Metod</th>
                    <th>Servis</th>
                </tr>
                <?php
                foreach($transactions as $transaction){

                   // foreach($c as $name=>$count){
                        echo '<tr>';
                        echo '<td>'.$transaction["id"].'</td>';
                        echo '<td><a href="http://m.alo.az/profile.php?uid='.$transaction["user_id"].'" target="_blank">'.$transaction["user_id"]." - ".$transaction["nickname"].'</a></td>';
                        echo '<td>'.$transaction["amount"].'</td>';
                        echo '<td>'.$transaction["coins"].'</td>';
                        echo '<td>'.$transaction["date"].'</td>';
                        echo '<td>'.$transaction["payment_method"].'</td>';
                        echo '<td>'.$transaction["payment_service"].'</td>';
                        echo '</tr>';
                   // }

                }

                ?>
            </table>
        </div>

    </div>
</div>
