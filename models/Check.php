<?php


namespace app\models;


use app\components\CoreProxy;
use yii\base\Model;

class Check extends Model
{
    public $id;
    public function __construct($id)
    {
       $this->id = $this->getBankCheck($id);
       return $this;
    }
    public function getBankCheck($operation_id)
    {
        $response = CoreProxy::getBankCheck($operation_id);
        return json_decode($response->content, true);
    }
}