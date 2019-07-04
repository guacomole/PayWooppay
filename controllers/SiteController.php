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

    public function actions()
    {
        $this->layout = 'basic';
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            // ...
        ];
    }
    public function actionAuth()
    {
        $this->layout = 'basic';
        $this->view->title = 'Авторизация!';
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
        return $this->redirect(Url::to(['auth']));
    }

}