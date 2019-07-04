<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

?>
<div class="form-div">
    <?php echo "<h3><br><em>Авторизация</em><br></h3>" ?>
    <?php if ( Yii::$app->session->hasFlash('error')): ?>
        <?php
        echo Html::tag('h5', Yii::$app->session->getFlash('error'));
        ?>
    <?php endif; ?>
    <?php $form = ActiveForm::begin(['options' => ['id' => 'Form'], 'action' => ['site/auth'],
        //'enableClientValidation' => false,
        ]) ?>
    <?= $form->field($model, 'login')->widget(MaskedInput::class, ['mask' => '+7 (999)-999-99-99',]); ?>
    <?= $form->field($model, 'password')->passwordInput();  ?>
    <?= Html::submitButton('Войти', ['class' => ['btn btn-success'],]) ?>
    <?php $form = ActiveForm::end() ?>
</div>
