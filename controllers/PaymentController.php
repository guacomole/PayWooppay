<?php


namespace app\controllers;

use yii\base\UnknownPropertyException;
use app\forms\PaymentForm;
use app\models\Category;
use app\models\Service;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Exception;
use yii\web\ServerErrorHttpException;

class PaymentController extends BehaviorsController
{
    public $layout = 'inside';
    /**
     * @return string|\yii\web\Response
     */
    public function actionCategory()
    {
        $this->view->title = 'Категории';
        try{
            $categoryModel = new Category();
            $categories = $categoryModel->find();
            return $this->render('category', compact( 'categories') );

        }catch(ServerErrorHttpException $e) {
            Yii::$app->session['error'] = json_decode($e->getMessage(), true);
            return $this->redirect(Url::to(['/site/auth']));
        }
    }

    /**
     * @param null $page
     * @return string|\yii\web\Response
     */
    public function actionService($page = null, $category_id=null)
    {
        $this->view->title = 'Сервисы';
        try{
            $serviceModel = new Service();
            $services = $serviceModel->find($page, null, $category_id);
            $pageCount = $serviceModel->pageCount;
            return $this->render('service', compact( 'pageCount', 'services') );

        }catch(ServerErrorHttpException $e) {
            Yii::$app->session['error'] = json_decode($e->getMessage(), true);
            return $this->redirect(Url::to(['/site/auth']));
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
                else{
                    Yii::$app->session->setFlash('error', 'Ошибка данных.');
                }
            }
            return $this->render('payment', compact('paymentModel'));
        }
        catch(ServerErrorHttpException $e) {
            $error = json_decode($e->getMessage(), true);
            if (isset($error['status']) and $error['status'] == 401) {
                Yii::$app->session->setFlash('error', $error);
                return $this->redirect(Url::to(['/site/auth']));
            } elseif (isset($error[0]['field']) and isset($error[0]['message']) and $error[0]['field'] != 'fields') {
                $paymentModel->addError($error[0]['field'], $error[0]['message']);
                return $this->render('payment', compact('paymentModel'));
            }
        } catch (UnknownPropertyException $e){
            Yii::$app->session->setFlash('error', $e->getMessage() );
            return $this->render('payment');

        } catch (Exception $e) {
            $session['error'] = $e->getMessage();
            return $this->redirect(Url::to(['/site/auth']));
        }
    }
}