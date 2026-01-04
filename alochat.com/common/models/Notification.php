<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_id_from
 * @property string $username
 * @property string $image
 * @property integer $type
 * @property integer $share_id
 * @property integer $coin
 * @property integer $read
 * @property integer $time
 */
class Notification extends \yii\db\ActiveRecord
{

    const  NOT_ALOCHAT_COIN = 1;
    const  NOT_USER_COIN = 2;
    const  NOT_SHARE_LIKE = 3;
    const  NOT_SHARE_COMMENT = 4;
    const  NOT_USER_VISIT = 5;
    const  NOT_USER_LIKE = 6;
    const  NOT_USER_GIFT = 7;
    const  NOT_USER_FRIEND = 8;
    const  NOT_USER_FRIEND_REQUEST_CONFIRM = 9;
    const  NOT_USER_FRIEND_REQUEST_REMOVE = 10;
    const  NOT_USER_BLOCK = 11;
    const  NOT_IMAGE_LIKE = 12;
    const  NOT_IMAGE_COMMENT = 13;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'user_id_from', 'type', 'share_id', 'coin', 'read','time'], 'integer'],
            [['username'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_id_from' => Yii::t('app', 'User Id From'),
            'username' => Yii::t('app', 'Username'),
            'type' => Yii::t('app', 'Type'),
            'share_id' => Yii::t('app', 'Share ID'),
            'coin' => Yii::t('app', 'Coin'),
            'read' => Yii::t('app', 'Read'),
        ];
    }


    public static function setNotification($user_id,$type,$time=0,$user_id_from=1,$username='Alochat',$coin=0,$share_id=0,$read=0,$image='')
    {

        if($image == ''){
            if($user_id_from == Yii::$app->user->id){
                $image = Yii::$app->user->identity->profile_photo;
            }
            $user = Yii::$app->db->createCommand('SELECT * FROM `user` WHERE id=:id ')->bindValue(':id' , $user_id_from)->queryOne();
            if($user){
                $image = $user['profile_photo']?Url::base() . $user['profile_photo'] : Url::base() . Yii::$app->params['defaultProfilePicture_'.$user['sex']];
            }else{
                $image = '/images/icons/sample.png';
            }
        }
        $insert = Yii::$app->db
            ->createCommand('INSERT INTO notification SET user_id=:user_id,user_id_from=:user_id_from,coin=:coin,`type`=:type,username=:username,share_id=:share_id,`read`=:read,`time`=:time,`image`=:image')
            ->bindValues([
                ":user_id" => $user_id,
                ":type" => $type,
                ":user_id_from" => $user_id_from,
                ":coin" => $coin,
                ":share_id" => $share_id,
                ":username" => $username,
                ":read" => $read,
                ":time" => $time,
                ":image" => $image,
            ])
            ->execute();

        if($insert){
            return true;
        }else{
            return false;
        }
    }




    public static function getNewNotificationCount($user_id)
    {
        $count  = 0;
        $db = Yii::$app->db;
        $res  = $db->createCommand('SELECT count(id) FROM notification WHERE user_id=:user_id and `read`=0')
            ->bindValues([
                ":user_id" => $user_id
            ])
            ->queryScalar();
        if(intval($res)>0) {
            $count = intval($res);
        }
            return $count;

    }



    public static function getNewNotificationText($user_id)
    {
        $db = Yii::$app->db;
        $newNotificationText  = '';
        $notifications = $db->createCommand('SELECT * FROM notification WHERE user_id=:user_id and `read`=0 ORDER BY `time` DESC limit 5')
            ->bindValues([":user_id" => $user_id])
            ->queryAll();

        if($notifications){
            $newNotificationText .= '<li class="dropdown-header">Bildirişlər</li>';
            foreach($notifications as $notification){

                $text = '';
                $link = '';

                if($notification["type"]==self::NOT_ALOCHAT_COIN){
                    $text = 'Alochat sizə bal hədiyyə etdi';
                    $link = Url::to(["/coins"."?ref=notification"]);
                }elseif($notification["type"] == self::NOT_SHARE_COMMENT){
                    $text = 'Sizin paylaşıma rəy bildirdi';
                    $link = Url::to(["/profile/post/".$notification["share_id"]."?ref=notification"]);

                }elseif($notification["type"] == self::NOT_SHARE_LIKE){
                    $text = 'Sizin paylaşımı bəyəndi';
                    $link = Url::to(["/profile/post/".$notification["share_id"]."?ref=notification"]);

                }elseif($notification["type"] == self::NOT_USER_COIN){
                    $text =' sizə '.$notification["coin"]."bal hədiyyə etdi";
                    $link = Url::to(["/coins"."?ref=notification"]);

                }elseif($notification["type"] == self::NOT_USER_FRIEND){
                    $text = ' sizə dostluq göndərdi';
                    $link = Url::to(["/profile/friend"."?ref=notification"]);


                }elseif($notification["type"] == self::NOT_USER_FRIEND_REQUEST_CONFIRM){
                    $text = ' sizin dostluq təklifinizi qəbul etdi';
                    $link = Url::to(["/u/".$notification["user_id_from"]."?ref=notification"]);

                }elseif($notification["type"] == self::NOT_USER_FRIEND_REQUEST_REMOVE){
                    $text = ' sizin dostluq sorğunuzu sildi';
                    $link = Url::to(["/u/".$notification["user_id_from"]."?ref=notification"]);


                }elseif($notification["type"] == self::NOT_USER_GIFT){
                    $text = ' sizə hədiyyə göndərdi';
                    $link = Url::to(["/gift/".$notification["user_id"]."?ref=notification"]);


                }elseif($notification["type"] == self::NOT_USER_LIKE){
                    $text = ' sizin profili bəyəndi';
                    $link = Url::to(["/profile/like"."?ref=notification"]);

                }elseif($notification["type"] == self::NOT_USER_VISIT){
                    $text = ' sizin profili ziyarət etdi';
                    $link = Url::to(["/profile/visitors"."?ref=notification"]);

                }elseif($notification["type"] == self::NOT_IMAGE_LIKE){
                    $text = ' sizin şəkili bəyəndi';
                    $link = Url::to(["/profile/image/".$notification["share_id"]."?ref=notification"]);

                }elseif($notification["type"] == self::NOT_IMAGE_COMMENT){
                    $text = ' sizin şəkilə  rəy bildirdi';
                    $link = Url::to(["/profile/image/".$notification["share_id"]."?ref=notification"]);

                }
                $time = date("d-m-Y H:i",$notification["time"]);
                $image  = $notification["image"];

                $newNotificationText .= '
                            <li class="media">
                                <a href="'.$link.'">
                                    <div class="media-left"><img src="'.$image.'" class="media-object" alt=""></div>
                                    <div class="notification-body">

                                        <h6 class="media-heading">'.$notification["username"].'</h6>
                                        <p>'.$text.'</p>
                                        <div class="text-muted f-s-11">'.$time.'</div>
                                    </div>
                                </a>
                            </li>  ';
            }

            $newNotificationText .= '<li class="dropdown-footer text-center">
                                <a href="'.Url::to(["/site/notifications"]).'">Bütün bildirişlərə bax</a>
                            </li>';
        }else{
            $newNotificationText.= '
            <li class="dropdown-header">Yeni bildiriş yoxdur</li>
                            <li class="dropdown-footer text-center">
                                <a href="'.Url::to(["/site/notifications"]).'">Bütün bildirişlərə bax</a>
                            </li>';
        }

        return $newNotificationText;

    }


    public static function readByTypeNotification($types,$share_id=0,$user_id_from=0)
    {
        $user_id = Yii::$app->user->id;
        $count  = 0;

        $db = Yii::$app->db;

        $types = "(".implode(',',$types).")";

        $where = ' ';

        if(intval($share_id)>0){
            $where .= " and share_id=".$share_id;
        }

        if(intval($user_id_from)>0){
            $where .= " and user_id_from=".$user_id_from;
        }

        $res  = $db->createCommand('SELECT count(id) FROM notification WHERE user_id=:user_id and (`read`=0 or `read`=1) and `type` in '.$types.$where)
            ->bindValues([
                ":user_id" => $user_id,
            ])
            ->queryScalar();


        if(intval($res)>0) {
            $count = intval($res);
            $update  = $db->createCommand('UPDATE notification SET `read`=2 WHERE user_id=:user_id and (`read`=0 or `read`=1) and `type` in '.$types.$where)
                ->bindValues([
                    ":user_id" => $user_id,
                ])
                ->execute();
            if($update){
                $count  = 0;
            }
        }

        return $count;
    }


    public static function readNotification($user_id)
    {
        $count  = 0;

        $db = Yii::$app->db;

        $res  = $db->createCommand('SELECT count(id) FROM notification WHERE user_id=:user_id and `read`=0')
            ->bindValues([
                ":user_id" => $user_id
            ])
            ->queryScalar();
        if(intval($res)>0) {
            $count = intval($res);
            $update  = $db->createCommand('UPDATE   notification SET `read`=1 WHERE user_id=:user_id and `read`=0')
                ->bindValues([
                    ":user_id" => $user_id
                ])
                ->execute();
            if($update){
                $count  = 0;
            }
        }

        return $count;
    }


    public static function SqlInjectFilter($str)
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


}
