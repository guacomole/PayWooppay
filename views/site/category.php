<?php
use yii\helpers\Html;
?>

<?php if ( isset($categoryModel) ): ?>
    <?php
    $pathAction = Yii::$app->controller->route;
    $pathLink = 'site/service';
    foreach ($categoryModel->categories as $category){
        echo
        "<a class=\"btn btn-default category-btn\" href=", Yii::$app->urlManager->createUrl([$pathLink, 'category_id' => $category['id']]), ">" .
            "<img src=\"" . $category['picture_url']  . "\"" . "width=\"50\" height=\"50\" hspace=\"20\" alt=\"Картинка\">" .
            $category['title'] .
        "</a>";
    }

    debug($categoryModel);
    ?>

<?php endif; ?>

