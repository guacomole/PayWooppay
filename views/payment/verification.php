<?php

use yii\helpers\Html;
use yii\helpers\Url;


?>
<div class='blue-border center-block' style="text-align:left">
    <h3 class="">Пожалуйста, подтвердите платёж</h3>
    <br>
<?php

foreach($paymentModel->attrs as $name)
{
    echo '<h4>', $paymentModel->labels[$name], ': ',  $paymentModel->$name, '<h4>';
}
echo '<h4>', 'Комиссия', ': ',  $paymentModel->commission, '<h4>',
     '<h4>', 'Итого', ': ',  $paymentModel->total_sum, '<h4>',
     Html::a('Оплатить', Url::to(['check']), ['class' => 'btn btn-info']),
     Html::a('Отмена', [ Url::to(['payment', 'id' => Yii::$app->session['idPayment']]) ], ['class' => 'btn btn-danger']);

// in action
//$id = Yii::$app->request->post('id', []);

?>
</div>