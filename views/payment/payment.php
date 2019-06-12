<?php
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php
    if ( Yii::$app->session->hasFlash('error') ){
        debug(Yii::$app->session->getFlash('error'));
    }
    if (isset($success)) echo $success;

    $form = ActiveForm::begin(['options' => ['id' => 'PaymentForm'], 'action' => [ Url::to(['payment', 'id' => Yii::$app->session['idPayment']]) ]]);
    foreach($paymentModel->attrs as $name)
    {
        if ( isset($paymentModel->params[$name]['mask']) ){
            echo $form->field($paymentModel, $name)->widget(MaskedInput::class, [
               'mask' => $paymentModel->params[$name]['mask'],
            ])->label($paymentModel->labels[$name]);
        }
        elseif ( isset($paymentModel->params[$name]) ){
            echo $form->field($paymentModel, $name)->textInput($paymentModel->params[$name])->label($paymentModel->labels[$name]);

        }else{
            echo $form->field($paymentModel, $name)->label($paymentModel->labels[$name]);
        }

    }
        echo Html::submitButton('Submit', ['class' => 'btn btn-primary']);
        ActiveForm::end();
        if (isset($response)) debug($response);
        debug($paymentModel);
    ?>

