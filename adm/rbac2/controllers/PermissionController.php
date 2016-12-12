<?php

namespace adm\rbac2\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use adm\rbac2\models\AuthItem;
use adm\rbac2\models\AuthItemSearch;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class PermissionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $params = Yii::$app->request->queryParams;
        $params[$searchModel->formName()]['type'] = 2;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new AuthItem();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            foreach ($post['group'] as $k => $group) {

                if (empty($group) || empty($post['name'][$k])) {
                    continue;
                }
                //创建权限组
                $group_name = AuthItem::createGroup($group);

                //创建权限
                AuthItem::createPermission($group_name, $post['name'][$k], $post['description'][$k]);
            }
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $post[$model->formName()]['updated_at'] = time();
            if ($model->load($post) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->name]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
