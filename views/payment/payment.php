<?php
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php if ( isset($paymentModel) ): ?>
    <?php
    $form = ActiveForm::begin( [
        'options' => ['id' => 'PaymentForm', 'class' => 'form-div center-block'],
        'action' => [ Url::to(['payment', 'id' => Yii::$app->session['idPayment']]) ]
    ]);
    echo Html::img($paymentModel->picture_url, ['class' => 'image']),
    '<br>',
    Html::tag('h4', $paymentModel->service_title, ['style' => 'text-align:center']);
    if ( Yii::$app->session->hasFlash('error') ){
        echo Html::tag('h5', Yii::$app->session->getFlash('error'), ['style' => 'text-align:center; color:red']);
    }
    foreach($paymentModel->attrs as $name)  //вывод полей
    {
        if ( isset($paymentModel->params[$name]['mask']) ){   //если у поля есть маска
            echo $form->field($paymentModel, $name)->widget(MaskedInput::class, [
               'mask' => $paymentModel->params[$name]['mask'],
            ])->label($paymentModel->labels[$name]);
        } elseif ( isset($paymentModel->params[$name]) ){   //если есть html параметры валидации
            echo $form->field($paymentModel, $name)->textInput($paymentModel->params[$name])->label($paymentModel->labels[$name]);
        } else{ //вывод простейшего поля
            echo $form->field($paymentModel, $name)->label($paymentModel->labels[$name]);
        }
    }
        echo Html::submitButton('Оплатить', ['class' => 'btn btn-primary']);
        ActiveForm::end();
        ?>
<?php endif; ?>
