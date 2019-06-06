<?php


namespace app\models;

use app\components\CoreProxy;
use yii\base\Model;

class Service extends Model
{
    public $pageCount;
    public function find($page=null, $id=null, $categoryId=null)
    {
        $response = CoreProxy::getService($page, $id, $categoryId);
        $services = json_decode($response->content, true);
        $this->pageCount = $response->headers->get('x-pagination-page-count');
        return $services;
    }

}