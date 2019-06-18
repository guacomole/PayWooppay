<?php

use app\assets\AppAsset;
use yii\helpers\Html;
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
<div class="wrap">
    <div class="container">
        <ul class="nav nav-pills" >
            <li role="presentation"><?= Html::a('Список сервисов', ['service']) ?></li>
            <li role="presentation"><?= Html::a('Список категорий', ['category']) ?></li>
            <h4 style="text-align:right"> Ваш номер:  <?php echo Yii::$app->session['phone']; ?></h4>
        </ul>
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
