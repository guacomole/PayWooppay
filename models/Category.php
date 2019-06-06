<?php


namespace app\models;


use app\components\CoreProxy;
use yii\base\Model;

class Category extends Model
{
    public $categories;

    public function find($parentId=199)
    {
        $response = CoreProxy::getCategories($parentId);
        $this->categories= json_decode($response->content, true);

    }
}