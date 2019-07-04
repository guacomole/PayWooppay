<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
/** @var Array` $services */
?>
<?php
    if ( Yii::$app->session->hasFlash('error') ){
        debug(Yii::$app->session->getFlash('error'));
    }
    ?>

<?php if ( ($services) ): ?>
    <div class="btn-wrapper">
    <?php
        foreach ($services as $service){
            echo
                "<a class=\"btn btn-default service-btn\" href=", Url::to(['payment', 'id' => $service->id], true), ">",
                "<img src=\"" . $service->picture_url  . "\"" . " width=\"50\" height=\"50\" hspace=\"25\" alt=\"Картинка\">",
                $service->title,
                "</a>";
    }
    ?>
    </div>
    <br>
    <?php
        echo LinkPager::widget(['pagination' => $pages, 'class' => 'pagination']);
    ?>
<?php endif; ?>