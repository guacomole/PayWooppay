<?php


namespace app\models;


use app\components\CoreProxy;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

class Check extends Model
{
    public $receipt;
    public $service_title;
    public $ident = [];
    public $amount;
    public $admit;
    public $commission;
    public $time;
    public $checkOnPrint;

    public function __construct($id)
    {
       $response = $this->getBankCheck($id);
       $this->receipt = $response['id'];
       $this->service_title = $response['service_title'];
       $this->amount = $response['amount'];
       $this->commission = $response['commission'];
       $this->admit = $response['admit'];
       $this->time = $response['time'];
       $this->checkOnPrint = CoreProxy::getCheckOnPrint($id);
       foreach ($response['ident'] as $item){
           if ($item['title'] != 'Внешний ID') {
               $this->ident[$item['title']] = $item['value'];
           }
       }
       return $this;
    }
    public function getBankCheck($operation_id)
    {
        $response = CoreProxy::getBankCheck($operation_id);
        $response = json_decode($response->content, true);
        if ($response['transaction']['status'] == 14) return $response;
        else throw new ServerErrorHttpException('Невозможно произвести оплату. Попробуйте позже.');

    }
}