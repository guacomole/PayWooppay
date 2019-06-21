<?php


namespace app\controllers;

use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\CoreProxy;
use yii\web\ServerErrorHttpException;
use Yii;

class BehaviorsController extends Controller
{

    public function behaviors() //если есть template не показывать
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['auth'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['payment', 'site'],
                        'actions' => ['service', 'category', 'payment', 'logout'],
                        'matchCallback' => function($rule, $action){
                            try {
                                return CoreProxy::isAuth();
                            }
                            catch (InternalErrorException $e){
                                Yii::$app->session->setFlash('error', $e->getMessage());
                                return false;
                            }
                            catch (ServerErrorHttpException $e){
                                Yii::$app->session->setFlash('error',
                                    'Авторизуйтесь, если у вас есть кошелёк, или создайте его на <a href="https://www.wooppay.com/services">wooppay.kz</a>');
                                return false;
                            }
                            catch (Exception $e) {
                                Yii::$app->session->setFlash('error', $e->getMessage());
                                return false;
                            }
                        }
                    ],
                ],
            ],
        ];
    }
}