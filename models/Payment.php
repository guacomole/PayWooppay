<?php

namespace app\models;

use app\components\CoreProxy;
use yii\base\DynamicModel;

class Payment extends DynamicModel
{
    public $attrs;
    public $params;
    public $labels;
    public function __construct(array $attrs, array $params, array $labels)
    {
        $this->params = $params; // два атрибута для очистки маски в beforeValidate
        $this->attrs = $attrs;
        $this->labels = $labels;
        parent::__construct($attrs); //создание динамической модели
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            foreach ($this->attrs as $attr) {
                if ( isset($this->params[$attr]['mask']) ) {
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
        foreach ($this->attrs as $attr){
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