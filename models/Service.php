<?php


namespace app\models;

use app\components\CoreProxy;


class Service
{
    public $totalCount;
    public $fields;
    public $id;
    public $title;
    public $picture_url;

    public function __construct($id=null, $title=null, $picture_url=null, $fields=null)
    {
        $this->fields = $fields;
        $this->id = $id;
        $this->title = $title;
        if (@file_get_contents($picture_url, 0, NULL, 0, 1)) {
            $this->picture_url = $picture_url;
        } else {
            $this->picture_url = '/images/no_data.jpg';
        }
        return $this;
    }

    public function find($page=null, $id=null, $categoryId=null)
    {
        if (!$id) {
            $response = CoreProxy::getService($page, $id, $categoryId);
            $this->totalCount = $response->headers->get('X-Pagination-Total-Count');
            $response = json_decode($response->content, true);
            $services = [];
            foreach ($response as $service) {
                $service = new Service($service['id'], $service['title'], $service['picture_url']);
                array_push($services, $service);
            }
            return $services;
        } else{
            $response = CoreProxy::getService($page, $id, $categoryId);
            $response = json_decode($response->content, true);
            $service = new Service($response['id'], $response['title'], $response['picture_url'], $response['fields']);
            return $service;
        }
    }
}