<?php


namespace app\controllers;

use yii\web\Controller;
use app\forms\PaymentForm;
use app\models\Category;
use app\models\Service;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Exception;
use yii\web\ServerErrorHttpException;

class PaymentController extends Controller
{
    public $layout = 'inside';
    /**
     * @return string|\yii\web\Response
     */
    public function actionCategory()
    {
        try{
            $categoryModel = new Category();
            $categoryModel->find();
            return $this->render('category', compact( 'categoryModel') );

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
        Yii::$app->session['idPayment'] = $id;
        try{
            $model = new PaymentForm($id);
            $paymentModel = $model->getRules();
            if ( (Yii::$app->request->isPost) ) {
                if ($paymentModel->load(Yii::$app->request->post()) and $paymentModel->validate()) {
                    $response = $paymentModel->pay($id);
                    $success = 'УСПЕШНО!';
                    return $this->render('payment', compact('paymentModel', 'model', 'response', 'success'));
                }
                else{
                    $error = $model->errors;
                    return $this->render('payment', compact('paymentModel','model', 'error'));
                }
            }
            return $this->render('payment', compact('paymentModel','model'));
        } catch(ServerErrorHttpException $e) {              //создать модель для обработки месседжей ошибок
            $error = json_decode($e->getMessage(), true);
            if( isset($error['status']) and $error['status'] == 401) {
                $session['error'] = $e->getMessage();
                return $this->redirect(Url::to(['/site/auth']));
            }
            else{
                Yii::$app->session->setFlash('error', $error);
                return $this->render('payment', compact('paymentModel','model'));
            }
        } catch (Exception $e) {
            $session['error'] = $e->getMessage();
            return $this->redirect(Url::to(['/site/auth']), compact('model'));
        }
    }
}