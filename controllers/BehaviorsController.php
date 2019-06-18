<?php


namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\CoreProxy;
use yii\web\ServerErrorHttpException;

class BehaviorsController extends Controller
{

    public function behaviors()
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
                            catch (ServerErrorHttpException $e){
                                return false;
                            }
                        }
                    ],
                ],
            ],
        ];
    }
}