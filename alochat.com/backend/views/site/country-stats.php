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
                    <th>Ölkə</th>
                    <th>İstifadəçi sayı</th>
                </tr>
                <?php
                foreach($countries_count as $k=>$c){

                   // foreach($c as $name=>$count){
                        echo '<tr>';
                        echo '<td>'.$c["name"].'</td>';
                        echo '<td>'.$c["count"].'</td>';
                        echo '</tr>';
                   // }

                }

                ?>
            </table>
        </div>

    </div>
</div>
acilmasi : <?= $vaxt; ?>