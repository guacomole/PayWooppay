<?php


namespace app\controllers;

use Symfony\Component\CssSelector\Exception\InternalErrorException;
use app\forms\PaymentForm;
use app\models\Category;
use app\models\Service;
use Yii;

class PaymentController extends BehaviorsController
{
    public $layout = 'inside';
    /**
     * @return string|\yii\web\Response
     */
    public function actionCategory()
    {
        try {
        $this->view->title = 'Категории';
        $categoryModel = new Category();
        $categories = $categoryModel->find();
        return $this->render('category', compact( 'categories') );
        } catch (\Exception $e){
            Yii::$app->session->setFlash('error', 'Невозможно отобразить содержимое страницы.');
            return $this->render('category');

        }

    }

    /**
     * @param null $page
     * @return string|\yii\web\Response
     */
    public function actionService($page = null, $category_id=null)
    {
        try {
            $this->view->title = 'Сервисы';
            $serviceModel = new Service();
            $services = $serviceModel->find($page, null, $category_id);
            $pageCount = $serviceModel->pageCount;
            return $this->render('service', compact('pageCount', 'services'));
        } catch (\Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->render('service');

        }

    }

    public function actionPayment($id)
    {
        try{
            $this->view->title = 'Оплата';
            Yii::$app->session['idPayment'] = $id;
            $paymentModel = new PaymentForm($id);
            if ( Yii::$app->request->isPost ) {
                if ($paymentModel->load(Yii::$app->request->post()) and $paymentModel->validate()) {
                    $check = $paymentModel->pay($id); // 11 status - new op, 14 stat - vse horowo,
                    return $this->render('check', compact('check'));
                }
            }
            return $this->render('payment', compact('paymentModel'));
        } catch(InternalErrorException $e){
            Yii::$app->session->setFlash('error', 'Невозможно произвести платёж.');
            return $this->render('payment', compact('paymentModel'));
        } catch (\Exception $e){
            Yii::$app->session->setFlash('error', 'Невозможно отобразить услугу.');//'Невозможно отобразить услугу.');
            return $this->render('payment');

        }
    }
}