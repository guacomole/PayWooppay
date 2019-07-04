
<?php if ( $categories ): ?>
    <div class="btn-wrapper">
    <?php
    $pathAction = Yii::$app->controller->route;
    $pathLink = 'service';
    foreach ($categories as $category){
        echo
        "<a class=\"btn btn-default category-btn\" href=", Yii::$app->urlManager->createUrl([$pathLink, 'category_id' => $category['id']]), ">" .
            "<img src=\"" . $category['picture_url']  . "\"" . "width=\"50\" height=\"50\" hspace=\"20\" alt=\"Картинка\">" .
            $category['title'] .
        "</a>";
    }
    ?>
    </div>
<?php endif; ?>

