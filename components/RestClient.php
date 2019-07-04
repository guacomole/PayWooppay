<?php


namespace app\components;


use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class RestClient
{

    public static function post($url, $body=[], $headers=[]){
        $client = new Client();
        $request = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($body)
            ->addHeaders($headers);
        $response = self::sendRequest($request);
        return $response;
    }

    public static function get($url, $body=[], $headers=[]){
        $client = new Client();
        $request = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData($body)
            ->addHeaders($headers);
        $response = self::sendRequest($request);
        return $response;
    }

    private static function sendRequest($request)
    {
        try{
            $response = $request->send();
            Yii::$app->session['response'] = $response->getStatusCode();
        } catch (Exception $e){
            throw new InternalErrorException('Непредвиденные технические проблемы. Пожалуйста, попробуйте позже.', 500);
        }
        if( !$response->isOk ){
            if ($response->getStatusCode() == 422){
                throw new UnprocessableEntityHttpException($response->content);
            } elseif ( $response->getStatusCode() == 404) {
                throw new NotFoundHttpException($response->content);
            } else {
                throw new ServerErrorHttpException($response);
            }
        }
        if( !$response->content ){
            throw new InternalErrorException('Пустое тело ответа.');
        }
        return $response;
    }
}