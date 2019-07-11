<?php

namespace app\models;

use app\components\CoreProxy;
use yii\base\DynamicModel;
use yii\web\UnprocessableEntityHttpException;
use Yii;
use app\myExceptions\BadPayException;

class Payment extends DynamicModel
{
    public $attrs;
    public $operation_id;
    public $commission;
    public $total_sum;

    public function paymentValidate()  //используется как правило валидации для PaymentForm
    {
        try {
            $body['service_id'] = Yii::$app->session['idPayment'];
            foreach ($this->attrs as $attr) {
                $body['fields'][$attr] = $this->$attr;
            }
            $response = CoreProxy::paymentValidate($body);
            $this->commission = json_decode(CoreProxy::getCommission(Yii::$app->session['idPayment'], $this->amount), true);
            $this->commission ? null : $this->commission = 0;
            $this->total_sum = $this->commission + $this->amount;
            $body = json_decode($response->content, true);
            Yii::$app->session->setFlash('body', $body);
        } catch (UnprocessableEntityHttpException $e){
            $error = json_decode($e->getMessage(), true);
            if (in_array($error[0]['field'], $this->attrs)) {
                $this->addErrors([$error[0]['field'] => $error[0]['message']]);
            } else {
                throw new BadPayException('Невозможно произвести платёж. Попробуйте позже.');
            }
            return false;
        }
    }

    public static function pay($body) //PSR 1-4,7, статичный размер для, центровку по картинке, базовая картинка template, пагинация jquery
    {
        $response = CoreProxy::makePayment($body);
        $operation_id = json_decode($response->content, true)['operation']['id'];
        return $operation_id;
    }
}