<?php

namespace frontend\controllers;

 use common\models\User;
 use common\models\UserImage;
 use common\models\UserImageResized;
 use common\models\UserImageThumb;
 use Yii;
 use yii\helpers\BaseFileHelper;
 use yii\helpers\Url;

class AloController extends \yii\web\Controller
{
    protected function SqlInjectFilter($str) {
        $str = str_replace(" ",'',$str);
        // $str = mysql_real_escape_string($str);
        $str = str_replace("\n",'',$str);
        $str = str_replace("\t",'',$str);
        $str = str_replace("\r",'',$str);
        $str = str_replace("\0",'',$str);
        $str = str_replace("\x0B",'',$str);
        $str = str_replace("'",'',$str);
        $str = str_replace('"','',$str);
        $str = str_replace('\\','',$str);
        $str = str_replace('/','',$str);
        $str = str_ireplace (" and ","",$str);
        $str = str_ireplace ("execute ","",$str);
        $str = str_ireplace ("update ","",$str);
        $str = str_ireplace ("count ","",$str);
        $str = str_ireplace ("chr ","",$str);
        $str = str_ireplace ("mid ","",$str);
        $str = str_ireplace ("master ","",$str);
        $str = str_ireplace ("truncate ","",$str);
        $str = str_ireplace ("char ","",$str);
        $str = str_ireplace ("declare ","",$str);
        $str = str_replace ("select ","",$str);
        $str = str_ireplace ("create ","",$str);
        $str = str_ireplace ("delete ","",$str);
        $str = str_ireplace ("insert ","",$str);
        $str = str_ireplace ("union ","",$str);
        $str = str_replace ("\"","",$str);
        $str = str_replace ('"',"",$str);
        //$str = str_replace (" ","",$str);
        $str = str_replace ("$","",$str);
        $str = str_ireplace ("or ","",$str);
        $str = str_replace ("=","",$str);
        $str = str_replace ("% 20 ","",$str);
        $str = addslashes($str);
        return $str;
    }

    public function actionLoginAlo($id)
    {
        //alochat.com/site/login-alo/1129446?security_code=3000c09bb65b09fa4413cec1dd2d36fc
        $this->layout = false;
        $user = User::findOne($id);
        if($user){

            $randRow = Yii::$app->db->createCommand('SELECT * FROM user_logs WHERE user_id=:user_id and login_status=0 and login_time=0 order by id DESC')->bindValue(':user_id',$user->id)->queryOne();
            if($randRow){
                if(isset($_GET["sc"]) and trim($_GET["sc"])){

                    $code = $this->SqlInjectFilter($_GET["sc"]);

                    $original_code = md5(md5($randRow["rand"]."aloaz123".$user->id.$user->nickname.$user->password.$user->id."alochat456".$randRow["rand"]));
                    if($code == $original_code){
                        Yii::$app->user->login($user, 3600 * 24 * 30 );
                        Yii::$app->db->createCommand('UPDATE user_logs SET login_status=1,login_time=:login_time WHERE id=:log_id')->bindValues([":login_time" => time(), ":log_id" => $randRow["id"]])->execute();
                        return $this->redirect(Url::to(["/site/users"]));
                    }else{
                        echo "code duzgun deyil";
                        exit;
                    }
                }
            }else {
                echo "Bu link artiq istifade edilib";
                exit;            }

        }else {
            echo "axtardiginiz sehife tapilmadi";
            exit;
        }
    }


