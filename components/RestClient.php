<?php


namespace app\components;


use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\web\ServerErrorHttpException;

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
            throw new ServerErrorHttpException('Непредвиденные технические проблемы');
        }

        if( !$response->isOk ){
            throw new ServerErrorHttpException($response->content);
        }
        return $response;

    }
}