<?php


namespace app\models;


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
}