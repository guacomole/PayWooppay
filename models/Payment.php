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

    public function pay($id) //PSR 1-4,7, статичный размер для, центровку по картинке, базовая картинка template, пагинация jquery
    {
        try {
        $operation_id = $this->makePayment(Yii::$app->session['idPayment']);
        $check = new Check($operation_id);
        } catch (InternalErrorException $e) { //показывать id операции 11 status, если чек приход долго, обратитесь в службу поддержки
            sleep(3);
            $operation_id = $this->makePayment(Yii::$app->session['idPayment']);
            $check = new Check($operation_id);
        } catch (UnprocessableEntityHttpException $e){
            $error = json_decode($e->getMessage(), true);
            if (in_array($error[0]['field'], $this->attrs))
                $this->addErrors([$error[0]['field'] => $error[0]['message']]);
            else
                throw new InternalErrorException('Непредвиденные технические проблемы');
            return false;
        }
        return $check;
    }
}