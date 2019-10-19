<?php

namespace app\controllers;

use app\models\answer\Answer;
use app\models\answer\AnswerSearch;
use Yii;
use app\models\questions\Questions;
use app\models\questions\QuestionsSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuestionController implements the CRUD actions for Questions model.
 */
class QuestionsController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Questions models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {

            Yii::$app->session->setFlash('error', "You are not log in!");
            return $this->redirect('http://app.test/site/login');


        }
        $searchModel = new QuestionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Questions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {

            Yii::$app->session->setFlash('error', "You are not log in!");
            return $this->redirect('http://app.test/site/login');


        }


        $searchModel = new AnswerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider -> query->where(['question_id'=>$id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Questions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {

            Yii::$app->session->setFlash('error', "You are not log in!");
            return $this->redirect('http://app.test/site/login');


        }

        $model = new Questions();




        if ($model->load(Yii::$app->request->post())){
            if(Questions::maxQuestions($model->quiz_id) && $model->save()){

                Yii::$app->session->setFlash('success', "Successfully created Question");
                return $this->redirect(['view', 'id' => $model->id]);

            }
            Yii::$app->session->setFlash('error', "You can't create much more Question! You can update or delete any Questions");
            return $this->redirect('create');







        }

        return $this->render('create', [
            'model' => $model,
        ]);


    }

    /**
     * Updates an existing Questions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {

            Yii::$app->session->setFlash('error', "You are not log in!");
            return $this->redirect('http://app.test/site/login');


        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Questions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {

            Yii::$app->session->setFlash('error', "You are not log in!");
            return $this->redirect('http://app.test/site/login');


        }
        $answers = Answer::find()->where(['in','question_id',$id])->all();
            foreach ($answers as $answer) {
                $answer->delete();
            }
        $this->findModel($id)->delete();


        return $this->redirect(['index']);
    }

    /**
     * Finds the Questions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Questions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Questions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
