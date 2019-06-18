<?php

namespace app\models;

use app\components\CoreProxy;
use yii\base\DynamicModel;
use yii\web\ServerErrorHttpException;

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
                    $this->$attr = substr(preg_replace("/[^0-9,.]/", "", $this->$attr), 1);
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

    public function pay($id)
    {
        $operation_id = $this->makePayment($id);
        try {
            $check = new Check($operation_id);
        }
        catch (ServerErrorHttpException $e) {
            sleep(3);
            $operation_id = $this->makePayment($id);
            $check = new Check($operation_id);
        }
        return $check;
    }
}