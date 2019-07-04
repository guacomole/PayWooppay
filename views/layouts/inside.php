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
    <!-- Классы navbar и navbar-default (базовые классы меню) -->
    <header>
    <nav class="navbar navbar-default navbar-static-top" style="position: -webkit-sticky; position: sticky; top: 0;
    background-color: darkorange; margin-bottom: 30px;">
        <!-- Контейнер (определяет ширину Navbar) -->
        <div class="container-fluid" style = "margin-bottom: 30px; margin-top:30px;">
            <!-- Заголовок -->
            <div class="navbar-header">
                <!-- Бренд или название сайта (отображается в левой части меню) -->
                <a class="navbar-brand" style="color:white; font-size: 28px;"><b>PayWooppay</b></a>
            </div>
            <!-- Основная часть меню (может содержать ссылки, формы и другие элементы) -->
            <div class="collapse navbar-collapse" id="navbar-main">
                <!-- Содержимое основной части -->
                <ul class="nav navbar-nav"  id="blah">
                    <li class="super-btn"> <?= Html::a('Список сервисов', ['service'], ['style'=>'color:white; display:block;']) ?></li>
                    <li class="super-btn"> <?= Html::a('Список категорий', ['category'], ['style'=>'color:white; display:block;']) ?></li>
                </ul>
                <div class="navbar-right" style="margin-right:50px; color:white;">
                    <p class="navbar-text" style="margin-right: 50px; color:white; font-size:16px;"><b>Ваш номер:  <?php echo Yii::$app->session['phone']; ?></b></p>
                <?= Html::a('Выйти', ['site/logout'],
                    ['class' => ' navbar-btn btn btn-danger navbar-right'])
                ?>
                </div>
            </div>
        </div>
    </nav>
    </header>
    <?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<script>
    $(function() {
        var loc = window.location.href;
        $('#blah li').each(function() {
            var link = $(this).find('a:first').attr('href');
            if(loc.indexOf(link) >= 0)
                $(this).addClass('active-menu');

        });
    });
</script>
<?php $this->endPage() ?>
