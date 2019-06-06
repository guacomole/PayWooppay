<?php
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\ActiveForm; ?>


<?php
    if ( Yii::$app->session->hasFlash('error') ){
        debug(Yii::$app->session->getFlash('error'));
    }
    if (isset($success)) echo $success;
    $form = ActiveForm::begin(['options' => ['id' => 'PaymentForm'], 'action' => ['site/payment?id='.Yii::$app->session['idPayment']], /*'enableClientValidation' => false*/]);

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
        if (isset($response)) debug($response);

    ?>

