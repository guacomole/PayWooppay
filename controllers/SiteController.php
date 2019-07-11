<?php


namespace app\controllers;

use app\forms\LoginForm;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\httpclient\Exception;
use Yii;
use yii\web\Controller;
use yii\web\UnprocessableEntityHttpException;

class SiteController extends BehaviorsController
{
    public $layout = 'inside';

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception != null and $exception->statusCode == 401) {
            Yii::$app->session->setFlash('error',
                'Авторизуйтесь, если у вас есть кошелёк, или создайте его на <a href="https://www.wooppay.com/services">wooppay.kz</a>');
            return $this->redirect(['site/auth']);
        } else {
            $this->layout = 'basic';
            return $this->render('error', ['exception' => $exception]);
        }
    }

    public function actionAuth()
    {
        $this->layout = 'basic';
        $this->view->title = 'Вход';
        $model = new LoginForm();
        if ( Yii::$app->request->isPost ) {
            if ($model->load(Yii::$app->request->post()) and $model->validate()) {
                return $this->redirect(['payment/category']);
            }
        }
        return $this->render('auth', compact('model'));
    }

    public function actionLogout()
    {
        Yii::$app->session->destroy();
        Yii::$app->session->setFlash('error', 'Авторизуйтесь, если у вас есть кошелёк, или создайте его на 
                    <a href="https://www.wooppay.com/services">wooppay.kz</a>');
        return $this->redirect(Url::to(['auth']));
    }

}