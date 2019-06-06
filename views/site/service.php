<?php
use yii\helpers\Url;
use yii\helpers\Html;
/** @var Array` $services */
?>
<?php if ( (true) ): ?>
    <?php
    $pathAction = Yii::$app->controller->route;
    $pathLink = 'site/payment';
    foreach ($services as $service){
        echo
        "<a class=\"btn btn-default service-btn\" href=", Url::to([$pathLink, 'id' => $service['id']], true), ">" .
            "<img src=\"" . $service['picture_url']  . "\"" . "width=\"50\" height=\"50\" hspace=\"30\" alt=\"Картинка\">" .
            $service['title'] .
        "</a>";
    }
    echo "<br>";
    if ($pageCount > 1 and isset($_GET['category_id'])) {
        foreach (range(1, $pageCount) as $page) {
            echo
            Html::a($page, [$pathAction, 'page' => $page, 'category_id' => $_GET['category_id'] ], ['class' => 'btn btn-default page-btn']);
        }
    }
    elseif($pageCount > 1){
        foreach (range(1, $pageCount) as $page) {
            echo
            Html::a($page, [$pathAction, 'page' => $page], ['class' => 'btn btn-default page-btn']);
        }
    }
    ?>

<?php endif; ?>