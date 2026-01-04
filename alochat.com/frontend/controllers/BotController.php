<?php

namespace frontend\controllers;

use common\models\Conversation;
use common\models\ConversationReply;
use yii\filters\AccessControl;
use common\models\User;

class BotController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','message'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'ips' => ['176.32.32.21','127.0.0.1','37.32.67.22']
                    ],

                ]
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = false;
        $botIdsArray = [13294,1011,1090,8133,8422,8923,9027,9127,9525,9818];
        $r = rand(5,8);
        $countBots = count($botIdsArray);

        $countUser = round(($countBots*$r)/10);
        $rand_keys = array_rand($botIdsArray, $countUser); //$rand_keys[0]

        for($i=0;$i<$countUser;$i++){
            $arrayUser[] = $botIdsArray[$rand_keys[$i]];
        }

        $users  = implode(',',$arrayUser);
        \Yii::$app->db->createCommand('UPDATE user SET last_login=:last_time,last_activity=:last_time WHERE id in ('.$users.')',[':last_time' => time()])
            ->execute();
    }

    public function actionMessage()
    {
        $this->layout = false;

        $botIdsArray = [13294,1011,1090,8133,8422,8923,9027,9127,9525,9818];
        $r = rand(5,8);
        $countBots = count($botIdsArray);

        $countUser = round(($countBots*$r)/10);
        $rand_keys = array_rand($botIdsArray, $countUser); //$rand_keys[0]

        for($i=0;$i<$countUser;$i++){
            $arrayUser[] = $botIdsArray[$rand_keys[$i]];
        }
        // test vaxti 3 dene sekili olan userler olacaq
        $arrayUser = ['8133','1011'];
        $users  = implode(',',$arrayUser);
        \Yii::$app->db->createCommand('UPDATE user SET last_login=:last_time,last_activity=:last_time WHERE id in ('.$users.')',[':last_time' => time()])
            ->execute();

        $time = time()- 7200;

        $arrayWords1 = ['salam','Selam','Slm','Merhaba','Hi', 'Hello'];
        $arrayWords2 = ['necesen','netersen','necesiz'];

        $arrayReplyWords1 = ['Salam','salam','Eleykume salam'];
        $arrayReplyWords2 = ['Yaxsiyam','yaxşiyam, siz?','bele de','Normal. bes sen?','normal','Pis deyiləm','yaxwi'];

        foreach($arrayUser  as $user){

            $messages = Conversation::find()
                ->select('c.id,cr.reply,cr.id as rid,c.level')
                ->from('conversation as c')
                ->leftJoin('conversation_reply as cr','cr.conversation_id = c.id')
                ->where('(c.user_one = '.$user.' or c.user_two='.$user.') and c.last_time>'.$time.' and cr.read=0')
                ->asArray()
                ->all();
           // var_dump($messages);

            foreach($messages as $message){
                $conversation = Conversation::findOne($message["id"]);

                if($message['level'] == 0 or $message['level'] == 1){
                    foreach($arrayWords1 as $word){
                        if(strpos(strtolower($message['reply']),$word) !== false){
                            $conversationReply = new ConversationReply();
                            $conversationReply->user_id = $user;
                            $conversationReply->time = time();
                            $conversationReply->conversation_id = $message['id'];
                            $conversationReply->reply =    $arrayReplyWords1[array_rand($arrayReplyWords1, 1)];


                            if($conversationReply->save(false)){
                                ConversationReply::updateAll(['read' => 1],'conversation_id='.$message['id'].' and user_id!='.$user);

                                $conversation->level = 1;
                                $conversation->save(false);
                                echo "alindi1 <br />";
                            }else {
                                echo "alinmadi <br />";
                            }

                              echo $word." sozu1 islenib". $message['id']." nomresli conversationda";
                        }else {
                            continue;
                        }
                    }

                    foreach($arrayWords2 as $word){
                        if(strpos(strtolower($message['reply']),$word) !== false){
                             $conversationReply = new ConversationReply();
                            $conversationReply->user_id = $user;
                            $conversationReply->time = time();
                            $conversationReply->conversation_id = $message['id'];
                            $conversationReply->reply =    $arrayReplyWords2[array_rand($arrayReplyWords2, 1)];
                            if($conversationReply->save(false)){
                                ConversationReply::updateAll(['read' => 1],'conversation_id='.$message['id'].' and user_id!='.$user);

                                $conversation->level = 2;
                                $conversation->save(false);
                                echo "alindi2 <br />";
                            }else {
                                echo "alinmadi <br />";
                            }

                              echo $word." sozu2 islenib". $message['id']." nomresli conversationda";
                        }else {
                            continue;
                        }
                    }
                }




            }
        }

    }



}
