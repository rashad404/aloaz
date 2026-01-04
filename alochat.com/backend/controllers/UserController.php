<?php

namespace backend\controllers;

use common\models\Auth;
use common\models\Blocks;
use common\models\Conversation;
use common\models\ConversationReply;
use common\models\Share;
use common\models\ShareComment;
use common\models\ShareLike;
use common\models\UserActivity;
use common\models\UserBlock;
use common\models\UserFriend;
use common\models\UserGift;
use common\models\UserImage;
use common\models\UserImageResized;
use common\models\UserImageThumb;
use common\models\UserLike;
use common\models\UserPhotoUploadAsk;
use common\models\UserVip;
use common\models\UserVisit;
use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\BaseFileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index','online','view','create','update','delete','test','delete-shares','block','block-users','remove-block'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $onfroms = Yii::$app->db->createCommand('SELECT DISTINCT  onfrom FROM `user`')->queryAll();
        $regfroms = Yii::$app->db->createCommand('SELECT DISTINCT  regfrom FROM `user`')->queryAll();

        $onfromsArray = [];
        foreach($onfroms as $v){
            $onfromsArray[$v["onfrom"]] = $v["onfrom"];
        }

        $regfromsArray = [];
        foreach($regfroms as $v){
            $regfromsArray[$v["regfrom"]] = $v["regfrom"];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'onfroms' => $onfromsArray,
            'regfroms' => $regfromsArray
        ]);
    }

    public function actionBlockUsers()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->blockUser(Yii::$app->request->queryParams);


        return $this->render('block-users', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
         ]);
    }

    public function actionRemoveBlock($id)
    {
        $user = $this->findModel($id);

        $user->block_time = 0;
        $user->block_begin_time = 0;
        $user->save(false);

        Yii::$app->db->createCommand('UPDATE blocks SET end_time=:end_time,ended=1 WHERE id=:user_id ORDER BY id DESC')->bindValues([":user_id" => $id, ":end_time" => time()]);

        return $this->redirect('user/block-users');

    }

    public function actionBlock($id)
    {

        $user = $this->findModel($id);

        $model = new Blocks();

        $times = [
            '60' => '1 dəq',
            '300' => '5 dəq',
            '900' => '15 dəq',
            '1800' => '30 dəq',
            '3600' => '1 saat',
            '43200' => '12 saat',
            '86400' => '24 saat',
            '604800' => '1 həftə',
            '2592000' => '1 ay',
            '31104000' => '12 ay',
        ];

        if ($model->load(Yii::$app->request->post())) {
            $model->begin_time = 0;
            $model->end_time = 0;
            $model->blocked_time=time();
            if($model->save()){
                $user->block_time = $model->time;
                $user->save(false);
            }
             return $this->redirect(['view', 'id' => $user->id]);
        } else {
            return $this->render('block', [
                'model' => $model,
                'user' => $user,
                'times' => $times
            ]);
        }
     }

    public function actionOnline()
    {
        $onfrom1 = null;

        if($_GET){
            if(htmlspecialchars($_GET["onfrom"]) == 'web')
                $onfrom1  = 'web';
            elseif(htmlspecialchars($_GET["onfrom"]) == 'android')
                $onfrom1 = 'android';
            elseif(htmlspecialchars($_GET["onfrom"]) == 'mobile')
                $onfrom1 = 'mobile';
        }

        $provider = new ActiveDataProvider([
            'query' => User::getOnlineUsers($onfrom = $onfrom1),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $searchModel = new UserSearch();
        $dataProvider = $provider;

        return $this->render('online', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function  actionDeleteShares($id)
    {
        $shares = Yii::$app->db->createCommand('SELECT attach,`time` FROM share WHERE user_id="'.$id.'"')->queryAll();

        foreach($shares as $share){
            if($share["attach"]!=""){
                $file_path = date("Ym",$share["time"]);
                $file_resized =  Yii::$app->basePath.'/../public_html/images/share/resized/'.$file_path.'/'.$share["attach"];
                $file_thumbs =   Yii::$app->basePath.'/../public_html/images/share/thumbs/'.$file_path.'/'.$share["attach"];
                $file_uploads =  Yii::$app->basePath.'/../public_html/images/share/uploads/'.$file_path.'/'.$share["attach"];

                if(file_exists($file_resized)) unlink($file_resized);
                if(file_exists($file_thumbs)) unlink($file_thumbs);
                if(file_exists($file_uploads)) unlink($file_uploads);
            }

        }

        Share::deleteAll('user_id=:id',[":id" => $id]);


        ShareComment::deleteAll('uid=:id',[":id" => $id]);

        ShareLike::deleteAll('uid=:id',[":id" => $id]);

        return $this->redirect(['index']);

    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $dir =  Yii::$app->basePath;
        $dir = $dir.'/../public_html/images/user/'.$id.'/';
        BaseFileHelper::removeDirectory($dir);
        UserImage::deleteAll(["user_id" => $id]);
        UserImageThumb::deleteAll(["user_id" => $id]);
        UserImageResized::deleteAll(["user_id" => $id]);

        $conversation_reply =  Yii::$app->db->createCommand('delete from conversation_reply where conversation_id in (select id from conversation where user_one=:id or user_two=:id)');
        $conversation_reply->bindParam(':id',$id);
        $conversation_reply->execute();

        Conversation::deleteAll('user_one=:id or user_two=:id',[":id" => $id]);

        UserVip::deleteAll('user_id=:id',[':id'=>$id]);

        Auth::deleteAll('user_id=:id',[':id'=>$id]);

        UserVisit::deleteAll('visit_from=:id or visit_to=:id',[":id" => $id]);

        UserPhotoUploadAsk::deleteAll('user_from=:id or user_to=:id',[":id" => $id]);

        UserLike::deleteAll('like_from=:id or like_to=:id',[":id" => $id]);

        UserFriend::deleteAll('user_1=:id or user_2=:id',[":id" => $id]);

        UserGift::deleteAll('gift_from=:id or gift_to=:id',[":id" => $id]);

        UserBlock::deleteAll('block_from=:id or block_to=:id',[":id" => $id]);

        UserActivity::deleteAll('user_id=:id',[":id" => $id]);

        $shares = Yii::$app->db->createCommand('SELECT attach,`time` FROM share WHERE user_id="'.$id.'"')->queryAll();

        foreach($shares as $share){
            if($share["attach"]!=""){
                $file_path = date("Ym",$share["time"]);
                $file_resized =  Yii::$app->basePath.'/../public_html/images/share/resized/'.$file_path.'/'.$share["attach"];
                $file_thumbs =   Yii::$app->basePath.'/../public_html/images/share/thumbs/'.$file_path.'/'.$share["attach"];
                $file_uploads =  Yii::$app->basePath.'/../public_html/images/share/uploads/'.$file_path.'/'.$share["attach"];

                if(file_exists($file_resized)) unlink($file_resized);
                if(file_exists($file_thumbs)) unlink($file_thumbs);
                if(file_exists($file_uploads)) unlink($file_uploads);
            }

        }
        Share::deleteAll('user_id=:id',[":id" => $id]);


        ShareComment::deleteAll('uid=:id',[":id" => $id]);

        ShareLike::deleteAll('uid=:id',[":id" => $id]);




        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTest()
    {
        echo 'test sehife';
        //return $this->redirect('index');
    }
}
