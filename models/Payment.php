<?php

namespace app\models;

use app\components\CoreProxy;
use yii\base\DynamicModel;

class Payment extends DynamicModel
{
    public $names;
    public $params;
    public function __construct(array $names, array $params)
    {
        $this->params = $params;
        $this->names = $names;
        parent::__construct($names);
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            foreach ($this->names as $name) {
                if ( isset($this->params[$name]['mask']) ) {
                    $this->$name = substr(preg_replace("/[^0-9,.]/", "", $this->$name), 1);
                }
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function paymentValidate($id)
    {
        $body = [];
        $body['service_id'] = $id;
        foreach ($this->names as $attr){
            $body['fields'][$attr] = $this->$attr;
        }
        $response = CoreProxy::PaymentValidate($body);
        return json_decode($response->content, true);
    }

    public function makePayment($id)
    {
        $body = $this->paymentValidate($id);
        $response = CoreProxy::makePayment($body);
        $operation_id = json_decode($response->content, true)['operation']['id'];
        return $operation_id;
    }

    public function getBankCheck($operation_id)
    {
        $response = CoreProxy::getBankCheck($operation_id);
        return json_decode($response->content, true);
    }

    public function pay($id)
    {
        $operation_id = $this->makePayment($id);
        $response = $this->getBankCheck($operation_id);
        return $response;
    }
}