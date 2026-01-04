<?php
namespace frontend\models;

use common\models\Conversation;
use common\models\ConversationReply;
use common\models\Country;
use common\models\PhotoComment;
use common\models\UserImage;
use yii\base\Model;
use Yii;

/**
 * AcceptCommentForm form
 */
class AcceptCommentForm extends Model
{
    public $photo_id;
    public $text;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['photo_id', 'text'], 'required'],
            [['photo_id'], 'integer'],
            [['text'], 'string', 'max' => 500],
        ];
    }

    public function acceptComment()
    {

        if ($this->validate() && Yii::$app->user->id) {

            $commentedBy = Yii::$app->user;
            $photo = UserImage::findOne(intval($this->photo_id));

            $owner = $photo->user;

            if ($photo && !empty($this->text) && $owner) {

                $conversation = false;

                $conversation = Conversation::checkConversationExist($owner->id, $commentedBy->id);

                if (!$conversation) {

                    $conversation = new Conversation();
                    $conversation->user_one = $owner->id;
                    $conversation->user_two = $commentedBy->id;
                    $conversation->save();
                }

                $checkComment = ConversationReply::find()
                    ->where([
                        'photo_id' => $photo->id,
                        'user_id' => $commentedBy->id,

                    ])
                    ->andWhere(['>=', 'time', time() - 600])
                    ->one();

                if (!$checkComment) {

                    $reply = new ConversationReply();

                    $reply->conversation_id = $conversation->id;
                    $reply->user_id = $commentedBy->id;
                    $reply->reply = $this->text;
                    $reply->photo_id = $photo->id;

                    $reply->save();

                    if ($reply->primaryKey)
                        return true;
                    else
                        $this->addError('text', Yii::t('app', "The comment hasn't been sent"));
                } else {

                    $this->addError('text', Yii::t('app', "You can't post more than one comment per 10 minutes (spam protection)"));
                }

            } else {
                return false;
            }
//            $conversation
//            $comment = new PhotoComment();
//
//            $comment->commented_by =  Yii::$app->user->id;
//
//
//            if ($comment->save(false))
//                return true;
//            else
//                return false;

        }

        return null;
    }

    public function attributeLabels()
    {

        return [

        ];
    }
}
