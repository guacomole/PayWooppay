<?php


namespace app\models;


use app\components\CoreProxy;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

class Check extends Model
{

    public $checkInPDF;

    public function __construct($id)
    {
       $response = $this->getBankCheck($id);
       $this->checkInPDF = $response;
       return $this;
    }
    public function getBankCheck($operation_id)
    {
        $response = CoreProxy::getBankCheck($operation_id);
        $response = json_decode($response->content, true);
        if ($response['transaction']['status'] == 14){
            $response = CoreProxy::getCheckInPDF($operation_id)->content;
            return $response;
        }
        else
            throw new ServerErrorHttpException('Невозможно произвести оплату. Попробуйте позже.');
    }
}