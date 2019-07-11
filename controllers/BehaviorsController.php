<?php


namespace app\controllers;


use app\models\Profile;
use yii\web\Controller;
use yii\filters\AccessControl;
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
                        'actions' => ['auth', 'error'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['payment', 'site'],
                        'actions' => ['service', 'category', 'payment', 'logout', 'check', 'pay'],
                        'matchCallback' => function($rule, $action){
                            if ( isset(Yii::$app->session['token']) and Yii::$app->session['token']){
                                $balance = Profile::getBalance();
                                Yii::$app->session['balance'] = $balance;
                                return true;
                            } else {
                                Yii::$app->session->setFlash('error',
                                   'Авторизуйтесь, если у вас есть кошелёк, или создайте его на <a href="https://www.wooppay.com/services">wooppay.kz</a>');
                                return false;
                            }
                        }
                    ],

                ],
            ],
        ];
    }
}