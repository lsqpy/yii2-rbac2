<?php

namespace adm\rbac2\controllers;


use Yii;
use adm\rbac2\models\AuthItem;
use adm\rbac2\models\AuthItemSearch;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class RoleController extends Controller
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
        $params[$searchModel->formName()]['type'] = 1;
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
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $post[$model->formName()]['type'] = Item::TYPE_ROLE;
            if ($model->load($post) && $model->save()) {
                $auth = Yii::$app->authManager;

                //插入权限
                foreach ($post["auth_access"] as $auth_acces) {
                    $parent = $auth->createRole($model->name);
                    $child = $auth->createPermission($auth_acces);
                    $auth->addChild($parent, $child);
                }

                return $this->redirect(['index']);
            }
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
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->save()) {

            //删除权限
            $auth = Yii::$app->authManager;
            $auth->removeChildren($model);

            //插入权限
            foreach($post["auth_access"] as $auth_acces){
                $parent = $auth->createRole($model->name);
                $child = $auth->createPermission($auth_acces);
                $auth->addChild($parent, $child);
            }

            return $this->redirect(['view', 'id' => $model->name]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
