<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "conversation_reply".
 *
 * @property integer $id
 * @property string $reply
 * @property integer $user_id
 * @property integer $user_id_to
 * @property integer $conversation_id
 * @property integer $photo_id
 * @property integer $send_photo_id
 * @property integer $read
 * @property integer $deleted_by
 * @property integer $time
 * @property string $from_nick
 */
class ConversationReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conversation_reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reply'], 'string'],
            [['user_id', 'conversation_id'], 'required'],
            ['time', 'default', 'value' => time()],
            [['user_id', 'user_id_to','conversation_id','photo_id','send_photo_id', 'read','deleted_by',  'time'], 'integer'],
            ['from_nick','safe']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'reply' => Yii::t('app', 'Reply'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_id_to' => Yii::t('app', 'User ID to'),
            'conversation_id' => Yii::t('app', 'Conversation ID'),
            'send_photo_id' => Yii::t('app', 'Send Photo ID'),
            'photo_id' => Yii::t('app', 'Photo ID'),
            'read' => Yii::t('app', 'Read'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'time' => Yii::t('app', 'Time'),
            'from_nick' => Yii::t('app', 'From Nickname'),
        ];
    }

    public static function sendMessage($userFrom, $userTo, $reply)
    {

        $conversation = Conversation::checkConversationExist($userFrom, $userTo);

        if (!$conversation) {
            $conversation = new Conversation();
            $conversation->user_one = $userFrom;
            $conversation->user_two = $userTo;
            $conversation->save();
        }

        $message = new ConversationReply();

        $message->user_id = $userFrom;
        $message->user_id_to = $userTo;
         $message->reply = $reply;
        $message->conversation_id = $conversation->id;
        $message->time = time();

        $message->save(false);

        if ($message->id)
            return true;
        else
            return false;

    }

    public static function getNewMessagesCount($userId)
    {

        $userId = intval($userId);
        $res = 0;
        /*$qr = (new Query())
            ->select("count(conversation_id) as count") //DISTINCT conversation_id
            ->from('conversation_reply')
            ->where("user_id!=$userId")
            ->andWhere("deleted_by!=$userId")
            ->andWhere(['read' => 0])
            ->andWhere("conversation_id in (Select id from conversation where user_one=$userId or user_two=$userId)")
            ->one();*/




        $db = Yii::$app->db;
        $qr = $db->createCommand('SELECT count(conversation_id) as `count` FROM `conversation_reply` WHERE user_id_to="'.$userId.'" and deleted_by!="'.$userId.'" and `read`=0')->queryScalar();
        if($qr>0)
            $res = intval($qr);
        return $res;
    }

    public static function getSmiles()
    {
        return      $smilesArray = ['.e1.' => '1f604',
            '.e2.' => '1f603',
            '.e3.' => '1f600',
            '.e4.' => '1f60a',
            '.e5.' => '263a',
            '.e6.' => '1f609',
            '.e7.' => '1f60d',
            '.e8.' => '1f618',
            '.e9.' => '1f61a',
            '.e10.' => '1f617',
            '.e11.' => '1f619',
            '.e12.' => '1f61c',
            '.e13.' => '1f61d',
            '.e14.' => '1f61b',
            '.e15.' => '1f633',
            '.e16.' => '1f601',
            '.e17.' => '1f614',
            '.e18.' => '1f60c',
            '.e19.' => '1f612',
            '.e20.' => '1f61e',
            '.e21.' => '1f623',
            '.e22.' => '1f622',
            '.e23.' => '1f602',
            '.e24.' => '1f62d',
            '.e25.' => '1f62a',
            '.e26.' => '1f625',
            '.e27.' => '1f630',
            '.e28.' => '1f605',
            '.e29.' => '1f613',
            '.e30.' => '1f629',
            '.e31.' => '1f62b',
            '.e32.' => '1f628',
            '.e33.' => '1f631',
            '.e34.' => '1f620',
            '.e35.' => '1f621',
            '.e36.' => '1f624',
            '.e37.' => '1f616',
            '.e38.' => '1f606',
            '.e39.' => '1f60b',
            '.e40.' => '1f637',
            '.e41.' => '1f60e',
            '.e42.' => '1f634',
            '.e43.' => '1f635',
            '.e44.' => '1f632',
            '.e45.' => '1f61f',
            '.e46.' => '1f626',
            '.e47.' => '1f627',
            '.e48.' => '1f608',
            '.e49.' => '1f47f',
            '.e50.' => '1f62e',
            '.e51.' => '1f62c',
            '.e52.' => '1f610',
            '.e53.' => '1f615',
            '.e54.' => '1f62f',
            '.e55.' => '1f636',
            '.e56.' => '1f607',
            '.e57.' => '1f60f',
            '.e58.' => '1f611',
            '.e59.' => '1f472',
            '.e60.' => '1f473',
            '.e61.' => '1f46e',
            '.e62.' => '1f477',
            '.e63.' => '1f482',
            '.e64.' => '1f476',
            '.e65.' => '1f466',
            '.e66.' => '1f467',
            '.e67.' => '1f468',
            '.e68.' => '1f469',
            '.e69.' => '1f474',
            '.e70.' => '1f475',
            '.e71.' => '1f471',
            '.e72.' => '1f47c',
            '.e73.' => '1f478',
            '.e74.' => '1f63a',
            '.e75.' => '1f638',
            '.e76.' => '1f63b',
            '.e77.' => '1f63d',
            '.e78.' => '1f63c',
            '.e79.' => '1f640',
            '.e80.' => '1f63f',
            '.e81.' => '1f639',
            '.e82.' => '1f63e',
            '.e83.' => '1f479',
            '.e84.' => '1f47a',
            '.e85.' => '1f648',
            '.e86.' => '1f649',
            '.e87.' => '1f64a',
            '.e88.' => '1f480',
            '.e89.' => '1f47d',
            '.e90.' => '1f4a9',
            '.e91.' => '1f525',
            '.e92.' => '2728',
            '.e93.' => '1f31f',
            '.e94.' => '1f4ab',
            '.e95.' => '1f4a5',
            '.e96.' => '1f4a2',
            '.e97.' => '1f4a6',
            '.e98.' => '1f4a7',
            '.e99.' => '1f4a4',
            '.e100.' => '1f4a8',
            '.e101.' => '1f442',
            '.e102.' => '1f440',
            '.e103.' => '1f443',
            '.e104.' => '1f445',
            '.e105.' => '1f444',
            '.e106.' => '1f44d',
            '.e107.' => '1f44e',
            '.e108.' => '1f44c',
            '.e109.' => '1f44a',
            '.e110.' => '270a',
            '.e111.' => '270c',
            '.e112.' => '1f44b',
            '.e113.' => '270b',
            '.e114.' => '1f450',
            '.e115.' => '1f446',
            '.e116.' => '1f447',
            '.e117.' => '1f449',
            '.e118.' => '1f448',
            '.e119.' => '1f64c',
            '.e120.' => '1f64f',
            '.e121.' => '261d',
            '.e122.' => '1f44f',
            '.e123.' => '1f4aa',
            '.e124.' => '1f6b6',
            '.e125.' => '1f3c3',
            '.e126.' => '1f483',
            '.e127.' => '1f46b',
            '.e128.' => '1f46a',
            '.e129.' => '1f46c',
            '.e130.' => '1f46d',
            '.e131.' => '1f48f',
            '.e132.' => '1f491',
            '.e133.' => '1f46f',
            '.e134.' => '1f646',
            '.e135.' => '1f645',
            '.e136.' => '1f481',
            '.e137.' => '1f64b',
            '.e138.' => '1f486',
            '.e139.' => '1f487',
            '.e140.' => '1f485',
            '.e141.' => '1f470',
            '.e142.' => '1f64e',
            '.e143.' => '1f64d',
            '.e144.' => '1f647',
            '.e145.' => '1f3a9',
            '.e146.' => '1f451',
            '.e147.' => '1f452',
            '.e148.' => '1f45f',
            '.e149.' => '1f45e',
            '.e150.' => '1f461',
            '.e151.' => '1f460',
            '.e152.' => '1f462',
            '.e153.' => '1f455',
            '.e154.' => '1f454',
            '.e155.' => '1f45a',
            '.e156.' => '1f457',
            '.e157.' => '1f3bd',
            '.e158.' => '1f456',
            '.e159.' => '1f458',
            '.e160.' => '1f459',
            '.e161.' => '1f4bc',
            '.e162.' => '1f45c',
            '.e163.' => '1f45d',
            '.e164.' => '1f45b',
            '.e165.' => '1f453',
            '.e166.' => '1f380',
            '.e167.' => '1f302',
            '.e168.' => '1f484',
            '.e169.' => '1f49b',
            '.e170.' => '1f499',
            '.e171.' => '1f49c',
            '.e172.' => '1f49a',
            '.e173.' => '2764',
            '.e174.' => '1f494',
            '.e175.' => '1f497',
            '.e176.' => '1f493',
            '.e177.' => '1f495',
            '.e178.' => '1f496',
            '.e179.' => '1f49e',
            '.e180.' => '1f498',
            '.e181.' => '1f48c',
            '.e182.' => '1f48b',
            '.e183.' => '1f48d',
            '.e184.' => '1f48e',
            '.e185.' => '1f464',
            '.e186.' => '1f465',
            '.e187.' => '1f4ac',
            '.e188.' => '1f463',
            '.e189.' => '1f4ad',
            '.e190.' => '1f3b6',
            '.e191.' => '1f3b7',
            '.e192.' => '1f3b8',
            '.e193.' => '1f3b9',
            '.e194.' => '1f3ba',
            '.e195.' => '1f3bb'];

    }

    public static function getEmojis()
    {

     $smilesArray = ['.e1.' => '1f604',
        '.e2.' => '1f603',
        '.e3.' => '1f600',
        '.e4.' => '1f60a',
        '.e5.' => '263a',
        '.e6.' => '1f609',
        '.e7.' => '1f60d',
        '.e8.' => '1f618',
        '.e9.' => '1f61a',
        '.e10.' => '1f617',
        '.e11.' => '1f619',
        '.e12.' => '1f61c',
        '.e13.' => '1f61d',
        '.e14.' => '1f61b',
        '.e15.' => '1f633',
        '.e16.' => '1f601',
        '.e17.' => '1f614',
        '.e18.' => '1f60c',
        '.e19.' => '1f612',
        '.e20.' => '1f61e',
        '.e21.' => '1f623',
        '.e22.' => '1f622',
        '.e23.' => '1f602',
        '.e24.' => '1f62d',
        '.e25.' => '1f62a',
        '.e26.' => '1f625',
        '.e27.' => '1f630',
        '.e28.' => '1f605',
        '.e29.' => '1f613',
        '.e30.' => '1f629',
        '.e31.' => '1f62b',
        '.e32.' => '1f628',
        '.e33.' => '1f631',
        '.e34.' => '1f620',
        '.e35.' => '1f621',
        '.e36.' => '1f624',
        '.e37.' => '1f616',
        '.e38.' => '1f606',
        '.e39.' => '1f60b',
        '.e40.' => '1f637',
        '.e41.' => '1f60e',
        '.e42.' => '1f634',
        '.e43.' => '1f635',
        '.e44.' => '1f632',
        '.e45.' => '1f61f',
        '.e46.' => '1f626',
        '.e47.' => '1f627',
        '.e48.' => '1f608',
        '.e49.' => '1f47f',
        '.e50.' => '1f62e',
        '.e51.' => '1f62c',
        '.e52.' => '1f610',
        '.e53.' => '1f615',
        '.e54.' => '1f62f',
        '.e55.' => '1f636',
        '.e56.' => '1f607',
        '.e57.' => '1f60f',
        '.e58.' => '1f611',
        '.e59.' => '1f472',
        '.e60.' => '1f473',
        '.e61.' => '1f46e',
        '.e62.' => '1f477',
        '.e63.' => '1f482',
        '.e64.' => '1f476',
        '.e65.' => '1f466',
        '.e66.' => '1f467',
        '.e67.' => '1f468',
        '.e68.' => '1f469',
        '.e69.' => '1f474',
        '.e70.' => '1f475',
        '.e71.' => '1f471',
        '.e72.' => '1f47c',
        '.e73.' => '1f478',
        '.e74.' => '1f63a',
        '.e75.' => '1f638',
        '.e76.' => '1f63b',
        '.e77.' => '1f63d',
        '.e78.' => '1f63c',
        '.e79.' => '1f640',
        '.e80.' => '1f63f',
        '.e81.' => '1f639',
        '.e82.' => '1f63e',
        '.e83.' => '1f479',
        '.e84.' => '1f47a',
        '.e85.' => '1f648',
        '.e86.' => '1f649',
        '.e87.' => '1f64a',
        '.e88.' => '1f480',
        '.e89.' => '1f47d',
        '.e90.' => '1f4a9',
        '.e91.' => '1f525',
        '.e92.' => '2728',
        '.e93.' => '1f31f',
        '.e94.' => '1f4ab',
        '.e95.' => '1f4a5',
        '.e96.' => '1f4a2',
        '.e97.' => '1f4a6',
        '.e98.' => '1f4a7',
        '.e99.' => '1f4a4',
        '.e100.' => '1f4a8',
        '.e101.' => '1f442',
        '.e102.' => '1f440',
        '.e103.' => '1f443',
        '.e104.' => '1f445',
        '.e105.' => '1f444',
        '.e106.' => '1f44d',
        '.e107.' => '1f44e',
        '.e108.' => '1f44c',
        '.e109.' => '1f44a',
        '.e110.' => '270a',
        '.e111.' => '270c',
        '.e112.' => '1f44b',
        '.e113.' => '270b',
        '.e114.' => '1f450',
        '.e115.' => '1f446',
        '.e116.' => '1f447',
        '.e117.' => '1f449',
        '.e118.' => '1f448',
        '.e119.' => '1f64c',
        '.e120.' => '1f64f',
        '.e121.' => '261d',
        '.e122.' => '1f44f',
        '.e123.' => '1f4aa',
        '.e124.' => '1f6b6',
        '.e125.' => '1f3c3',
        '.e126.' => '1f483',
        '.e127.' => '1f46b',
        '.e128.' => '1f46a',
        '.e129.' => '1f46c',
        '.e130.' => '1f46d',
        '.e131.' => '1f48f',
        '.e132.' => '1f491',
        '.e133.' => '1f46f',
        '.e134.' => '1f646',
        '.e135.' => '1f645',
        '.e136.' => '1f481',
        '.e137.' => '1f64b',
        '.e138.' => '1f486',
        '.e139.' => '1f487',
        '.e140.' => '1f485',
        '.e141.' => '1f470',
        '.e142.' => '1f64e',
        '.e143.' => '1f64d',
        '.e144.' => '1f647',
        '.e145.' => '1f3a9',
        '.e146.' => '1f451',
        '.e147.' => '1f452',
        '.e148.' => '1f45f',
        '.e149.' => '1f45e',
        '.e150.' => '1f461',
        '.e151.' => '1f460',
        '.e152.' => '1f462',
        '.e153.' => '1f455',
        '.e154.' => '1f454',
        '.e155.' => '1f45a',
        '.e156.' => '1f457',
        '.e157.' => '1f3bd',
        '.e158.' => '1f456',
        '.e159.' => '1f458',
        '.e160.' => '1f459',
        '.e161.' => '1f4bc',
        '.e162.' => '1f45c',
        '.e163.' => '1f45d',
        '.e164.' => '1f45b',
        '.e165.' => '1f453',
        '.e166.' => '1f380',
        '.e167.' => '1f302',
        '.e168.' => '1f484',
        '.e169.' => '1f49b',
        '.e170.' => '1f499',
        '.e171.' => '1f49c',
        '.e172.' => '1f49a',
        '.e173.' => '2764',
        '.e174.' => '1f494',
        '.e175.' => '1f497',
        '.e176.' => '1f493',
        '.e177.' => '1f495',
        '.e178.' => '1f496',
        '.e179.' => '1f49e',
        '.e180.' => '1f498',
        '.e181.' => '1f48c',
        '.e182.' => '1f48b',
        '.e183.' => '1f48d',
        '.e184.' => '1f48e',
        '.e185.' => '1f464',
        '.e186.' => '1f465',
        '.e187.' => '1f4ac',
        '.e188.' => '1f463',
        '.e189.' => '1f4ad',
        '.e190.' => '1f3b6',
        '.e191.' => '1f3b7',
        '.e192.' => '1f3b8',
        '.e193.' => '1f3b9',
        '.e194.' => '1f3ba',
        '.e195.' => '1f3bb'];

        foreach ($smilesArray as $key => $value) {
            $smilesArray[$key] = '<img class="smile" src="/images/smiles/'.$value.'.png" alt="'.$key.'"/>';
        }

        return $smilesArray;


    }

    public static function getSmileImg($text)
    {
        $smilesArray = self::getEmojis();
        $text = str_replace(array_keys($smilesArray), array_values($smilesArray),   $text);
        return $text;

    }

}
