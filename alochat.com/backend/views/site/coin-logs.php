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
            <div class="form-group col-md-3">
                <label for="exampleInputBegin">Başlanğıc Tarix</label>
                <input type="text" name="begin_date" class="form-control" value="<?= date("Y-m-d 00:00",strtotime($begin_date))?>" id="exampleInputBegin" placeholder="<?= date("Y-m-d")?>">
            </div>
            <div class="form-group col-md-3">
                <label for="exampleInputBegin">Bitmə Tarix</label>
                <input type="text" name="end_date" class="form-control" value="<?= date("Y-m-d 23:59",strtotime($end_date))?>" id="exampleInputBegin" placeholder="<?= date("Y-m-d")?>">
            </div>
            <div class="form-group col-md-3">
                <label for="exampleInputBegin">Tipi</label>
                <select name="type" class="form-control">
                    <option value="1" <?= $type==1?'selected':'';?>>Xərclənib</option>
                    <option value="2" <?= $type==2?'selected':'';?>>Artırılıb</option>
                </select>
             </div>
            <div class="form-group col-md-3">
                <input type="submit" name="dateOnline" class="btn btn-success" value="Bax" style="margin-top:25px;">
            </div>

            <?php \yii\widgets\ActiveForm::end(); ?>
            <div class="clearfix"></div>
            <br />
        </div>
        <div class="col-md-12">
            <?php
                echo $begin_date." və ".$end_date." aralığında bal əməliyyatlarının sayı - ".$logs_count["c"]." <br /> <br />";
            ?>
        </div>

        <div class="col-md-12">
            <?php
                foreach($all_logs as $log){
                    echo $log["text"]." - ".$log["c"]." dənə ödəmə - ".$log["s"]." coin xərclənib <br />";
                }
            ?>
        </div>
        <br />
         <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Log id</th>
                    <th>İstifadəçi</th>
                    <th>İstifadəçi 2</th>
                    <th>Ballar</th>
                    <th>Tipi(1=>Xərclənib,2=>Artıtılıb)</th>
                    <th>Text</th>
                    <th>Vaxt</th>
                </tr>
                <?php
                foreach($logs as $log){

                   // foreach($c as $name=>$count){
                        echo '<tr>';
                        echo '<td>'.$log["id"].'</td>';
                        echo '<td><a href="http://m.alo.az/profile.php?uid='.$log["user_id"].'" target="_blank">'.$log["user_id"]." - ".$log["nickname"].'</a></td>';
                        echo '<td>'.$log["user_id2"]." - ".$log["nickname2"].'</td>';
                        echo '<td>'.$log["coins"].'</td>';
                        echo '<td>'.$log["type"].'</td>';
                        echo '<td>'.$log["text"].'</td>';
                        echo '<td>'.$log["date"].'</td>';
                        echo '</tr>';
                   // }

                }

                ?>
            </table>
        </div>

    </div>
</div>
