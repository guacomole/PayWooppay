<?php


namespace app\models;


use app\components\CoreProxy;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\base\Model;

class Category extends Model
{
    public $id;
    public $title;
    public $picture_url;

    public function __construct($id=null, $title=null, $picture_url=null)
    {
        $this->id = $id;
        $this->title = $title;
        if (@file_get_contents($picture_url, 0, NULL, 0, 1)) {
            $this->picture_url = $picture_url;
        } else {
            $this->picture_url = '/images/no.jpg';
        }
        return $this;
    }

    public function find($parentId=199)
    {
        $response = CoreProxy::getCategories($parentId);
        $response= json_decode($response->content, true);
        $categories = [];
        foreach ($response as $category){
            $category = new Category($category['id'], $category['title'], $category['picture_url']);
            array_push($categories, $category);
        }
        if ( empty($categories) ) {
            throw new InternalErrorException('Невозможно отобразить категории, попробуйте позже.', 500);
        }
        return $categories;
    }
}