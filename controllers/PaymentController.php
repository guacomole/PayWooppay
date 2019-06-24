<?php


namespace app\controllers;

use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\base\UnknownPropertyException;
use app\forms\PaymentForm;
use app\models\Category;
use app\models\Service;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Exception;


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
        $this->view->title = 'Оплата';
        Yii::$app->session['idPayment'] = $id;
        try{
            $paymentModel = new PaymentForm($id);
            $paymentModel = $paymentModel->getRules();
            if ( (Yii::$app->request->isPost) ) {
                if ($paymentModel->load(Yii::$app->request->post()) and $paymentModel->validate()) {
                    $check = $paymentModel->pay($id); // 11 status - new op, 14 stat - vse horowo,
                    return $this->render('check', compact('check'));
                }
            }
            return $this->render('payment', compact('paymentModel'));
        } catch(InternalErrorException $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->render('payment', compact('paymentModel'));
        } catch (\Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->render('payment');

        }
    }
}