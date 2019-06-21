<?php


namespace app\components;


use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\web\BadRequestHttpException;
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

    public static function  get($url, $body=[], $headers=[]){
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
            $response = $request -> send();
        } catch (Exception $e){
            throw new InternalErrorException('Непредвиденные технические проблемы. Пожалуйста, попробуйте позже.');
        }
        if( !$response->isOk ){
            if ( isset(json_decode($response->content,true)['0']['field'])) {
                throw new UnprocessableEntityHttpException($response->content);
            }
            else {
                throw new ServerErrorHttpException($response->content);
            }
        }
        return $response;
    }
}