<?php

namespace backend\controllers;

use Yii;
use common\models\Gift;
use common\models\GiftSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * GiftController implements the CRUD actions for Gift model.
 */
class GiftController extends Controller
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
     * Lists all Gift models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GiftSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Gift model.
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
     * Creates a new Gift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Gift();

        $model->status  = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $icon  = UploadedFile::getInstance($model,'icon');

            if($icon){
                $imagesDir = Yii::$app->basePath.'/../public_html/images/gifts/';
                // Save original image
                $image_icon = $imagesDir . $model->id . ".jpg";

                $icon->saveAs($image_icon);
                $model->icon  = "/images/gifts/" . $model->id . ".jpg";

                $model->save(false);

            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Gift model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $iconOld =  $model->icon;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $icon  = UploadedFile::getInstance($model,'icon');

            if($icon!=NULL){
                $imagesDir = Yii::$app->basePath.'/../public_html/images/gifts/';
                // Save original image
                $image_icon = $imagesDir . $model->id . ".".$icon->extension;

                $icon->saveAs($image_icon);
                $model->icon  = "/images/gifts/" . $model->id . ".".$icon->extension;

                $model->save(false);

            } else {
                $model->icon = $iconOld;
                $model->save(false);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Gift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);


        $imagesDir = Yii::$app->basePath.'/../public_html';

        if ($model->icon && file_exists($imagesDir . $model->icon))
            unlink($imagesDir . $model->icon);

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Gift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Gift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gift::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
