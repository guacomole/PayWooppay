<?php


namespace app\controllers;

use app\forms\PaymentForm;
use app\models\Category;
use app\models\Service;
use Yii;
use app\myExceptions\BadPayException;
use yii\data\Pagination;
use app\models\Payment;
use app\models\Check;
use yii\filters\VerbFilter;
use yii\web\Controller;

class PaymentController extends Controller//BehaviorsController
{
    public $layout = 'inside';

   /* public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'check' => ['post', 'get'],
            ],
        ];
        return $behaviors;
    }*/

    public function actionCategory()
    {
        $this->view->title = 'Категории';
        $categoryModel = new Category();
        $categories = $categoryModel->find();
        return $this->render('category', compact('categories'));
    }

    /**
     * @param null $page
     * @return string|\yii\web\Response
     */
    public function actionService($page = null, $category_id = null)
    {
        $this->view->title = 'Сервисы';
        $serviceModel = new Service();
        $services = $serviceModel->find($page, null, $category_id);
        $pages = new Pagination(['totalCount' => $serviceModel->totalCount]);
        if (empty($services)) {
            Yii::$app->session->setFlash('error', 'Тут пока что нет сервисов.');
        }
        return $this->render('service', compact('services', 'pages'));
    }

    public function actionPayment($id)
    {
        try {
            $this->view->title = 'Оплата';
            Yii::$app->session['idPayment'] = $id;
            $paymentModel = new PaymentForm($id);
            if (Yii::$app->request->isPost) {
                if ($paymentModel->load(Yii::$app->request->post()) and $paymentModel->validate()) {
                    return $this->render('verification', compact('paymentModel'));
                }
            }
            return $this->render('payment', compact('paymentModel'));
        } catch (BadPayException $e) {
            $error =  $e->getMessage();//'Невозможно произвести платёж. Попробуйте позже.';
            return $this->render('payment', compact('paymentModel', 'error'));
        }
    }

    public function actionCheck()
    {
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isPost  ){
            $operation_id = Yii::$app->request->post('operation_id');
            return $this->redirect(['service']);
        }/* elseif (Yii::$app->session->hasFlash('body')) {
            $operation_id = Payment::pay(Yii::$app->session->getFlash('body'));
        }*/
        $check = new Check($operation_id=50387723);
        return $this->render('check', compact('check', 'post'));
    }
}