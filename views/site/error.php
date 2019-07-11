<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = 'Ошибка';
?>
<div class="site-error">
<?php if ($exception->statusCode == 404 or $exception->getCode() == 404) { ?>

    <p class="text-danger text-center lead">Такой страницы не существует. Воспользуйтесь меню ниже.</p>


<?php Yii::$app->session->setFlash('exception', $exception);} elseif ($exception->statusCode == 500 or $exception->getCode() == 500) { ?>
    <p class="text-danger text-center lead"> Упс... Что-то пошло не так... </p>
    <div class="text-center">
        <?= nl2br(Html::encode($exception->getMessage())) ?>
    </div>
    <?php }else{  debug($exception) ?>
    <p class="text-danger text-center lead"> Упс... Что-то пошло не так... </p>
    <div class="text-danger text-center">
        <?= nl2br(Html::encode('Непредвиденные технические проблемы.')) ?>
    </div>
    <?php }  ?>

</div>