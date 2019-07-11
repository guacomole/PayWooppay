<?php


namespace app\controllers;

use app\forms\CheckForm;
use app\forms\PaymentForm;
use app\models\Category;
use app\models\Service;
use Yii;
use app\myExceptions\BadPayException;
use yii\data\Pagination;
use app\models\Payment;
use app\models\Check;
use app\models\Profile;

class PaymentController extends BehaviorsController
{
    public $layout = 'inside';


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
            $error = $e->getMessage();//'Невозможно произвести платёж. Попробуйте позже.';
            return $this->render('payment', compact('paymentModel', 'error'));
        }
    }


    public function actionCheck()
    {
        try {
            $this->view->title = 'Чек';
            $model = new CheckForm();
            if (Yii::$app->request->isPost) { //повторный запрос чека
                if ($model->load(Yii::$app->request->post())) {
                    $operation_id = $model->operation_id;
                }
            } elseif (Yii::$app->session->hasFlash('body')) {
                $operation_id = Payment::pay(Yii::$app->session->getFlash('body'));
                $balance = Profile::getBalance();
                Yii::$app->session['balance'] = $balance;
            } else {
                return $this->redirect(['service']);
            }
            $check = new Check($operation_id);
            return $this->render('check', compact('check', 'model'));
        }catch (BadPayException $e){
            $error = $e->getMessage();
            Yii::$app->session->setFlash('error', $error);
            return $this->redirect(['payment', 'id' => Yii::$app->session['idPayment']]);
        }
    }

}