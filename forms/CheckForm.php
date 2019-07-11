<?php


namespace app\forms;


use yii\base\Model;

class CheckForm extends Model
{
    public $operation_id;


    public function rules()
    {
        return [
            ['operation_id', 'required'],
        ];
    }
}