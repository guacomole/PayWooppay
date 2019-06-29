<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">
<?php if ($exception->statusCode == 404 or $exception->getCode() == 404) { ?>
    <p class="text-danger text-center lead">Такой страницы не существует. Воспользуйтесь меню ниже.</p>

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger">
       <?= nl2br(Html::encode($message)) ?>
   </div>
<?php } elseif ($exception->statusCode == 500 or $exception->getCode() == 500) { ?>
    <p class="text-danger text-center lead"> Упс... Что-то пошло не так... </p>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($exception->getMessage())) ?>
    </div>
    <?php } ?>
    <?php debug($exception) ?>
</div>