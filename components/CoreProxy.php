<?php


namespace app\components;


use app\components\RestClient;
use http\Client;
use Yii;
use yii\helpers\Url;

class CoreProxy
{
    const API_URL = 'https://api.yii2-stage.test.wooppay.com/v1/';
    const AUTH_URL = 'https://api.yii2-stage.test.wooppay.com/v1/auth';
    const HISTORY_URL = 'https://api.yii2-stage.test.wooppay.com/v1/history';
    const SERVICE_URL = 'https://api.yii2-stage.test.wooppay.com/v1/service';
    const CATEGORY_URL = 'https://api.yii2-stage.test.wooppay.com/v1/service-category';

    public static function auth($login, $password)
    {
        $body = [
          'login' => $login,
          'password' =>  $password
        ];
        $response = RestClient::post(self::AUTH_URL, $body);
        return $response;
    }

    public static function profileInfo()
    {
        $headers = ['Authorization' => Yii::$app->session['token']];
        $response = RestClient::get(self::AUTH_URL, $body = [], $headers);
        return $response;
    }

    public static function getHistory()
    {
        $headers = ['Authorization' => Yii::$app->session['token']];
        $response = RestClient::get(self::HISTORY_URL, $body = [], $headers);
        return $response;
    }

    public static function getService($page, $id, $category_id)
    {
        $headers = ['Authorization' => Yii::$app->session['token']];
        if ($category_id and $page){
            $url = substr(Url::to([self::SERVICE_URL, 'category_id' => $category_id, 'page' => $page]), 1);
        }
        elseif ($id) {
            $url = substr(Url::to([self::SERVICE_URL . '/' . $id, 'expand' => 'fields.validations']), 1);
        }
        elseif ($page){
            $url = substr(Url::to([self::SERVICE_URL, 'page' => $page]), 1);
        }
        elseif ($category_id){
            $url = substr(Url::to([self::SERVICE_URL, 'category_id' => $category_id]), 1);
        }
        elseif ($category_id and $page){
            $url = substr(Url::to([self::SERVICE_URL, 'category_id' => $category_id, 'page' => $page]), 1);
        }
        else {
            $url = self::SERVICE_URL;
        }
        $response = RestClient::get($url, $body = [], $headers);
        return $response;
    }

    public static function getCategories($parentId)
    {
        $url = substr(Url::to([self::CATEGORY_URL, 'parent_id' => $parentId]), 1);
        $response = RestClient::get($url, $body =[]);
        return $response;
    }

}