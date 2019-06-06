<?php
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\ActiveForm; ?>

<?php
    if (isset($error)) echo 'ОШИБКА';
    elseif (isset($success)) echo $success;
    $form = ActiveForm::begin(['options' => ['id' => 'PaymentForm'], 'action' => ['site/payment?id='.Yii::$app->session['idPayment']], /*'enableClientValidation' => false*/]);
    echo $paymentModel->account;
    foreach($model->names as $name)
    {
        if ( isset($model->params[$name]['mask']) ){
            echo $form->field($paymentModel, $name)->widget(MaskedInput::class, [
               'mask' => $model->params[$name]['mask'],
            ])->label($model->labels[$name]);
        }
        elseif ( isset($model->params[$name]) ){
            echo $form->field($paymentModel, $name)->textInput($model->params[$name])->label($model->labels[$name]);

        }else{
            echo $form->field($paymentModel, $name)->label($model->labels[$name]);
        }

    }
        echo Html::submitButton('Submit', ['class' => 'btn btn-primary']);
        ActiveForm::end();
        debug($model);
    ?>

