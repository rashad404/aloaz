<?php
use yii\helpers\Html;
use \yii\helpers\Url;
?>

<!-- Modal -->
<div class="modal fade" id="privacy-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= Yii::t('app', 'Privacy & Policy') ?></h4>

            </div>

            <div class="modal-body">
                <p><?= Yii::t('privacy','AloChat is free messaging and acquaintance platform.')?></p>
               <p>
                   <?= Yii::t('privacy','AloChat offers you services with following conditions. These conditions are considered to regulate the use from this service of you. Please read the terms of use attentively.')?>
               </p>
               <p><h4> <?= Yii::t('privacy','- Your obligations')?></h4></p>
               <p>
                   <?= Yii::t('privacy','You warrant that, you will use this service only in accordance with the terms of use, honestly, obeying the law and will not do the followings:')?><br />
                   <?= Yii::t('privacy','Obscene, pornographic, threatening, racist, dangerous, abusive, slanderous, revealing the secret( including copyrights) violate any intellectual property rights and objectionable or unlawful in other way ;')?><br />
                   <?= Yii::t('privacy','disrupt the security of the system or network which may cause to make a civil or criminal answer;')?>
                   <?= Yii::t('privacy','Use to sell site or services,advertisement or any services and goods for any commercial purpose without the consent of AloChat.')?>
               </p>
                <p><h4><?= Yii::t('privacy','- Rights')?></h4></p>
                <p>
                    <?= Yii::t('privacy',"AloChat has to right to efface or suspend the user's account in violation of terms of use from the service or Legislation of the Republic of Azerbaijan.")?><br />
                    <?= Yii::t('privacy','Terms of use can be changed by AloChat without any special warnings.')?>
                </p>
                <p><h4><?= Yii::t('privacy','- Responsibility')?></h4></p>
                <p>
                    <?= Yii::t('privacy',"AloChat assumes no responsibility for damages arising from the use of any service or quality.")?><br />
                    <?= Yii::t('privacy',"AloChat also does not assume the responsibility for any loss related to delays, non-observance, service failure not depending on AloChat.")?><br />
                    <?= Yii::t('privacy',"AloChat makes serious efforts ensure the safety of your information. But there is a risk of prevention and acquisition of your information unlawfully by the others except the person whom was sent for reasons beyond our control.")?><br />
                    <?= Yii::t('privacy',"AloChat doesn't bear responsibility for the content included to the site and services or related to the site that created or printed by use")?><br />
                </p>




            </div>  <!--Modal body-->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div><!-- Modal -->