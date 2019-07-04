<?php
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php if ( isset($error) ){
    debug($error);
} ?>
<?php if ( isset($paymentModel) ): ?>
<div class="form-div">
    <?php
    echo Html::img($paymentModel->picture_url, ['class' => 'image']),
        '<br>',
    Html::tag('h4', $paymentModel->service_title);
    if ( isset($error) ){
        echo Html::tag('h5', $error, ['style' => 'text-align:center; color:red']);
    }
    $form = ActiveForm::begin( ['options' => ['id' => 'PaymentForm', ], 'action' => [ Url::to(['payment', 'id' => Yii::$app->session['idPayment']]) ] ]);
    foreach($paymentModel->attrs as $name)  //вывод полей
    {
        if ( isset($paymentModel->params[$name]['mask']) ){   //если у поля есть маска
            echo $form->field($paymentModel, $name)->widget(MaskedInput::class, [
               'mask' => $paymentModel->params[$name]['mask'],
            ])->label($paymentModel->labels[$name]);
        }
        elseif ( isset($paymentModel->params[$name]) ){   //если есть html параметры валидации
            echo $form->field($paymentModel, $name)->textInput($paymentModel->params[$name])->label($paymentModel->labels[$name]);

        } else{ //вывод простейшего поля
            echo $form->field($paymentModel, $name)->label($paymentModel->labels[$name]);
        }
    }
        echo Html::submitButton('Оплатить', ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
</div>
<?php endif; ?>
