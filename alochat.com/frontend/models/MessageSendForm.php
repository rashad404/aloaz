<?php
namespace frontend\models;

use common\models\Conversation;
use common\models\ConversationReply;
use common\models\Country;
use common\models\PhotoComment;
use common\models\User;
use common\models\UserActivity;
use common\models\UserBlock;
use common\models\UserImage;
use yii\base\Model;
use Yii;
use yii\db\Query;

/**
 * MessageSendForm form
 */
class MessageSendForm extends Model
{
    public $message;
    public $cid;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'cid'], 'required'],
            [['cid'], 'integer'],
            [['message'], 'string', 'max' => 1000],
            ['cid', 'validateCid'],
            ['message', 'validateMessage']
        ];
    }

    public function validateCid($attribute)
    {
        if (Conversation::checkConversationUser($this->$attribute, Yii::$app->user->id))
            return true;
        else
            $this->addError('message', Yii::t('app', 'You cannot send this message'));

    }

    public function validateMessage($attribute)
    {
        $this->$attribute = trim($this->$attribute);

        $last2Messages = (new Query())
            ->select('reply')
            ->from('conversation_reply')
            ->where(['user_id' => Yii::$app->user->id, 'conversation_id' => $this->cid])
            ->andWhere(['>', 'time', (time() - (2*60))])
            ->orderBy(['id' => SORT_DESC])
            ->limit(2)
            ->all();

        if (count($last2Messages) == 2) {

            if ($last2Messages[0]['reply'] == $this->$attribute
                && $last2Messages[1]['reply'] == $this->$attribute
            ) {
                $this->addError($attribute, Yii::t('app', 'Be more creative!'));
            }
        }
    }

    public function  sendMessage()
    {
        $conversation = Conversation::findOne($this->cid);
        $user = Yii::$app->user->identity;
        if($conversation->deleted_by >0) {
            $conversation->deleted_by = 0;
            $conversation->update(false, ['deleted_by']);
        }

        $reply = new ConversationReply();

        if($conversation->user_one == Yii::$app->user->id) {
            $otherUserId = $conversation->user_two;
        }else {
            $otherUserId = $conversation->user_one;
        }
        $reply->read = 0;
        if($conversation->blocked>0){

            $blocked = UserBlock::find()->where(['block_to' => Yii::$app->user->id, 'block_from' => $otherUserId])->one();
            if ($blocked) {
                $reply->deleted_by = $otherUserId;
                $reply->read = 1;
            }
        }
        $otherUser = Yii::$app->db->createCommand('SELECT nickname FROM `user` WHERE id='.$otherUserId)->queryOne();

        $reply->conversation_id = $this->cid;

        $reply->user_id = Yii::$app->user->id;

        $reply->user_id_to = $otherUserId;

        $reply->from_nick = Yii::$app->user->identity->nickname;

        //$message = User::func_strip_tags($this->message);

        $message = MessageSendForm::filterword($this->message);//User::filterword($this->message);
        $reply->reply = $message;



        $reply->save();



        if ($reply->primaryKey)
        {

            $user->msg_count = $user->msg_count + 1;
            $user->msg_count_day = $user->msg_count_day + 1;
            $user->save(false);

            if($conversation->user_one == $reply->user_id){
                $not_read = 'not_read_two';
            }else {
                $not_read = 'not_read_one';
            }
                 $conversation->$not_read+=1;

            $conversation->last_time = $reply->time;
            $conversation->last_reply = $reply->reply;
            $conversation->update();
            UserActivity::activityFirstMessage();
            return true;
        }
        else
            $this->addError('text', Yii::t('app', "The comment hasn't been sent"));

        return null;
    }

    public static function filterword($word)
    {
        $filter_words = array('<script','</script>');
        $f_word = str_replace($filter_words,'*',$word);
        return $f_word;
    }

    public function attributeLabels()
    {

        return [

        ];
    }
}
