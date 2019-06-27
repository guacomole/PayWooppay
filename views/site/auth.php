<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

?>
<div class="form-div">
    <?php echo "<h1 ><br><em>Авторизация</em><br></h1>" ?>
    <?php if ( Yii::$app->session->hasFlash('error') ): ?>
        <?php
        debug(Yii::$app->session->getFlash('error'));
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
