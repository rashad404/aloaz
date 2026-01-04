<?php

namespace frontend\controllers;

use common\models\CoinLogs;
use common\models\Conversation;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\models\User;

class ApiController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    private $p_id;        // Partnyor ID
    private $s_id;        // Xidmət ID
    private $key;        // Xidmətin şifrəsi
    private $o_id;        // Order ID
    private $tr_id;        // Tranzaksiya ID
    private $method;    // Metod (account və ya code)
    private $amount;    // Məbləğ
    private $test;        // Xidmət rejimin statusu
    private $hash;        // Məlumatların şifrələnmiş adı

    private $point_login = 'infomobpointsubscription';
    private $point_pass = 'infomobpointsubscription';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function actionInfomob()
    {
        $this->layout = false;
        /** Qisa nomreler ve tarifleri:
         ** 9136 – 0,50 azn
         ** 9142 – 1,00 azn
         ** 9143 - 2,00 azn
         ** 9148 – 5,00 azn
         **/
        $db = Yii::$app->db;
        $key = ($_POST["key"]); // security
        $id = intval($_POST["id"]);
        $shortnumber = intval($_POST["shortnumber"]);
        $smstext = ($_POST["smstext"]); //security

        if ($key != "c2b6c02a593bfcb10a70de10c5d39dcf") {
            echo "key yoxdur";
            exit;
        } // Tehlukesizlik ucun vacibdir. Mezmun.az -da qeyd etdiyiniz key burdaki ile eyni olmalidir.
        if (!empty($smstext) && $id == 0) {
            $exp = explode("-", $smstext);
            $id = $exp[1];
            $id = preg_replace("/[^0-9]/", "", $id);
        }

        $bal_array = array(
            "9136" => "20",    //10 bali deyise bilersiniz
            "9142" => "50",    //25 bali deyise bilersiniz
            "9143" => "120",    //60 bali deyise bilersiniz
            "9148" => "300");    //150 bali deyise bilersiniz
        $bal = $bal_array[$shortnumber];
        if ($bal < 1) {
            echo "bal yoxdiur";
            exit;
        }
        if ($id < 1) {
            echo "id yoxdur";
            exit;
        }

        /*if($subs > 1){
            $bal_bonus = $bal + ($bal*20/100)*($subs-1);
            if($bal_bonus > $bal*2) $bal_bonus = $bal*2;
            $bal = intval($bal_bonus);
        }*/

        // bu sorgunu oz skriptinize uygunlasdirmalisiniz

        $update = $db->createCommand('UPDATE `user` SET `coins` = `coins` + :bal, `all_coins` = `all_coins` + :bal WHERE `id`= :id LIMIT 1')->bindValues([":bal" => $bal, 'id' => $id])->execute();
        if ($update) {
            echo $bal . " bal hesabiniza yuklenildi. Tesekkur edirik!";
            $text = "smsle_bal_almaq";
            $db->createCommand('INSERT INTO `coin_logs` SET user_id=:id,`type`=2,`coins`=:bal,`text`=:text')->bindValues([":id" => $id, ':bal' => $bal, ":text" => $text])->execute();
        } else {
            echo "Xeta bas verdi";
        }
        exit;
    }


    public function actionPortmanat()
    {
        $manat_coin = 50;
        $db = Yii::$app->db;
        if (!empty($_POST)) {
            $this->p_id = 10575;        //partnyor ID-ni bura yazın
            $this->s_id = 11866;        //xidmət ID-ni bura yazın
            $this->key = 'a6x5wpc41f';    //xidmət şifrəsini bura yazın
            $this->o_id = $_POST['o_id'];
            $this->tr_id = $_POST['transaction'];
            $this->method = $_POST['method'];
            $this->amount = $_POST['amount'];
            $this->test = $_POST['test'];
            $this->hash = $_POST['hash'];

            $coins = round($this->amount) * 50;

            $hash = strtoupper(md5($this->p_id . $this->s_id . $this->o_id . $this->tr_id . $this->key));
            $transaction = $db->createCommand('SELECT * FROM `transactions` WHERE payment_status=:payment_status and id=:id')->bindValues([":id" => $this->o_id, ":payment_status" => 0])->queryOne();
            $user = \common\models\User::findOne($transaction["user_id"]);
            if ($hash == $this->hash) //Əgər dogrudursa 1, yoxsa 0 qaytarılmalı
            {
                if ($this->test == '0')//Əgər test rejimi söndürülübsə
                {
                     if($transaction["amount"]==0 or $transaction["amount"]==$this->amount){
                        $amount = $this->amount;
                    }

                    //Baza əməliyyatlarını burada qeyd edin.
                    //(Məsələn: Hesab artirılsın, bal yüklənsin və s.)
                    Yii::$app->db->createCommand('UPDATE `transactions` SET payment_status=1,`amount`=:amount,update_date=:date,coins=:coins where id=' . $this->o_id . ' limit 1')->bindValues([":coins" => $coins,":amount" => $amount ,":date" => date("Y-m-d H:i:s")])->execute();
                    Yii::$app->db->createCommand('INSERT INTO coin_logs SET user_id=:user_id,coins=:coins,`type`=:type,text=:text,`date`=:date')->bindValues([":user_id" => $transaction["user_id"], ":coins" => $coins, ":type" => 2, ":text" => CoinLogs::LOG_BUY_COIN_PORTMANAT, ":date" => date("Y-m-d H:i:s")])->execute();
                    $user->coins = $user->coins + $coins;
                    $user->all_coins = $user->all_coins + $coins;
                    $user->save(false);
                }
                echo '1';
            } else {
                Yii::$app->db->createCommand('UPDATE `transactions` SET payment_status=2,update_date=:date where id=' . $this->o_id)->bindValues([":date" => date("Y-m-d H:i:s")])->execute();

                echo '0';
            }
        }

    }


    public function actionPortmanatSuccess()
    {
        Yii::$app->session->setFlash('success', 'Ödəməniz uğurla tamamlandı');
        return $this->redirect(Url::to(["/coins"]));
    }

    public function actionPortmanatFailed()
    {
        Yii::$app->session->setFlash('success', 'Xəta baş verdi');
        return $this->redirect(Url::to(["/coins"]));
    }

    protected function SqlInjectFilter($str)
    {
        $str = str_replace(" ", '', $str);
        // $str = mysql_real_escape_string($str);
        $str = str_replace("\n", '', $str);
        $str = str_replace("\t", '', $str);
        $str = str_replace("\r", '', $str);
        $str = str_replace("\0", '', $str);
        $str = str_replace("\x0B", '', $str);
        $str = str_replace("'", '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_ireplace(" and ", "", $str);
        $str = str_ireplace("execute ", "", $str);
        $str = str_ireplace("update ", "", $str);
        $str = str_ireplace("count ", "", $str);
        $str = str_ireplace("chr ", "", $str);
        $str = str_ireplace("mid ", "", $str);
        $str = str_ireplace("master ", "", $str);
        $str = str_ireplace("truncate ", "", $str);
        $str = str_ireplace("char ", "", $str);
        $str = str_ireplace("declare ", "", $str);
        $str = str_replace("select ", "", $str);
        $str = str_ireplace("create ", "", $str);
        $str = str_ireplace("delete ", "", $str);
        $str = str_ireplace("insert ", "", $str);
        $str = str_ireplace("union ", "", $str);
        $str = str_replace("\"", "", $str);
        $str = str_replace('"', "", $str);
        //$str = str_replace (" ","",$str);
        $str = str_replace("$", "", $str);
        $str = str_ireplace("or ", "", $str);
        $str = str_replace("=", "", $str);
        $str = str_replace("% 20 ", "", $str);
        $str = addslashes($str);
        return $str;
    }


    public function actionPointSubscribe()
    {
        $this->layout = false;
        $db = Yii::$app->db;
        $user_key = $this->generatePassword(8);
        $text_coin = "infomob_add_coin";

        if ($_POST) {
            $point_login = $this->SqlInjectFilter($_POST["point_login"]);
            $point_pass = $this->SqlInjectFilter($_POST["point_pass"]);
            if ($point_login == $this->point_login and $point_pass == $this->point_pass) {
                $msisdn = $this->SqlInjectFilter($_POST["msisdn"]);
                $user = $db->createCommand('SELECT * FROM `user` WHERE `phone`=:phone')->bindValue(':phone', $msisdn)->queryOne();
                if ($user == null) {
                    $user = new User();
                    $user->full_name = $msisdn;
                    $user->nickname = $msisdn;
                    $user->phone = $msisdn;
                    $user->sex = 0;
                    $user->age = 18;
                    $user->password = User::generatePassword(8);
                    $user->role = User::ROLE_USER;
                    $user->last_post = '';
                    $user->about = '';
                    $user->ref = 'infomob';
                    $user->regfrom = 'web';
                    $user->status = User::STATUS_ACTIVE;
                    $user->verify = 1;
                    $user->coins = Yii::$app->params["infomobSubCoin"];
                    $user->all_coins = Yii::$app->params["infomobSubCoin"];

                    $ip = Yii::$app->ipgeobase->getIP();
                    $user->ip = $ip;

                    $geoData = Yii::$app->ipgeobase->getLocation($ip);

                    $countryId = 17;

                    $cityId = 0;

                    if ($countryId)
                        $user->country_id = $countryId;

                    if ($cityId && $geoData['city'] != '-')
                        $user->city_id = $cityId;

                    $user->setPassword($user->password);

                    $user->generateAuthKey();

                    if ($user->save(false)) {

                        $db->createCommand('INSERT INTO user_logs SET user_id=:user_id,rand=:rand,login_time=0,login_status=0,key_status=0')
                            ->bindValues([":user_id" => $user->id, ":rand" => $user_key])
                            ->execute();

                        $system_message = 'nick: ' . $user->nickname . "<br /> parol: " . $user->password;
                        Conversation::sendBySystemMessage($user->id, $system_message);

                        User::sendWelcomeMessage($user->id);

                        $db->createCommand('INSERT INTO `coin_logs` SET user_id=:id,`type`=2,`coins`=:coin,`text`=:text,`date`=:date')->bindValues([":id" => $user->id, ':coin' => $user->coins, ":text" => $text_coin, ":date" => date("Y-m-d H:i:s")])->execute();

                        $arr = ["response" => "success", "user_id" => $user->id, "user_key" => $user_key, "isset" => 0, "message" => "Qeydiyyat edildi", "user_nickname" => $user->nickname, "user_password" => $user->password];
                        print json_encode($arr);
                        // echo $user->id;
                    } else {
                        $arr = ["response" => "error", "message" => "Istifadeci yaradarken sehv bas verdi"];
                        print json_encode($arr);
                    }
                } else {

                    $db->createCommand('INSERT INTO user_logs SET user_id=:user_id,rand=:rand,login_time=0,login_status=0,key_status=0')
                        ->bindValues([":user_id" => $user["id"], ":rand" => $user_key])
                        ->execute();

                    $system_message = 'nick: ' . $user["nickname"] . "<br /> parol: " . $user["password"];
                    Conversation::sendBySystemMessage($user["id"], $system_message);


                    $coins = $user["coins"] + Yii::$app->params["infomobSubCoin"];
                    $all_coins = $user["all_coins"] + Yii::$app->params["infomobSubCoin"];
                    $db->createCommand('UPDATE `user` SET `coins` = :coins,`all_coins`=:all_coins WHERE id=:user_id')->bindValues([":coins" => $coins, ":all_coins" => $all_coins, ":user_id" => $user["id"]])->execute();

                    $db->createCommand('INSERT INTO `coin_logs` SET user_id=:id,`type`=2,`coins`=:coin,`text`=:text,`date`=:date')->bindValues([":id" => $user["id"], ':coin' => $coins, ":text" => $text_coin, ":date" => date("Y-m-d H:i:s")])->execute();


                    $arr = ["response" => "success", "user_id" => $user["id"], "user_key" => $user_key, "isset" => 1, "message" => "Bu istifadeci artiq movcuddur", "user_nickname" => $user["nickname"], "user_password" => $user["password"]];
                    print json_encode($arr);
                }
            } else {
                $arr = ["response" => "error", "message" => "user parol duzgun deyil"];
                print json_encode($arr);
            }
        } else {
            $arr = ["response" => "error", "message" => "Post yoxdur"];
            print json_encode($arr);
        }
    }

    public function actionAddPoint()
    {
        $this->layout = false;
        $db = Yii::$app->db;
        if ($_POST) {
            $point_login = $this->SqlInjectFilter($_POST["point_login"]);
            $point_pass = $this->SqlInjectFilter($_POST["point_pass"]);
            if ($point_login == $this->point_login and $point_pass == $this->point_pass) {
                 $id = intval($_POST["user_id"]);
                $user = $db->createCommand('SELECT * FROM `user` WHERE  `id`=:id')->bindValues([  ":id" => $id])->queryOne();
                if ($user != null) {
                    $coin = Yii::$app->params["infomobSubCoin"];
                    $new_coins = $user["coins"] + $coin;
                    $new_all_coins = $user["all_coins"] + $coin;
                    $update = $db->createCommand('UPDATE `user` SET coins=:coins,all_coins=:all_coins  WHERE id=:id limit 1')
                        ->bindValues([':coins' => $new_coins, ':all_coins' => $new_all_coins, ':id' => $user["id"]])
                        ->execute();
                    if ($update) {
                        $text = "infomob_add_coin";
                        $db->createCommand('INSERT INTO `coin_logs` SET user_id=:id,`type`=2,`coins`=:coin,`text`=:text,date=:date')->bindValues([":id" => $id, ':coin' => $coin, ":text" => $text,":date" => date("Y-m-d H:i:s")])->execute();
                        $system_message = 'Infomob vasitəsilə balansınıza '.$coin." bal artırıldi";
                        Conversation::sendBySystemMessage($user["id"], $system_message);

                        $arr = ["response" => "1", "message" => "Bal artırıldı"];
                        print json_encode($arr);
                    } else {
                        echo 0;
                        $arr = ["response" => "0", "message" => "Bal artırılmasında xəta baş verdi"];
                        print json_encode($arr);
                    }
                } else {
                     $arr = ["response" => "0", "message" => "İstifadəçi yoxdur"];
                    print json_encode($arr);
                }
            } else {
                $arr = ["response" => "0", "message" => "İnfomob user parol yoxdur"];
                print json_encode($arr);
            }
        } else {
            $arr = ["response" => "0", "message" => "post yoxdur"];
            print json_encode($arr);
        }
    }


    public function actionIssetUser()
    {
         $this->layout = false;
        $db = Yii::$app->db;
        if ($_POST) {
            $point_login = $this->SqlInjectFilter($_POST["point_login"]);
            $point_pass = $this->SqlInjectFilter($_POST["point_pass"]);

            if ($point_login == $this->point_login and $point_pass == $this->point_pass) {
                $user_id = intval($_POST["user_id"]);
                $user = $db->createCommand('SELECT * FROM `user` WHERE `id`=:user_id')->bindValues([':user_id'=> $user_id])->queryOne();
                if ($user == null) {
                    $arr = ["response" => 0];
                    print json_encode($arr);

                } else {
                    $arr = ["response" => 1];
                    print json_encode($arr);
                }
            } else {
                $arr = ["response" => 0];
                print json_encode($arr);
            }
        } else {
            $arr = ["response" => 0];
            print json_encode($arr);
        }
    }




    public static function generatePassword($length = '')
    {
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $max = strlen($str);
        $length = @round($length);
        if (empty($length)) {
            $length = rand(8, 12);
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $str{rand(0, $max - 1)};
        }

        $log = Yii::$app->db->createCommand('SELECT count(id) FROM `user_logs` WHERE rand=:rand limit 1')->bindValue(":rand",$password)->queryScalar();

        if($log>0) {
            return  self::generatePassword(8);
        }elseif($log==0){
            return $password;

        }

    }
}
