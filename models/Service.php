<?php


namespace app\models;

use app\components\CoreProxy;

class Service
{
    public $pageCount;
    public $fields;
    public $id;
    public $title;
    public $picture_url;

    public function __construct($id=null, $title=null, $picture_url=null, $fields=null)
    {
        $this->fields = $fields;
        $this->id = $id;
        $this->title = $title;
        $this->picture_url = $picture_url;
        return $this;
    }

    public function find($page=null, $id=null, $categoryId=null)
    {
        if (!$id) {
            $response = CoreProxy::getService($page, $id, $categoryId);
            $this->pageCount = $response->headers->get('x-pagination-page-count');
            $response = json_decode($response->content, true);
            $services = [];
            foreach ($response as $service) {
                if ($service['is_simple']) {
                    $service = new Service($service['id'], $service['title'], $service['picture_url']);
                    array_push($services, $service);
                }
            }
            return $services;
        }
        else{
            $response = CoreProxy::getService($page, $id, $categoryId);
            $response = json_decode($response->content, true);
            $service = new Service($response['id'], $response['title'], $response['picture_url'], $response['fields']);
            return $service;
        }
    }
}