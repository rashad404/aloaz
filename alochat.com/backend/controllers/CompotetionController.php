<?php

namespace backend\controllers;

use common\models\CompotetionImages;
use common\models\CompotetionImagesSearch;
use Yii;
use common\models\Compotetion;
use common\models\CompotetionSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompotetionController implements the CRUD actions for Compotetion model.
 */
class CompotetionController extends Controller
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
     * Lists all Compotetion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompotetionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Compotetion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $db = Yii::$app->db;
        $allImages = [];
        $images = $db -> createCommand('SELECT * FROM compotetion_images WHERE compotetion_id=:cid')->bindValues([":cid" => $id])->queryAll();
        foreach($images  as $key=>$image){
            $allImages[$key]["user_id"] = $image["user_id"];
            $allImages[$key]["user_image_id"] = $image["user_image_id"];
            $allImages[$key]["like_count"] = $image["like_count"];
            $allImages[$key]["status"] = $image["status"];
            $allImages[$key]["image_time"] = $image["image_time"];

            $user = $db->createCommand('SELECT nickname,profile_photo FROM `user` WHERE id=:id')->bindValue(":id",$image["user_id"])->queryOne();
            $allImages[$key]["nickname"] = $user["nickname"];
            $allImages[$key]["profile_photo"] = $user["profile_photo"];

            $user_image = $db->createCommand('SELECT * FROM `user_image` WHERE `id`=:image_id')->bindValue(":image_id",$image["user_image_id"])->queryOne();
            $allImages[$key]["path"] = $user_image["path"];

        }

        $searchModel = new CompotetionImagesSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params,$id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'images' => $allImages,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Creates a new Compotetion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Compotetion();

        if ($model->load(Yii::$app->request->post())) {

            $model->start_date  = date("Y-m-d",strtotime($model->start_date));
            $model->end_date  = date("Y-m-d",strtotime($model->end_date));

            if ($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Compotetion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->start_date = date("d-M-Y",strtotime($model->start_date));
        $model->end_date = date("d-M-Y",strtotime($model->end_date));
        if ($model->load(Yii::$app->request->post())) {
            $model->start_date  = date("Y-m-d",strtotime($model->start_date));
            $model->end_date  = date("Y-m-d",strtotime($model->end_date));

            if ($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Compotetion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteImage($id)
    {
        $model = $this->findModelCompotetionImages($id);
        $c_id = $model->compotetion_id;
        $model->delete();

        return $this->redirect(['/compotetion/view?id='.$c_id]);
    }

    /**
     * Finds the CompotetionImages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompotetionImages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCompotetionImages($id)
    {
        if (($model = CompotetionImages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Compotetion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Compotetion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Compotetion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
