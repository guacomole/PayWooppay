<?php


namespace app\forms;

use app\models\Service;
use yii\base\ErrorException;
use yii\base\UnknownPropertyException;
use app\models\Payment;
use yii\httpclient\Exception;
use yii\web\ServerErrorHttpException;

class PaymentForm extends Service
{
    public $attrs = []; //программные названия полей, будущие атрибуты динамической модели
    public $params = []; //ассоциативный массив, $attr => html код для превалидации
    public $labels = []; //клиентские названия полей

    public function __construct($id)
    {
        try{
            $service = $this->find(null, $id);
            parent::__construct($service->id, $service->title, $service->picture_url, $service->fields);
            $this->getHtmlRules();
            return $this;
        }
        catch (Exception $e) {
            throw new ServerErrorHttpException('Непредвиденные технические проблемы.');
        }
    }

    public function getHtmlRules()
    {
        $fields = $this->fields;
        try {
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
        catch (ErrorException $e){
            throw new \Exception('Невозможно отобразить сервис.');
        }
    }

    public function getRules()
    {
        $fields = $this->fields;
        try {
        $model = new Payment($this->attrs, $this->params, $this->labels);
        $model->addRule($this->attrs, 'trim');
        $model->addRule($this->attrs, 'pay');
            foreach ($fields as $field) { //get rules for js validation in form
                if ($field['hidden']) continue;
                foreach ($field['validations'] as $validation) {
                    if (isset($validation['param']['allowEmpty'])) unset($validation['param']['allowEmpty']);
                    if ($validation['type'] == 'length') {
                        $model->addRule($field['name'], $field['type'], [$validation['type'] => $validation['param']]);
                    } elseif (isset($this->params[$field['name']]['mask']) and isset($validation['param']['pattern'])) {
                        $model->addRule($field['name'], $validation['type'], ['pattern' => '/' . '^((8|7|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$' . '/']);
                    } elseif ($validation['type'] == 'numerical') {
                        $model->addRule($field['name'], 'integer', $validation['param']);
                    } else {
                        $model->addRule($field['name'], $validation['type'], $validation['param']);
                    }
                }
            }
        }
        //catch (UnknownPropertyException $e){
            //throw new Exception('Невозможно отобразить сервис.');
        //}
        catch (\Exception $e){
            throw new \Exception('Невозможно отобразить сервис.');
        }
        return $model;
    }
}