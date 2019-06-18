<?php


namespace app\controllers;

use app\forms\LoginForm;
use yii\helpers\Url;
use yii\httpclient\Exception;
use Yii;
use yii\web\ServerErrorHttpException;

class SiteController extends BehaviorsController
{
    public $layout = 'inside';

    public function actionAuth()
    {
        $this->layout = 'basic';
        $this->view->title = 'Авторизация!';
        $session = Yii::$app->session;
        $model = new LoginForm();
        if ( Yii::$app->request->isPost ) {
            if ( $model->load(Yii::$app->request->post()) and $model->validate()) {
                try {
                    $model->login();
                    return $this->redirect(['payment/category']);
                } catch (ServerErrorHttpException $e) {
                    $session['error'] = $e->getMessage();
                    return $this->render('auth', compact('model'));
                } catch (Exception $e) {
                    $session['error'] = $e->getMessage();
                    return $this->render('auth', compact('model'));
                }
            }else {
                $session['error'] = $model->errors;
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