    public  function actionAutoLogin($key)
    {
        $this->layout = false;
        $key =  $this->SqlInjectFilter($key);
        $randRow = Yii::$app->db->createCommand('SELECT * FROM user_logs WHERE rand=:rand and key_status=0 order by id DESC')->bindValue(':rand',$key)->queryOne();
        if($randRow){
                $user = User::findOne($randRow["user_id"]);


                if($user){
                    Yii::$app->user->login($user, 3600 * 24 * 30 );
                    Yii::$app->db->createCommand('UPDATE user_logs SET login_status=1,login_time=:login_time WHERE id=:log_id')->bindValues([":login_time" => time(), ":log_id" => $randRow["id"]])->execute();
                    return $this->redirect(Url::to(["/site/users"]));
                }else{
                    echo "user tapilmadi";
                    exit;
                }
        }else {
            echo "Bele sehife tapilmadi";
            exit;
        }



    }
    public function actionAloImage($id)
    {
        $this->layout = false;

        $db = \Yii::$app->db;
        $id =intval($id);
        $webRoot = \Yii::getAlias('@webroot');
        $imageIndex = rand(1000,9999);

        $user = $db->createCommand("SELECT id,changed_photo_url,sex,nickname FROM `user` WHERE changed_photo=1 and  changed_photo_url!='' and id=:id order by id desc limit 10")->bindValue(':id',$id)->queryOne();
        $profile_photo = explode('|',$user["changed_photo_url"]);
        if($user['sex']==0) $s_path = 0;  elseif($user['sex']==1) $s_path = 1;

        $userId = $user["id"];
        /* echo '<img src="http://m.alo.az/photos/files/'.$s_path.'/'.$profile_photo[0].'">'; */
        $userImage = new UserImage();
        $thumb = new UserImageThumb();

        $userImage->user_id = $userId;
        $path = 'images/user/'.$userId.'/';
        $filePath = 'images/user/'.$userId.'/'.$userId."_".$imageIndex.".jpg";
        BaseFileHelper::createDirectory($path,0777,false);
        $userImage->path = '/images/user/'.$userId.'/'.$userId."_".$imageIndex.".jpg";
        $userImage->add_date = time();
        $file = file_get_contents('http://m.alo.az/photos/files/'.$s_path.'/'.$profile_photo[0].'');
        if($file) {  // eger file varsa


            file_put_contents($filePath, $file);
            $userImage->save(false);

            //THUMB
            $thumbsDir = $webRoot . '/images/user/' . $userId . '/thumbs/';
            if (!is_dir($thumbsDir)) {
                mkdir($thumbsDir, 0777, true);
            }
            $thumbsDir = $thumbsDir . '/';
            $image_t = $thumbsDir . $userId . '_' . $imageIndex . ".jpg";
            if (UserImage::resizeImageAndSave($webRoot . $userImage->path, $image_t, 120, 120)) {
                $thumb = new UserImageThumb();
                $thumb->user_id = $userId;
                $thumb->path = '/images/user/' . $userId . '/thumbs/' . $userId . '_' . $imageIndex . ".jpg";
                $thumb->add_date = time();
                $thumb->id = $userImage->primaryKey;
                $thumb->save();
            }

            //RESIZED
            $resizedImagesDir = $webRoot . '/images/user/' . $userId . '/resized/';
            if (!is_dir($resizedImagesDir)) {
                mkdir($resizedImagesDir, 0777, true);
            }
            $resizedImagesDir = $resizedImagesDir . '/';
            $image_r = $resizedImagesDir . $userId . '_' . $imageIndex . ".jpg";

            if (UserImage::resizeImageAndSave($webRoot . $userImage->path, $image_r, 320) && $thumb->id) {
                $resized = new UserImageResized();
                $resized->user_id = $userId;
                $resized->path = '/images/user/' . $userId . '/resized/' . $userId . '_' . $imageIndex . ".jpg";
                $resized->add_date = time();
                $resized->id = $userImage->primaryKey;
                $resized->save();
            }
        }
        $db->createCommand("UPDATE `user` SET profile_photo='".$thumb->path."',profile_photo_id='".$userImage->id."',changed_photo=0 where id=".$userId)->execute();

    }


    public function actionChangePass($id)
    {
        $db = \Yii::$app->db;

        $isset = $db->createCommand('SELECT count(id) FROM `user` WHERE id=:id and changed_pass=1')->bindValue(':id',$id)->queryScalar();
        if($isset > 0){
            $row = $db->createCommand('SELECT id,password FROM `user` WHERE id=:id and changed_pass=1')->bindValue(':id',$id)->queryOne();
            $passwordHash = \Yii::$app->security->generatePasswordHash($row["password"]);
            $db->createCommand('UPDATE `user` SET password_hash=:hash,changed_pass=0 WHERE id=:id')->bindValues([":hash" => $passwordHash,":id"=>$row["id"]])->execute();
            echo "id: ".$row["id"];

        }
    }





}
