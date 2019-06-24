<?php


namespace app\forms;

use app\models\Check;
use app\models\Service;
use app\models\Payment;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\base\DynamicModel;
use yii\web\UnprocessableEntityHttpException;


class PaymentForm extends Payment
{
    public $attrs = []; //программные названия полей, будущие атрибуты динамической модели
    public $params = []; //ассоциативный массив, $attr => html код для превалидации
    public $labels = []; //клиентские названия полей

    public function __construct($id)
    {
        $service = new Service();
        $service = $service->find(null, $id);
        $this->getHtmlRules($service->fields);
        $this->getRules($service->fields);
        return $this;
    }

    public function getHtmlRules($fields)
    {
        if ( !$fields ){
            throw new \Exception('Невозможно отобразить сервис.');
        }
        foreach ($fields as $field) {  //get params for html validation in form
            if ($field['hidden']) continue;
            $this->labels[$field['name']] = $field['title'];
            $this->params[$field['name']] = [];
            if ($field['mask']) {
                $this->params[$field['name']]['mask'] = $field['mask'];
            }
            if ($field['type'] == 'amount' or $field['name'] == 'amount') {
                $this->params[$field['name']]['type'] = 'number';
            }
            array_push($this->attrs, $field['name']);
            foreach ($field['validations'] as $validation) {
                if (isset($validation['param']['pattern'])) {
                    $validation['param']['pattern'] = substr($validation['param']['pattern'], 1, -1);
                }
                if (isset($validation['param']['allowEmpty'])) {
                    unset($validation['param']['allowEmpty']);
                }
                $this->params[$field['name']] = array_merge($this->params[$field['name']], $validation['param']);
            }
            if (!isset($this->params[$field['name']]['type']) and isset($this->params[$field['name']]['max'])) {
                $this->params[$field['name']]['maxlength'] = $this->params[$field['name']]['max'];
                unset($this->params[$field['name']]['max']);
            }
        }
    }

    public function getRules($fields)
    {
        foreach ($this->attrs as $attr) {
            $this->defineAttribute($attr);
            }
        $this->addRule($this->attrs, 'trim');
        $this->addRule($this->attrs, 'paymentValidate');
        foreach ($fields as $field) { //get rules for js validation in form
            if ($field['hidden']) continue;
            foreach ($field['validations'] as $validation) {
                if (isset($validation['param']['allowEmpty'])) unset($validation['param']['allowEmpty']);
                if ($validation['type'] == 'length') {
                    $this->addRule($field['name'], $field['type'], [$validation['type'] => $validation['param']]);
                } elseif (isset($this->params[$field['name']]['mask']) and isset($validation['param']['pattern'])) {
                    $this->addRule($field['name'], $validation['type'], ['pattern' => '/' . '^((8|7|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$' . '/']);
                } elseif ($validation['type'] == 'numerical') {
                    $this->addRule($field['name'], 'integer', $validation['param']);
                } else {
                    $this->addRule($field['name'], $validation['type'], $validation['param']);
                }
            }
        }
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
}