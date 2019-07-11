<?php


namespace app\forms;

use app\models\Service;
use app\models\Payment;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\base\Exception;


class PaymentForm extends Payment
{
    public $attrs = []; //атрибуты динамической модели, которые заполняются в форме
    public $params = []; //ассоциативный массив, $attr => html код для превалидации
    public $labels = []; //клиентские названия полей
    public $service_title;
    public $picture_url;

    public function __construct($id)
    {
        $service = new Service();
        $service = $service->find(null, $id);
        $this->service_title = $service->title;
        $this->picture_url = $service->picture_url;
        try {
            $this->getHtmlRules($service->fields);
            $this->getRules($service->fields);
            return $this;
        } catch (\Exception $e) {
            throw new InternalErrorException('Невозможно отобразить услугу.', 500);
        }
    }

    public function getHtmlRules($fields)
    {
        if ( !$fields ){
            throw new Exception('Пустые поля.');
        }
        foreach ($fields as $field) {  //get params for html validation in form
            if ( $field['hidden'] or $field['name'] == 'txn_id' ) continue;
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
            if ( $field['hidden'] or isset($field['txn_id']) ) continue;
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