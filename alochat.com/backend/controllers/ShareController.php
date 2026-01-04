<?php

namespace backend\controllers;

use common\models\ShareComment;
use common\models\ShareLike;
use Yii;
use common\models\Share;
use common\models\ShareSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShareController implements the CRUD actions for Share model.
 */
class ShareController extends Controller
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
                        'actions' => ['index','view','create','update','delete'],
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
     * Lists all Share models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShareSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Share model.
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
     * Creates a new Share model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Share();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Share model.
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
     * Deletes an existing Share model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $share = Yii::$app->db->createCommand('SELECT attach,`time` FROM share WHERE id="'.$id.'"')->queryOne();

        if($share['attach']!=""){
            $file_path = date("Ym",$share["time"]);
            $file_resized =  Yii::$app->basePath.'/../public_html/images/share/resized/'.$file_path.'/'.$share["attach"];
            $file_thumbs =   Yii::$app->basePath.'/../public_html/images/share/thumbs/'.$file_path.'/'.$share["attach"];
            $file_uploads =  Yii::$app->basePath.'/../public_html/images/share/uploads/'.$file_path.'/'.$share["attach"];

            if(file_exists($file_resized)) unlink($file_resized);
            if(file_exists($file_thumbs)) unlink($file_thumbs);
            if(file_exists($file_uploads)) unlink($file_uploads);
        }



        Share::deleteAll('id=:id',[":id" => $id]);


        ShareComment::deleteAll('sid=:id',[":id" => $id]);

        ShareLike::deleteAll('sid=:id',[":id" => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Share model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Share the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Share::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
