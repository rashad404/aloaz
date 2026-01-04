<?php
namespace frontend\models;


use Yii;
use yii\base\Model;

class VerifyPhoneForm extends  Model
{
    public $phone;
    public $phone_prefix;
    public $code;


    public function rules()
    {
        return [
            ['phone','filter','filter' => 'trim'],
            ['phone','required'],
            //['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app','This phone number has already been taken.')],
            ['phone','validatePhone'],


            ['phone','string'],
            ['code','string','min' => 4, 'max' => 6]
        ];
    }



    public function validatePhone($attribute)
    {
        $phone = $this->$attribute;
        if(Yii::$app->user->identity->phone != $phone and Yii::$app->db->createCommand('SELECT count(id) FROM `user` WHERE phone=:phone')->bindValue(':phone',$phone)->queryScalar()>0){
            $this->addError($attribute, Yii::t('app','This phone number has already been takenq.'));

        }


    }

    public static function generateCode($length = 4)
    {
        $str = '123456789abcdefkmn';
        $max = strlen($str);
        $length = @round($length);
        if (empty($length)) {
            $length = rand(8, 12);
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $str{rand(0, $max - 1)};
        }
        return $password;
    }


    public function attributeLabels()
    {
        return [
            'phone' => 'Telefon',
            'code'  => 'Kod',
        ];
    }

    public static function sendsmsMetbuat($phone,$sms_text){
        $p_id = 3;            // Your Partner id
        $p_login = "metbuatazsmsgen"; // Your Partner Login
        $p_pass= "5BvMt9cdCDc8";  // Your Partner Pass
        $data = array("p_id"=>$p_id,"p_login"=>$p_login,"p_pass"=>$p_pass,"phone"=>$phone,"sms_text"=>$sms_text);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://smsgen.net/api/smsapi.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query($data));


        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);
        return json_decode($server_output);
    }

    public static function sendsms($msisdn, $smstext){
        $url = 'http://infomob.az/tools/sendsms/sms.php';
        $postData = "msisdn=$msisdn&smstext=$smstext&shortnum=9136&from=alo.az&key=".md5("$msisdn-Xp4E2cKoz0E")."";

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $output = curl_exec($ch);
        curl_close($ch);

        if(intval($output) == 1) return true; else return false;

    }


}