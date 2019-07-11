<?php

use yii\helpers\Html;
use yii\helpers\Url;


?>
<div class='form-div center-block' style="">
    <h3 class="">Пожалуйста, подтвердите платёж</h3>
<?php
echo Html::img($paymentModel->picture_url, ['class' => 'image']),
'<br>',
Html::tag('h4', $paymentModel->service_title, ['style' => 'text-align:center']);
foreach($paymentModel->attrs as $name)
{
    echo '<h4>', $paymentModel->labels[$name], ': ',  $paymentModel->$name, '<h4>';
}
echo '<h4>', 'Комиссия', ': ',  $paymentModel->commission, '<h4>',
     '<h4>', 'Итого', ': ',  $paymentModel->total_sum, '<h4>',
     Html::a('Оплатить', Url::to(['/payment/pay']), ['class' => 'btn btn-info']),
     Html::a('Отмена', [ Url::to(['payment', 'id' => Yii::$app->session['idPayment']]) ], ['class' => 'btn btn-danger']);

?>
</div>