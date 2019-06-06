<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php echo "<h1 ><br><em>Авторизация</em><br></h1>" ?>
<?php if ( isset(Yii::$app->session['error']) and Yii::$app->session['error'] ): ?>
    <?php
    debug(Yii::$app->session['error']);
    Yii::$app->session->destroy();
    ?>
<?php endif; ?>
<?php $form = ActiveForm::begin(['options' => ['id' => 'Form'], 'action' => ['site/auth'],
    //'enableClientValidation' => false,
    ]) ?>
<?= $form->field($model, 'phone')->textInput(['type' => "tel",['pattern' => "/^7\d{10}$/", 'maxlength' => 11]]); ?>
<?= $form->field($model, 'password')->passwordInput(); //$form->field($model, 'rememberMe')->checkbox() ?>
<?= Html::submitButton('Войти', ['class' => ['btn btn-success'],]) ?>
<?php $form = ActiveForm::end() ?>
