<?php

namespace app\models;

use app\components\CoreProxy;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\base\DynamicModel;
use yii\web\UnprocessableEntityHttpException;
use Yii;

class Payment extends DynamicModel
{
    public $attrs;
    public $operation_id;
    public $body = [];

    public function paymentValidate() //используется как правило валидации для PaymentForm
    {
        try {
            $this->body['service_id'] = Yii::$app->session['idPayment'];
            foreach ($this->attrs as $attr) {
                $this->body['fields'][$attr] = $this->$attr;
            }
            $response = CoreProxy::PaymentValidate($this->body);
            $this->body = json_decode($response->content, true);
        } catch (UnprocessableEntityHttpException $e){
            $error = json_decode($e->getMessage(), true);
            if (in_array($error[0]['field'], $this->attrs)) {
                $this->addErrors([$error[0]['field'] => $error[0]['message']]);
            }
            else {
                throw new InternalErrorException($e);
            }
            return false;
        }

    }

    public function makePayment()
    {
        $response = CoreProxy::makePayment($this->body);
        $operation_id = json_decode($response->content, true)['operation']['id'];
        return $operation_id;
    }

    public function pay() //PSR 1-4,7, статичный размер для, центровку по картинке, базовая картинка template, пагинация jquery
    {
        try {
            $this->operation_id = $this->makePayment();
            $check = new Check($this->operation_id);
        } catch (InternalErrorException $e) { //показывать id операции 11 status, если чек приход долго, обратитесь в службу поддержки
            sleep(3);
            $this->operation_id = $this->makePayment();
            $check = new Check($this->operation_id);
        }
        return $check;
    }
}