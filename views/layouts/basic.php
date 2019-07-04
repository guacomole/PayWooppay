<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use app\widgets\Alert;
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <header>
        <nav class="navbar navbar-default navbar-static-top" style="position: -webkit-sticky; position: sticky; top: 0;
    background-color: darkorange; margin-bottom: 30px;">
            <p class="navbar-brand center " style="color:white; font-size: 36px; margin-top:30px; margin-bottom: -20px"><b>PayWooppay</b></p>
        </nav>
    </header>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

