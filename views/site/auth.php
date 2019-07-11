<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'Form', 'class' => 'form-div center-block'], 'action' => ['site/auth'], ]) ?>
<?php echo Html::img('/images/wooppay.png', ['class' => 'image']),
'<br>',
Html::tag('h4', 'Вход в Wooppay кошелёк', ['style' => 'text-align:center;']); ?>
<?php if ( Yii::$app->session->hasFlash('error')) {
    echo Html::tag('h5', Yii::$app->session->getFlash('error'));
}
    ?>
<?= $form->field($model, 'login')->widget(MaskedInput::class, ['mask' => '+7 (999)-999-99-99',]); ?>
<?= $form->field($model, 'password')->passwordInput();  ?>
<?= Html::submitButton('Войти', ['class' => ['btn btn-info center-block'],]) ?>
<?php $form = ActiveForm::end() ?>

