<?php


namespace app\forms;

use app\models\Service;
use Yii;
use app\models\Payment;

class PaymentForm extends Service
{
    public $names = [];
    public $params = [];
    public $labels = [];
    public $fields;
    public $service_id;
    public $maskPattern;


    public function __construct($id)
    {
        $this->fields = $this->find(null, $id)['fields'];
        $this->getHtmlRules();
        $this->getRules();
        return $this;
    }

    public function getHtmlRules()
    {
        $fields = $this->fields;
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
            array_push($this->names, $field['name']);
            foreach ($field['validations'] as $validation) {
                if (isset($validation['param']['pattern'])){
                    $validation['param']['pattern'] = substr($validation['param']['pattern'], 1,-1);
                }
                if ( isset($validation['param']['allowEmpty']) ) {
                    unset($validation['param']['allowEmpty']);
                }
                $this->params[$field['name']] = array_merge($this->params[$field['name']], $validation['param']);
            }
            if (!isset($this->params[$field['name']]['type']) and isset($this->params[$field['name']]['max'] ) ) {
                $this->params[$field['name']]['maxlength'] = $this->params[$field['name']]['max'];
                unset($this->params[$field['name']]['max']);
            }
        }
    }

    public function getRules()
    {
        $fields = $this->fields;
        $model = new Payment($this->names, $this->params);
        $model->addRule($this->names, 'trim');
        foreach ($fields as $field) { //get rules for js validation in form
            if ($field['hidden']) continue;
            foreach ($field['validations'] as $validation) {
                if ( isset($validation['param']['allowEmpty']) ) unset($validation['param']['allowEmpty']);
                if ( $validation['type'] == 'length' ) {
                    $model->addRule($field['name'], $field['type'], [$validation['type'] => $validation['param']]);
                } elseif(isset($this->params[$field['name']]['mask']) and isset($validation['param']['pattern'])){
                    $model->addRule($field['name'], $validation['type'], $validation['param']);
                } elseif ($validation['type'] == 'numerical') {
                    $model->addRule($field['name'], 'integer', $validation['param']);
                }  else {
                    $model->addRule($field['name'], $validation['type'], $validation['param']);
                }
            }
        }
        return $model;
    }
}