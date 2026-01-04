<?php

namespace frontend\controllers;

 use common\models\CoinLogs;

 use common\models\Notification;
 use common\models\User;
 use frontend\models\ImageSendForm1;
 use frontend\models\NickChangeForm;
 use frontend\models\OnlineRatingForm;
 use frontend\models\SendCoinForm;
 use Yii;
 use yii\filters\AccessControl;
 use yii\filters\VerbFilter;

 class CoinsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],

            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'payment-redirect' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $db = Yii::$app->db;
        $this->layout = 'column3';


        if(htmlspecialchars(trim($_GET["ref"])) == 'notification'){
            $types = [Notification::NOT_ALOCHAT_COIN,Notification::NOT_USER_COIN];
            Notification::readByTypeNotification($types);
        }


        $logs = $db->createCommand('SELECT * FROM `coin_logs` WHERE user_id=:user_id ORDER BY id DESC limit 6')->bindValues([":user_id" => Yii::$app->user->id])->queryAll();

        return $this->render('index',
            [
                'logs' => $logs
            ]
        );
    }


    public function actionAddCoin()
    {
        $this->layout = 'column3';

        return $this->render('add-coin',[

        ]);
    }

     public function actionPaymentRedirect()
     {
         $db = Yii::$app->db;
         $this->layout = false;
         $amount = 0;
         $order_id = 0;
         $method = '';
         if(!empty($_POST)){
            if($_POST["method"]=='code'){
                $method = 'code';
            }elseif($_POST["method"]=='account'){
                $method = 'account';
                $amount  = $_POST["amount"];
            }

            if($method=='code' OR ($method=='account' and intval($amount)>0)){
                $db->createCommand('INSERT INTO `transactions` SET user_id=:user_id,amount=:amount,payment_method=:payment_method,payment_service=:payment_service,date=:date,payment_status=0')->bindValues([":user_id"=>Yii::$app->user->id,":amount"=>$amount,":date"=>date("Y-m-d H:i:s"),":payment_method" => $method,":payment_service" => "alochat_portmanat"])->execute();
                $order_id = $db->lastInsertID;
            }

         }
         return $this->render('payment-redirect',[
             'method' => $method,
             'amount' => $amount,
             'order_id' => $order_id
         ]);
     }


    public function actionChangeNick()
    {
        $this->layout = 'column3';
        $form = new NickChangeForm();
        $form->nickname = Yii::$app->user->identity->nickname;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if(Yii::$app->user->identity->coins>=Yii::$app->params["changeNicknameCoin"]){
                if($form->updateNick()){
                    Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues([":user_id" => Yii::$app->user->id,":coins" => Yii::$app->params["changeNicknameCoin"],":type"=>1,":text" => CoinLogs::LOG_CHANGE_NICK,":date"=>date("Y-m-d H:i:s")])->execute();

                    Yii::$app->session->setFlash('success', Yii::t('app', 'Your nick successfully changed.'));
                }

            }else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Balansiınızda kifayət qədər bal yoxdur. Loqin dəyişmə əməliyyatının icrası üçün balansınızda ən az {coin} olmalidir',["coin" => Yii::$app->params["changeNicknameCoin"]]));

            }

        }
        return $this->render('change-nick',[
            'form' => $form
        ]);
    }

    public function actionSendCoin()
    {

         $this->layout = 'column3';
        $form = new SendCoinForm();
        $maxCoin = round((Yii::$app->user->identity->coins*80)/100);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if(intval($form->coin)>0){
                $coin = intval($form->coin);
                $coin = round($coin*100/80);
                if(Yii::$app->user->identity->coins>=$coin){
                    if($user2 = User::findByNickname($form->nickname)!=NULL){
                        if($form->sendCoin()){
                            Yii::$app->session->setFlash('success', Yii::t('app', '{coin} coins have been sent to {nick}.',["coin" => intval($form->coin),"nick"=>$form->nickname]));

                        }else{
                            Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred.'));
                        }
                    }else{
                        Yii::$app->session->setFlash('error', Yii::t('app', 'User not found'));
                    }

                }else {

                    Yii::$app->session->setFlash('error', Yii::t('app', 'There are enough points balance. You can send maximum {coin}',["coin" => $maxCoin]));

                }
            }else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Göndərmək istdiyiniz bal miqdarı 0-dan yuxarı olmalıdır'));

            }

        }

        return $this->render('send-coin',[
            'form' => $form,
            'maxCoin' => $maxCoin
        ]);
    }


    public function actionSetVip()
    {

        $this->layout = 'column3';

        return $this->render('set-vip');
    }

    public function  actionDeleteNick()
    {
        $db = Yii::$app->db;
        $sc = array_key_exists('sc', $_GET) ? trim($_GET['sc']) : null;

        if (!empty($sc))
        {
            $original_sc = md5(md5("delete-nick".Yii::$app->user->id.Yii::$app->user->identity->nickname.Yii::$app->user->id."delete-nick"));
            if($original_sc==$sc){
                  if(Yii::$app->user->identity->coins>=Yii::$app->params["deleteNicknameCoin"]){
                      $coins = Yii::$app->user->identity->coins - Yii::$app->params["deleteNicknameCoin"];
                      $db->createCommand('UPDATE `user` SET deactive=1,coins=:coins WHERE id=:user_id limit 1')->bindValues([":user_id" => Yii::$app->user->id,':coins' => $coins])->execute();
                      Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues([":user_id" => Yii::$app->user->id,":coins" => Yii::$app->params["deleteNicknameCoin"],":type"=>1,":text" => CoinLogs::LOG_DELETE_NICK,":date"=>date("Y-m-d H:i:s")])->execute();

                      Yii::$app->user->logout();

                      return $this->goHome();
                   }else {
                      Yii::$app->session->setFlash('error', Yii::t('app', 'Balansınızda kifayət qədər bal yoxdur. İstifadeci silmə əməliyyatı üçün balansınızda {coin} olmalıdır',["coin" => Yii::$app->params["deleteNicknameCoin"]]));
                  }

            }

        }
        $this->layout  = 'column3';
        return $this->render('delete-nick');
    }

    public function actionOnlineRating()
    {
        $this->layout = 'column3';
        $db = Yii::$app->db;
        $form = new OnlineRatingForm();
        $pointResult = Yii::$app->user->identity->point;
        $count_users = $db->createCommand("SELECT COUNT(`id`) FROM `user` WHERE `point` > :point AND `last_activity`>:online_time")->bindValues([":point" => $pointResult,":online_time" =>  (time() - Yii::$app->params['userOnlineStatusCheckTime'])])->queryScalar();
        $place=$count_users+1;


        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $coin = $form->point;

            $maxCoin = Yii::$app->user->identity->coins;
             if(Yii::$app->user->identity->coins>=$coin){
                 if($form->addPoint()){
                     $pointResult = $coin + Yii::$app->user->identity->point;
                     $count_users = $db->createCommand("SELECT COUNT(`id`) FROM `user` WHERE `point` > :point AND `last_activity`>:online_time")->bindValues([":point" => $pointResult,":online_time" =>  (time() - Yii::$app->params['userOnlineStatusCheckTime'])])->queryScalar();
                     $place=$count_users+1;
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Xalınız artırıldı! Xallarınızın sayı {point} Loqininizin onlayndakı mövqeyi: {place} .',["point" => $form->point,"place"=>$place]));
                }else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred.'));
                }

            }else {

                Yii::$app->session->setFlash('error', Yii::t('app', 'Balansinizda kifayət qədər bal yoxdur.'));

            }
        }
        return $this->render('online-rating',[
            'form' => $form,
            'place' => $place
         ]);
    }




}
