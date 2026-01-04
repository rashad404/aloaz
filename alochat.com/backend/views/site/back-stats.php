<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<div class="site-index">

         <h2>Statistika!</h2>

    <div class="body-content">

        <div class="col-md-12">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Gün</th>
                    <th>istifadəçi qeydiyyatı</th>
                    <th>Göndərilən mesaj sayı</th>
                </tr>
                <?php
                foreach($data as $date => $day){
                    echo '<tr>';
                    echo '<td>'.$date.'</td>';
                    foreach($day as $key=>$value){
                        echo '<td>';
                        echo $key;
                        echo '</td>';
                        echo '<td>';
                        echo $value;
                        echo '</td>';
                    }

                    echo '</tr>';
                }
                ?>
            </table>
            <h4>Əvvəl qeydiyyatdan keçmiş istifadəçilərin 24 saat ərzində geri dönənlərin sayı</h4>
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>3 gün</th>
                    <th>7 gün</th>
                    <th>10 gün</th>
                    <th>30 gün</th>
                </tr>
                <tr>
                    <td><?= $oldUserActivity[3]; ?></td>
                    <td><?= $oldUserActivity[7]; ?></td>
                    <td><?= $oldUserActivity[10]; ?></td>
                    <td><?= $oldUserActivity[30]; ?></td>
                </tr>
            </table>
        </div>

    </div>
</div>
acilmasi : <?= $vaxt; ?>