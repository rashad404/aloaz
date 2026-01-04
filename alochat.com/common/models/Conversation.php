<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use common\models\UserBlock;

/**
 * This is the model class for table "conversation".
 *
 * @property integer $id
 * @property integer $user_one
 * @property integer $user_two
 * @property integer $status
 * @property integer $by_system
 * @property integer $deleted_by
 * @property integer $blocked
 * @property integer $level
 * @property string $last_reply
 * @property integer $last_time
 * @property integer $create_time
 * @property integer $not_read_one
 * @property integer $not_read_two

 */
class Conversation extends \yii\db\ActiveRecord
{
    const BY_SYSTEM_STATUS = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conversation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_one', 'user_two'], 'required'],
            [['user_one', 'user_two', 'status', 'deleted_by','by_system','blocked','level'], 'integer'],
            [['last_reply','last_time','not_read_one','not_read_two','create_time'], 'safe'],
            [['user_one', 'user_two'], 'unique', 'targetAttribute' => ['user_one', 'user_two'], 'message' => 'The combination of User One and User Two has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_one' => Yii::t('app', 'User One'),
            'user_two' => Yii::t('app', 'User Two'),
            'last_reply' => Yii::t('app', 'Last Reply'),
            'last_time' => Yii::t('app', 'Last message time'),
            'status' => Yii::t('app', 'Status'),
            'not_read_one' => Yii::t('app', 'Not read one'),
            'not_read_two' => Yii::t('app', 'Not read two'),
            'create_time' => Yii::t('app', 'Create time'),
        ];
    }

    public function getMessages()
    {

        return $this->hasMany(ConversationReply::className(), ['conversation_id' => 'id']);
    }

    public static function checkConversationExist($user_one, $user_two)
    {

        $conversation = Conversation::find()->
        where(['user_one' => $user_one, 'user_two' => $user_two])
            ->orWhere(['user_two' => $user_one, 'user_one' => $user_two])
            ->one();
        return $conversation;
    }

    public static function checkConversationUser($id, $user)
    {
        $user = intval($user);
        $conversation = Conversation::find()->
        where("id=$id and (user_one=$user or user_two=$user)")
            ->one();
        return $conversation ? true : false;
    }

    public static function getOtherUser($id){
        $user = Yii::$app->user->id;
        $otherUser = false;
        $conversation = Conversation::find()->
        where("id=$id and (user_one=$user or user_two=$user)")
            ->one();
        if($conversation){
            if($conversation->user_one == Yii::$app->user->id){
                $otherUserId = $conversation->user_two;
            } else {
                $otherUserId = $conversation->user_one;
            }

            $otherUser = User::findOne($otherUserId);
        }

        return $otherUser;
    }


    public static function getAllConversations($userId, $offset = 0, $limit = 20)
    {
        $userId = intval($userId);
        $offset = intval($offset);
        $limit = intval($limit);

        $limitSubQuery = '';

        if ($limit)
            $limitSubQuery = "Limit $limit  OFFSET $offset";

        $conversations = [];

        $conversationList = Conversation::findBySql("
             SELECT u.id as user_id,c.id as conversation_id,LEFT(u.full_name, 30) as full_name,u.last_activity,u.profile_photo,u.sex,c.last_time
                 FROM conversation c, user u
             WHERE
                 CASE
                     WHEN c.user_one = $userId
                     THEN c.user_two = u.id
                     WHEN c.user_two = $userId
                     THEN c.user_one= u.id
                 END
             AND (c.user_one=$userId OR c.user_two=$userId) AND (c.deleted_by!=$userId)

             Order by c.last_time DESC,c.id DESC

             " . $limitSubQuery)
            ->asArray()
            ->all();


        foreach ($conversationList as $item) {

            $reply = ConversationReply::findBySql("
              SELECT (
                    select count(id)
                    from conversation_reply R1
                    where R1.read=0 and R1.deleted_by!=$userId and R1.user_id!=$userId and  R1.conversation_id=". $item['conversation_id'].") as new_message_count,
               R.id,R.time,LEFT(R.reply, 50) as reply,R.read,R.user_id
                FROM conversation_reply R
              WHERE
                R.conversation_id='" . $item['conversation_id'] . "' and R.deleted_by!=$userId
              ORDER BY R.id DESC
              LIMIT 1

             ")->asArray()->one();


            if ($item['last_activity'] > (time() - Yii::$app->params['userOnlineStatusCheckTime']))
                $item['userOnline'] = true;
            else
                $item['userOnline'] = false;

            if (!$item['profile_photo'])
                $item['profile_photo'] = Yii::$app->params['defaultProfilePicture_'.$item['sex']];


            //$item['profile_photo'] = Url::base(). $item['profile_photo'];

            $item['time'] = $reply['time'];

            if (isset($reply['new_message_count']))
                $item['new_message_count'] = $reply['new_message_count'];
            else
                $item['new_message_count'] = 0;

            $item['id'] = $reply['id'];

            $item['reply'] = $reply['reply'];

            if ($reply && $reply['user_id'] != $userId && $reply['read'] == 0) {

                $item['read'] = 0;

            } else {

                $item['read'] = 1;
            }
            $conversations[] = $item;
        }

        self::array_sort_by_column($conversations, 'id', SORT_DESC);

        return $conversations;
    }

    public static function getAllConversations2($userId, $offset = 0, $limit = 20, $p = 0)
    {
        $userId = intval($userId);
        $offset = intval($offset);
        $limit = intval($limit);

        $limitSubQuery = '';

        if ($limit)
            $limitSubQuery = "Limit $limit  OFFSET $offset";

        $conversations = [];

        $where = '';
        if($p == 1){
            $where =" ((c.user_one=$userId and c.not_read_one>0) OR (c.user_two=$userId and c.not_read_two>0))";
        }else {
            $where = " (c.user_one=$userId OR c.user_two=$userId)";
        }

        $conversationList = Yii::$app->db->createCommand(
            "SELECT c.id as conversation_id,c.last_time,c.user_one,c.user_two,c.last_reply,c.not_read_one,c.not_read_two
                 FROM conversation c
             WHERE
                $where AND (c.deleted_by!=$userId) AND last_reply IS NOT NULL

             Order by c.last_time DESC,c.id DESC

             " . $limitSubQuery
        )->queryAll();


        foreach ($conversationList as $item) {
            $item['new_message_count'] = 0;
            if($item["user_one"] == Yii::$app->user->id){
                $otherUserId = $item["user_two"];
                $item['new_message_count'] = $item['not_read_one'];

            } else {
                $otherUserId = $item["user_one"];
                $item['new_message_count'] = $item['not_read_two'];

            }
           // $item['new_message_count'] = 0;
            $user = Yii::$app->db->createCommand('SELECT id as user_id,full_name,last_activity,profile_photo,sex,nickname  FROM `user` WHERE id="'.$otherUserId.'"')->queryOne();
            $item['sex'] = $user['sex'];
            $item['user_id'] = $user['user_id'];

            $item['full_name'] = $user['full_name'];
            $item['nickname'] = $user['nickname'];
            $item['last_activity'] = $user['last_activity'];
            $item['profile_photo'] = $user['profile_photo'];




            if ($item['last_activity'] > (time() - Yii::$app->params['userOnlineStatusCheckTime']))
                $item['userOnline'] = true;
            else
                $item['userOnline'] = false;

            if (!$item['profile_photo'])
                $item['profile_photo'] = Yii::$app->params['defaultProfilePicture_'.$item['sex']];


            $item['time'] = $item['last_time'];




             $item['reply'] = $item['last_reply'];

            $smilesArray = ConversationReply::getEmojis();

            $item['reply']= str_replace(array_keys($smilesArray), array_values($smilesArray),  $item['reply']);

            if ($item["new_message_count"]  == 0) {

                $item['read'] = 1;

            } else {

                $item['read'] = 0;
            }
            $conversations[] = $item;
        }

       // self::array_sort_by_column($conversations, 'last_time', SORT_DESC);

        return $conversations;
    }



    public static function getIssetOtherUserMessage($cid)
    {
        $result = ConversationReply::find()->where("conversation_id = ".$cid." and user_id!=".Yii::$app->user->id)->count('id');
        if($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function  getConversation($id, $userId, $offset = 0, $limit = 50)
    {
        $userId = intval($userId);
        $offset = intval($offset);
        $limit = intval($limit);
        $id = intval($id);

        $limitSubQuery = '';

        if ($limit)
            $limitSubQuery = "Limit $limit  OFFSET $offset";


        $conversationData = Conversation::findBySql("
             SELECT
                  u.id as user_id,
                  c.by_system,
                  c.id as conversation_id,
                  c.user_one,
                  c.user_two,
                  LEFT(u.nickname, 30) as nickname,
                  u.last_activity,
                  u.profile_photo,
                  city.name as city,
                  u.age,u.sex
                 FROM conversation c, user u

             LEFT JOIN city ON u.city_id = city.id
             WHERE
                 CASE
                     WHEN c.user_one = $userId
                     THEN c.user_two = u.id
                     WHEN c.user_two = $userId
                     THEN c.user_one= u.id
                 END
             AND c.deleted_by!=$userId
             AND c.id=$id
             LIMIT 1
             ")
            ->asArray()
            ->one();

        $userSex = $conversationData['sex'];

        if ($conversationData['last_activity'] > (time() - Yii::$app->params['userOnlineStatusCheckTime']))
            $conversationData['userOnline'] = true;
        else
            $conversationData['userOnline'] = false;

        if (!$conversationData['profile_photo'])
            $conversationData['profile_photo'] = Yii::$app->params['defaultProfilePicture_'.$userSex];

        $messages = ConversationReply::findBySql(
            "SELECT
                R.id,
                R.time,
                R.reply,
                R.send_photo_id,
                R.photo_id,
                U.id as user_id,
                U.full_name
            FROM
                user U,
                conversation_reply R
            WHERE
                R.user_id = U.id
            AND R.conversation_id = $id
            AND R.deleted_by !=  $userId
            ORDER BY
                R.id DESC
            LIMIT $limit")->asArray()->all();

        foreach ($messages as $k => $v) {


            if (intval($messages[$k]['user_id']) == $userId)
                $messages[$k]['full_name'] = Yii::t('app', 'Me');
            $messages[$k]["o_time"] = $messages[$k]['time'];

            $today = date("d");

            $day = date("d", $messages[$k]['time']);

            if ($day == $today) {
                $messages[$k]['time'] = date('H:i', $messages[$k]['time']);
            } elseif ($day == ($today - 1)) {

                $messages[$k]['time'] = Yii::t('app', 'yesterday') . ' ' . date('H:i', $messages[$k]['time']);
            } else
                $messages[$k]['time'] = date('d.M.Y H:i', $messages[$k]['time']);


            if ($messages[$k]['photo_id'] > 0) {

                $photoId = intval($messages[$k]['photo_id']);

                $photoPath = (new Query())->select('path')->from('user_image_thumb')->where('id=' . $photoId)->limit(1)->one();


                $messages[$k]['reply'] = $messages[$k]['reply'] . "<br/><img class='' src='" . Url::base() . $photoPath['path'] . "' />";
                ;

            }


            if ($messages[$k]['send_photo_id'] > 0) {

                $photoId = intval($messages[$k]['send_photo_id']);

                $photoPath = (new Query())->select('path,path_original')->from('user_image_send')->where('id=' . $photoId)->limit(1)->one();


                $messages[$k]['reply'] = "<a href='" . Url::base() . $photoPath['path_original'] . "' download><img class='' src='" . Url::base() . $photoPath['path'] . "' /></a>";


            }
            $smilesArray = ConversationReply::getEmojis();

            $messages[$k]['reply'] = str_replace(array_keys($smilesArray), array_values($smilesArray), $messages[$k]['reply']);


        }

        $conversationData['messages'] = $messages;

        return $conversationData;
    }

    public static function getNewMessages($id)
    {


        $id = intval($id);
        $ids = [];
        $messages = (new Query())
            ->select('id,time,reply,photo_id')
            ->from('conversation_reply')
            ->where("user_id!='" . Yii::$app->user->id . "' AND deleted_by!='" . Yii::$app->user->id . "' AND conversation_id='" . $id . "' AND `read`='0'")
            ->orderBy(['id' => SORT_DESC])
            ->all();

        foreach ($messages as $k => $v) {

            $ids[] = $messages[$k]['id'];

            $messages[$k]['time'] = date('H:i', $messages[$k]['time']);

            unset($messages[$k]['id']);

            if ($messages[$k]['photo_id'] > 0) {

                $photoId = intval($messages[$k]['photo_id']);

                $photoPath = (new Query())->select('path')->from('user_image_thumb')->where('id=' . $photoId)->limit(1)->one();

                $messages[$k]['reply'] = $messages[$k]['reply'] . "<br/><img class='' src='" . Url::base() . $photoPath['path'] . "' />";
            }


            $smilesArray = ConversationReply::getEmojis();

            $messages[$k]['reply'] = str_replace(array_keys($smilesArray), array_values($smilesArray), $messages[$k]['reply']);
        }

        if (!empty($ids)) {
            ConversationReply::updateAll(['read' => 1], ['id' => $ids]);
        }

        return $messages;
    }

    public static function getNewMessagesCountByConversation($userId)
    {

        $userId = intval($userId);

        $res = [];
        $qr = (new Query())
            ->select("count(CR.id) as count,CR.conversation_id")
            ->from('conversation_reply CR')
            ->innerJoin('conversation C','C.id=CR.conversation_id')
            ->where("CR.user_id!=$userId")
            ->andWhere("CR.deleted_by!=$userId")
            ->andWhere("C.user_one=".Yii::$app->user->id." or C.user_two=".Yii::$app->user->id)
            ->andWhere(['CR.read' => 0])
            ->groupBy('CR.conversation_id')
            ->all();

        if($qr)
            $res = $qr;

        return $res;
    }

    public static function sendBySystemMessage($user_id,$message)
    {

        if(Conversation::checkConversationExist(Yii::$app->params["adminUserId"],$user_id)){
           $conversation = Conversation::checkConversationExist(Yii::$app->params["adminUserId"],$user_id);
            $conversation->deleted_by = 0;
            $conversation->save(false);
        }else{
            $conversation = new Conversation();
            $conversation->user_one = Yii::$app->params["adminUserId"];
            $conversation->user_two = $user_id;
            $conversation->by_system = Conversation::BY_SYSTEM_STATUS;
            $conversation->save(false);
        }

        $conversationReply = new ConversationReply();
        $conversationReply->reply = $message;
        $conversationReply->user_id = $conversation->user_one;
        $conversationReply->user_id_to = $user_id;
        $conversationReply->conversation_id = $conversation->id;
        $conversationReply->photo_id = 0;
        $conversationReply->read = 0;
        $conversationReply->time = time();

        if($conversation->blocked>0){
            $blocked = UserBlock::find()->where(['block_to' => Yii::$app->params["adminUserId"] , 'block_from' => $user_id])->one();
            if ($blocked) {
                $conversationReply->deleted_by = $user_id;
            }
        }

        if($conversationReply->save(false)){

            $conversation->not_read_two+=1;
            $conversation->last_reply = $conversationReply->reply;
            $conversation->last_time = $conversationReply->time;
            $conversation->update();
            return true;
        }else{
            return false;
        }
    }

    public static function  array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}
