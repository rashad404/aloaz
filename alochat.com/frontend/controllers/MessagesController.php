<?php

namespace frontend\controllers;

use common\models\City;
use common\models\Conversation;
use common\models\ConversationReply;
use common\models\Country;
use common\models\User;
use common\models\UserActivity;
use common\models\UserBlock;
use common\models\UserImage;
use common\models\UserImageThumb;
use common\models\UserLike;
use frontend\models\AcceptCommentForm;
use frontend\models\CitySelectForm;
use frontend\models\ImageSendForm;
use frontend\models\ImageSendForm1;
use frontend\models\ImageUploadForm;
use frontend\models\MessageSendForm;
use frontend\models\PasswordChangeForm;
use frontend\models\SendForm;
use Yii;
use frontend\models\ProfileSettingsForm;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class MessagesController extends \yii\web\Controller
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
        ];
    }

    public function actionIndex()
    {
        $begin = time()+microtime();

        $this->layout = 'column3';

        $limit = 10;

        $userId = Yii::$app->user->id;

        $p = 0;
        if(isset($_GET["p"]) and  intval($_GET["p"])==1){
            $p = $_GET["p"];
        }

        if($p == 1){
            //  $where_query = '((user_one=$userId and not_read_one>0) OR (user_two=$userId and not_read_two>0) ) AND (deleted_by!=$userId)';
            $query = Conversation::find()->where("((user_one=$userId and not_read_one>0) OR (user_two=$userId and not_read_two>0) ) AND (deleted_by!=$userId) AND last_reply IS NOT NULL");
        }else {
            //  $where_query = '(user_one=$userId OR not_read_two>0)  AND (deleted_by!=$userId)';
            $query = Conversation::find()->where("(user_one=$userId OR user_two=$userId)  AND (deleted_by!=$userId) AND last_reply IS NOT NULL");

        }

        //$countQuery = Yii::$app->db->createCommand('SELECT count(id) FROM conversation WHERE '.$where_query)->queryScalar();

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count('id')]);

        $pages->pageSize = $limit;

        $offset = $pages->offset;

        $conversations = Conversation::getAllConversations2(Yii::$app->user->id, $offset, $limit,$p);


        $end = time()+microtime();

        $vaxt = $end - $begin;


        return $this->render('index',
            [
                'conversations' => $conversations,
                'pages' => $pages,
                'vaxt' => $vaxt,
                'p' => $p
            ]
        );
    }

    public function actionIndex1()
    {
        $begin = time()+microtime();

        $this->layout = 'column3';

        $limit = 5;

        $userId = Yii::$app->user->id;

        $query = Conversation::find()->where("(user_one=$userId OR user_two=$userId) AND (deleted_by!=$userId)");

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $pages->pageSize = $limit;

        $offset = $pages->offset;

        $conversations = Conversation::getAllConversations(Yii::$app->user->id, $offset, $limit);


        $end = time()+microtime();

       echo  $vaxt = $end - $begin;
        var_dump($conversations);

        exit;


        return $this->render('index',
            [
                'conversations' => $conversations,
                'pages' => $pages,
                'vaxt' => $vaxt
            ]
        );
    }

    public function actionIndex2()
    {
        $begin = time()+microtime();
        $this->layout = 'column3';

        $limit =20;

        $userId = Yii::$app->user->id;

        $query = Conversation::find()->where("(user_one=$userId OR user_two=$userId) AND (deleted_by!=$userId)");

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $pages->pageSize = $limit;

        $offset = $pages->offset;

        $conversations = Conversation::getAllConversations2(Yii::$app->user->id, $offset, $limit);


        $end = time()+microtime();
        $vaxt = $end - $begin;

        return $this->render('index2',
            [
                'conversations' => $conversations,
                'pages' => $pages,
                'vaxt' => $vaxt
            ]
        );
    }


    public function actionSend()
    {
        $form = new MessageSendForm();


        if (Yii::$app->request->isAjax) {
            $response = '';
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                if(Yii::$app->user->identity->msg_count_day<Yii::$app->params["maxMsgCount"]){
                    $form->sendMessage();
                    $response = 1;
                    $reply = $form->message;

                    $smilesArray = ConversationReply::getEmojis();

                    $reply = str_replace(array_keys($smilesArray), array_values($smilesArray),  $reply);
                }else{
                    $response = 'Limiti kecdiz';
                }

            } else {
                $response = $form->errors;
            }

            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => Response::FORMAT_JSON,
                'data' => ['response' => $response,'reply' => $reply]
            ]);
        } else {
            throw new BadRequestHttpException;
        }

        // $this->enableCsrfValidation = false;
    }

    public function actionNew($id = 0, $u = 0)
    {
        $this->layout = 'message';
        $db = Yii::$app->db;
        $id = intval($id);
        $u = intval($u);
        $submit_button_status = '';
        $input_readonly_status = false;

        $userTwo = User::findOne($u);

        if ($userTwo) {

            if($userTwo->only_friend == 1) {
                if(User::friendStatus($userTwo->id)!=3){
                    Yii::$app->session->setFlash('error',Yii::t('app','Only friends can write'));
                    $input_readonly_status = true;
                    $submit_button_status = 'disabled';
                    /*return $this->redirect(Url::to(['/u/'.$userTwo->id]));*/
                }
            }

            $exist = Conversation::checkConversationExist(Yii::$app->user->id, $userTwo->id);

            if ($exist) {


                if ($exist->deleted_by > 0) {

                    $exist->deleted_by = 0;
                    $exist->save(false);
                }
                $id = $exist->id;
            } else {
                $newConversation = new Conversation();

                $newConversation->user_one = Yii::$app->user->id;
                $newConversation->user_two = $userTwo->id;
                if(UserBlock::checkUsersIsBlocked($newConversation->user_one,$newConversation->user_two)) {
                    $newConversation->blocked = 1;
                }
                $newConversation->save();
                UserActivity::activityManyConversation();

                $id = $newConversation->primaryKey;

            }

            if ($id)
                return $this->redirect(Url::to(['/messages/view/', 'id' => $id]));
            else
                throw new BadRequestHttpException;
        }


        if (Conversation::checkConversationUser($id, Yii::$app->user->id)) {
            $userTwo = Conversation::getOtherUser($id);
            if($userTwo->only_friend == 1) {
                if(User::friendStatus($userTwo->id)!=3){
                    //  Yii::$app->session->setFlash('error',Yii::t('app','Only friends can write'));
                    $input_readonly_status = true;
                    $submit_button_status = 'disabled';
                    $result_text = Yii::t('app','Only friends can write');
                    // return $this->redirect(Url::to(['/u/'.$userTwo->id]));
                }
            }



            //ConversationReply::updateAll(['read' => 1,'read_time' => time()], " conversation_id=$id and `read`=0 and user_id!=" . Yii::$app->user->id);
            $db->createCommand('UPDATE `conversation_reply` SET `read`=1,`read_time`="'.time().'" where `conversation_id`="'.$id.'" and `read`=0 and `user_id_to`="'.Yii::$app->user->id.'"')->execute();
            $conversation = $db->createCommand('SELECT user_one,user_two FROM conversation WHERE id="'.$id.'"')->queryOne();
            if(Yii::$app->user->id == $conversation["user_one"]) {
                $not_read = "`not_read_one`";
            }elseif(Yii::$app->user->id == $conversation["user_two"]){
                $not_read = "`not_read_two`";
            }
            $db->createCommand('UPDATE `conversation` SET '.$not_read.'=0 WHERE `id`="'.$id.'"')->execute();


            $conversations = Conversation::getAllConversations2(Yii::$app->user->id, false, false);
            $currentConversation = Conversation::getConversation($id, Yii::$app->user->id);





            if($currentConversation["by_system"] == 1){
                $input_readonly_status = true;
                $submit_button_status = 'disabled';
            }

            $messageForm = new MessageSendForm();

            $messageForm->cid = $id;

            $imageForm = new ImageUploadForm();
            $imageSendStatus = Conversation::getIssetOtherUserMessage($id);

            return $this->render('new', [
                'currentConversation' => $currentConversation,
                'conversations' => $conversations,
                'id' => $id,
                'messageSendForm' => $messageForm,
                'submit_button_status' => $submit_button_status,
                'input_readonly_status' => $input_readonly_status,
                'imageForm' => $imageForm,
                'imageSendStatus' => $imageSendStatus,
                'result_text' => $result_text
            ]);
        } else {
            throw new BadRequestHttpException;
        }
     }


    public function actionNew1($id = 0, $u = 0)
    {
        $this->layout = 'message';
        $db = Yii::$app->db;
        $id = intval($id);
        $u = intval($u);
        $submit_button_status = '';
        $input_readonly_status = false;

        $userTwo = User::findOne($u);

        if ($userTwo) {

            if($userTwo->only_friend == 1) {
                if(User::friendStatus($userTwo->id)!=3){
                    Yii::$app->session->setFlash('error',Yii::t('app','Only friends can write'));
                    $input_readonly_status = true;
                    $submit_button_status = 'disabled';
                    /*return $this->redirect(Url::to(['/u/'.$userTwo->id]));*/
                }
            }

            $exist = Conversation::checkConversationExist(Yii::$app->user->id, $userTwo->id);

            if ($exist) {


                if ($exist->deleted_by > 0) {

                    $exist->deleted_by = 0;
                    $exist->save(false);
                }
                $id = $exist->id;
            } else {
                $newConversation = new Conversation();

                $newConversation->user_one = Yii::$app->user->id;
                $newConversation->user_two = $userTwo->id;
                if(UserBlock::checkUsersIsBlocked($newConversation->user_one,$newConversation->user_two)) {
                    $newConversation->blocked = 1;
                }
                $newConversation->save();
                UserActivity::activityManyConversation();

                $id = $newConversation->primaryKey;

            }

            if ($id)
                return $this->redirect(Url::to(['/messages/view/', 'id' => $id]));
            else
                throw new BadRequestHttpException;
        }


        if (Conversation::checkConversationUser($id, Yii::$app->user->id)) {
            $userTwo = Conversation::getOtherUser($id);
            if($userTwo->only_friend == 1) {
                if(User::friendStatus($userTwo->id)!=3){
                    //  Yii::$app->session->setFlash('error',Yii::t('app','Only friends can write'));
                    $input_readonly_status = true;
                    $submit_button_status = 'disabled';
                    $result_text = Yii::t('app','Only friends can write');
                    // return $this->redirect(Url::to(['/u/'.$userTwo->id]));
                }
            }



            //ConversationReply::updateAll(['read' => 1,'read_time' => time()], " conversation_id=$id and `read`=0 and user_id!=" . Yii::$app->user->id);
            $db->createCommand('UPDATE `conversation_reply` SET `read`=1,`read_time`="'.time().'" where `conversation_id`="'.$id.'" and `read`=0 and `user_id_to`="'.Yii::$app->user->id.'"')->execute();
            $conversation = $db->createCommand('SELECT user_one,user_two FROM conversation WHERE id="'.$id.'"')->queryOne();
            if(Yii::$app->user->id == $conversation["user_one"]) {
                $not_read = "`not_read_one`";
            }elseif(Yii::$app->user->id == $conversation["user_two"]){
                $not_read = "`not_read_two`";
            }
            $db->createCommand('UPDATE `conversation` SET '.$not_read.'=0 WHERE `id`="'.$id.'"')->execute();


            $conversations = Conversation::getAllConversations2(Yii::$app->user->id, false, false);
            $currentConversation = Conversation::getConversation($id, Yii::$app->user->id);





            if($currentConversation["by_system"] == 1){
                $input_readonly_status = true;
                $submit_button_status = 'disabled';
            }

            $messageForm = new MessageSendForm();

            $messageForm->cid = $id;

            $imageForm = new ImageUploadForm();
            $imageSendStatus = Conversation::getIssetOtherUserMessage($id);

            return $this->render('new1', [
                'currentConversation' => $currentConversation,
                'conversations' => $conversations,
                'id' => $id,
                'messageSendForm' => $messageForm,
                'submit_button_status' => $submit_button_status,
                'input_readonly_status' => $input_readonly_status,
                'imageForm' => $imageForm,
                'imageSendStatus' => $imageSendStatus,
                'result_text' => $result_text
            ]);
        } else {
            throw new BadRequestHttpException;
        }
    }
    public function actionView($id = 0, $u = 0)
    {
        $startTime = time() + microtime();
        $this->layout = 'message';
        $db = Yii::$app->db;
        $id = intval($id);
        $u = intval($u);
        $submit_button_status = '';
        $input_readonly_status = false;

        $userTwo = User::findOne($u);



        if ($userTwo) {

            if($userTwo->only_friend == 1) {
                if(User::friendStatus($userTwo->id)!=3){
                    Yii::$app->session->setFlash('error',Yii::t('app','Only friends can write'));
                    $input_readonly_status = true;
                    $submit_button_status = 'disabled';
                    /*return $this->redirect(Url::to(['/u/'.$userTwo->id]));*/
                }
            }

            if($userTwo->deactive == 1) {
                  //   Yii::$app->session->setFlash('error',Yii::t('app','Bu istifadeci oz profilini baglayib'));
                    $input_readonly_status = true;
                    $submit_button_status = 'disabled';
                    /*return $this->redirect(Url::to(['/u/'.$userTwo->id]));*/

            }

            $exist = Conversation::checkConversationExist(Yii::$app->user->id, $userTwo->id);

            if ($exist) {


                if ($exist->deleted_by > 0) {

                    $exist->deleted_by = 0;
                    $exist->save(false);
                }
                $id = $exist->id;
            } else {
                $newConversation = new Conversation();

                $newConversation->user_one = Yii::$app->user->id;
                $newConversation->user_two = $userTwo->id;
                $newConversation->create_time = time();
                if(UserBlock::checkUsersIsBlocked($newConversation->user_one,$newConversation->user_two)) {
                    $newConversation->blocked = 1;
                }


                if(Yii::$app->user->identity->verify==0){
                    $day_begin = strtotime(date("d-m-Y 0:0"));
                    $day_now = strtotime(date("d-m-Y H:i:s"));
                    $dailyConversation = Yii::$app->db->createCommand('SELECT count(id) FROM `conversation` WHERE user_one=:user_id and create_time>=:day_begin and create_time<=:day_now')
                        ->bindValues([":user_id" => Yii::$app->user->id,':day_begin' => $day_begin,':day_now' => $day_now])->queryScalar();
                    if($dailyConversation>Yii::$app->params["NotVerifiesUserConversationLimit"]){
                        Yii::$app->session->
                        setFlash('error','Sizin nömrəniz təsdiq olunmayıb və günlük 10 yazışma limitini keçmisiz. Zəhmət olmasa saytdan tam yararlanmaq üçün <a href="'.Url::to(["/profile/verify"]).'">nömrənizi təsdiq edin</a>');

                        return $this->redirect(Url::to(['messages/index']));
                        exit;
                    }
                }
                $newConversation->save();
                UserActivity::activityManyConversation();

                $id = $newConversation->primaryKey;

            }

            if ($id)
                return $this->redirect(Url::to(['/messages/view/', 'id' => $id]));
            else
                throw new BadRequestHttpException;
        }


        if (Conversation::checkConversationUser($id, Yii::$app->user->id)) {
            $userTwo = Conversation::getOtherUser($id);
            if($userTwo->only_friend == 1) {
                if(User::friendStatus($userTwo->id)!=3){
                    //  Yii::$app->session->setFlash('error',Yii::t('app','Only friends can write'));
                    $input_readonly_status = true;
                    $submit_button_status = 'disabled';
                    $result_text = Yii::t('app','Only friends can write');
                    // return $this->redirect(Url::to(['/u/'.$userTwo->id]));
                }
            }

            if($userTwo->deactive == 1) {
               // Yii::$app->session->setFlash('error',Yii::t('app','Bu istifadeci oz profilini baglayib'));
                $input_readonly_status = true;
                $submit_button_status = 'disabled';
                $result_text = Yii::t('app','Bu istifadəçi öz profilini bağlayıb');

                /*return $this->redirect(Url::to(['/u/'.$userTwo->id]));*/

            }

            //ConversationReply::updateAll(['read' => 1,'read_time' => time()], " conversation_id=$id and `read`=0 and user_id!=" . Yii::$app->user->id);
            $db->createCommand('UPDATE `conversation_reply` SET `read`=1,`read_time`="'.time().'" where `conversation_id`="'.$id.'" and `read`=0 and `user_id_to`="'.Yii::$app->user->id.'"')->execute();
            $conversation = $db->createCommand('SELECT user_one,user_two FROM conversation WHERE id="'.$id.'"')->queryOne();
            if(Yii::$app->user->id == $conversation["user_one"]) {
                $not_read = "`not_read_one`";
            }elseif(Yii::$app->user->id == $conversation["user_two"]){
                $not_read = "`not_read_two`";
            }
            $db->createCommand('UPDATE `conversation` SET '.$not_read.'=0 WHERE `id`="'.$id.'"')->execute();


            $conversations = Conversation::getAllConversations2(Yii::$app->user->id, false, false);
            $currentConversation = Conversation::getConversation($id, Yii::$app->user->id);


            if($currentConversation["by_system"] == 1){
                $input_readonly_status = true;
                $submit_button_status = 'disabled';
            }



            $messageForm = new MessageSendForm();

            $messageForm->cid = $id;

            $imageForm = new ImageUploadForm();
            $imageSendStatus = Conversation::getIssetOtherUserMessage($id);


            $endTime = time() + microtime();
            $ferqTime = $endTime - $startTime;
            return $this->render('new3', [
                'currentConversation' => $currentConversation,
                'conversations' => $conversations,
                'id' => $id,
                'messageSendForm' => $messageForm,
                'submit_button_status' => $submit_button_status,
                'input_readonly_status' => $input_readonly_status,
                'imageForm' => $imageForm,
                'imageSendStatus' => $imageSendStatus,
                'result_text' => $result_text,
                'ferqTime' => $ferqTime
            ]);
        } else {
            throw new BadRequestHttpException;
        }
    }

    public function actionGetNew($id)
    {

        $user = User::findOne(Yii::$app->user->id);

        $user->updateLastActivity();

        $id = intval($id);

        if ($id > 0 && Yii::$app->request->isAjax && Conversation::checkConversationUser($id, Yii::$app->user->id)) {

            $response = Conversation::getNewMessages($id);
            if(!$response)
                $response = [];
            $conversationMessages = Conversation::getNewMessagesCountByConversation(Yii::$app->user->id);

           // $conversationMessages = ConversationReply::getNewMessagesCount(Yii::$app->user->id);


            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => Response::FORMAT_JSON,
                'data' => ['messages' => $response,'conversationMessages'=>$conversationMessages]
            ]);
        } else {
            throw new BadRequestHttpException;
        }
    }

    public function actionGetNewMessages()
    {

        $user = User::findOne(Yii::$app->user->id);

        if (Yii::$app->request->isAjax) {


            $response = [];
            $conversationMessages = Conversation::getNewMessagesCountByConversation(Yii::$app->user->id);

            // $conversationMessages = ConversationReply::getNewMessagesCount(Yii::$app->user->id);


            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => Response::FORMAT_JSON,
                'data' => ['messages' => $response,'conversationMessages'=>$conversationMessages]
            ]);
        } else {
            throw new BadRequestHttpException;
        }
    }



    public function actionDeleteConversation($id)
    {
        if (Yii::$app->request->method != "POST")
            throw new BadRequestHttpException;

        $id = intval($id);

        $userId = Yii::$app->user->id;

        $conversation = Conversation::find()
            ->where("id=$id AND (user_one=$userId or user_two=$userId)")
            ->one();

        if (intval($conversation->deleted_by) > 0 && $conversation->deleted_by != $userId) {

            ConversationReply::deleteAll(['conversation_id' => $id]);
            $conversation->delete();

        } elseif (intval($conversation->deleted_by) == 0) {
            ConversationReply::deleteAll('deleted_by > 0 and deleted_by!='.$userId.' and conversation_id ='.$id);
            ConversationReply::updateAll(['deleted_by' => $userId], ['conversation_id' => $id, 'deleted_by' => 0]);
            $conversation->deleted_by = $userId;
            $conversation->save();
        }

        return $this->redirect(Url::to(['/messages/']));
    }


    public function actionSendImage()
    {
        $this->enableCsrfValidation = false;

        $imageForm = new ImageUploadForm();

        if (Yii::$app->request->isPost) {
            if(isset($_GET["id"]) and intval($_GET["id"])!=0){
                $cid = intval($_GET["id"]);
            } else {
                $cid = 3;
            }
            $images = UploadedFile::getInstances($imageForm, 'image');

            if ($images) {

                if ($imageForm->validateImages($images)) {

                    $imageForm->sendImages($images,$cid);


                    return  \Yii::createObject([
                        'class' => 'yii\web\Response',
                        'format' => \yii\web\Response::FORMAT_JSON,
                        'data' => ['success' => Yii::t('app', 'Your images successfully uploaded.')]
                    ]);


                } else {

                    return \Yii::createObject([
                        'class' => 'yii\web\Response',
                        'format' => \yii\web\Response::FORMAT_JSON,
                        'data' => ['error' => $imageForm->errors['image']]
                    ]);
                }
            }
        }
    }




}
