<?php
/* @var $this yii\web\View */
use dosamigos\datepicker\DatePicker;
$this->title = 'My Yii Application';
?>

<div class="site-index">

         <h2>Statistika!</h2>

    <div class="body-content">
        <div class="row">
        <?php
            $form = \yii\widgets\ActiveForm::begin();
        ?>
        <div class="col-md-3">
        <?= $form->field($model,'date_start')->widget(
            DatePicker::className(),
            [
                'inline' => false,
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy'
                    ]
            ]

        ); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model,'date_end')->widget(
                    DatePicker::className(),
                    [
                        'inline' => false,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]

                ); ?>
            </div>
        <div class="col-md-6">
            <input type="submit" class="btn btn-primary">
        </div>
        <?php \yii\widgets\ActiveForm::end();?>
        </div>
        <div class="clearfix"></div>



         <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Ref</th>
                    <th>İstifadəçi sayı</th>
                </tr>
                <?php
                foreach($refs as $k=>$r){

                   // foreach($c as $name=>$count){
                        echo '<tr>';
                        echo '<td>'.$r["ref"].'</td>';
                        echo '<td>'.$r["count"].'</td>';
                        echo '</tr>';
                   // }

                }

                ?>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>OnFROM</th>
                    <th>İstifadəçi sayı</th>
                </tr>
                <?php
                foreach($onfrom_stats as $k=>$r){

                    // foreach($c as $name=>$count){
                    echo '<tr>';
                    echo '<td>'.$r["onfrom"].'</td>';
                    echo '<td>'.$r["count"].'</td>';
                    echo '</tr>';
                    // }

                }

                ?>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Regfrom</th>
                    <th>İstifadəçi sayı</th>
                </tr>
                <?php
                foreach($regfrom_stats as $k=>$r){

                    // foreach($c as $name=>$count){
                    echo '<tr>';
                    echo '<td>'.$r["regfrom"].'</td>';
                    echo '<td>'.$r["count"].'</td>';
                    echo '</tr>';
                    // }

                }

                ?>
            </table>
        </div>

    </div>
</div>
acilmasi : <?= $vaxt; ?>