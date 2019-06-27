<?php


namespace app\models;


use app\components\CoreProxy;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

class Check extends Model
{

    public $checkInPDF;
    public $operation_id;
    public $service_title;
    public $amount;
    public $commission;
    public $admit;
    public $time;
    public $ident = '';

    public function __construct($operation_id)
    {
        $this->operation_id = $operation_id;
        $response = $this->getBankCheck($operation_id);
        if ( $response ) {
            $this->operation_id = $response['id'];
            $this->service_title = $response['service_title'];
            $this->amount = $response['amount'];
            $this->commission = $response['commission'];
            $this->admit = $response['admit'];
            $this->time = $response['time'];
            foreach ($response['ident'] as $item) {
                $this->ident = $this->ident . $item['title'] . ': ' . $item['value'] . '<br>';

            }
        }
        return $this;
    }
    public function getBankCheck($operation_id, $badstatus = false)
    {
        $response = CoreProxy::getBankCheck($operation_id);
        $response = json_decode($response->content, true);
        if ($response['transaction']['status'] == 14) {
            $this->checkInPDF = CoreProxy::getCheckInPDF($operation_id)->content;
            return $response;
        } elseif ($response['transaction']['status'] == 11) {
            sleep(3);
        } else {
            throw new InternalErrorException('Невозможно произвести платёж.');
        }
        if ( $badstatus ) return false;
        return $this->getBankCheck($operation_id, true);
    }

}