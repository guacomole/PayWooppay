<?php


namespace app\controllers;

use app\forms\PaymentForm;
use app\models\Category;
use app\models\Service;
use Yii;
use app\myExceptions\BadPayException;
use yii\data\Pagination;

class PaymentController extends BehaviorsController
{
    public $layout = 'inside';
    /**
     * @return string|\yii\web\Response
     */
    public function actionCategory()
    {
        $this->view->title = 'Категории';
        $categoryModel = new Category();
        $categories = $categoryModel->find();
        return $this->render('category', compact( 'categories') );
    }

    /**
     * @param null $page
     * @return string|\yii\web\Response
     */
    public function actionService($page = null, $category_id=null)
    {
        $this->view->title = 'Сервисы';
        $serviceModel = new Service();
        $services = $serviceModel->find($page, null, $category_id);
        $pages = new Pagination(['totalCount' => $serviceModel->totalCount]);
        if ( empty($services) ){
            Yii::$app->session->setFlash('error', 'Тут пока что нет сервисов.');
        }
        return $this->render('service', compact( 'services', 'pages'));
    }

    public function actionPayment($id)
    {
        try{
            $this->view->title = 'Оплата';
            Yii::$app->session['idPayment'] = $id;
            $paymentModel = new PaymentForm($id);
            if ( Yii::$app->request->isPost ) {
                if ($paymentModel->load(Yii::$app->request->post()) and $paymentModel->validate()) {
                    $check = $paymentModel->pay(); // 11 status - new op, 14 stat - vse horowo,
                    return $this->render('check', compact('check'));
                }
            }
            return $this->render('payment', compact('paymentModel'));
        } catch(BadPayException $e){
            $error = 'Невозможно произвести платёж. Попробуйте позже.';
            return $this->render('payment', compact('paymentModel', 'error'));
        }
    }
}