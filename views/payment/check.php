<?php
use yii\helpers\Html;
debug($post);
?>
<?php if( /* isset($check) and $check->checkInPDF */ false): ?>
<?php file_put_contents('check.pdf', $check->checkInPDF);  ?>
    <img src="/images/check-success.png" width="150px" height="150px" class="center-block" alt="Оплата проведена успешно.">
    <br>
    <h3 class="text-center" style="margin-top: -20px">Оплата проведена успешно.</h3>
    <!--<label for="showCheckBtn" class="btn btn-default btn-info check-btn">Показать чек</label>
    <input type="checkbox" id="showCheckBtn"> -->
    <div class="text center-block"><?php
    echo '<h2 style="text-align: center">ЧЕК</h2>' . '<br>' .
         'Номер квитанции: ' . $check->operation_id . '<br>' .
         'Название сервиса: ' . $check->service_title . '<br>' .
          $check->ident .
         'Дата и время: ' . $check->time . '<br>' .
         'Сумма платежа: ' . $check->amount . '<br>' .
         'Комиссия: ' . $check->commission . '<br>' .
         'Итого: ' . $check->admit . '<br>';

    ?>
        <label for="printBtn" class="btn btn-default btn-info print-btn" onclick="print('check.pdf')">Распечатать чек</label>
    </div>
    <script>
        function print(doc) {
            var objFra = document.createElement('iframe');   // Create an IFrame.
            objFra.style.visibility = "hidden";    // Hide the frame.
            objFra.src = doc;                      // Set source.
            document.body.appendChild(objFra);  // Add the frame to the web page.
            objFra.contentWindow.focus();       // Set focus.
            objFra.contentWindow.print();      // Print it.
        }
    </script>

<?php elseif( isset($check) and $check->operation_id): ?>
    <img src="/images/crash.jpg" width="150px" height="150px" class="center-block" alt="Ошибка...">
    <br>
    <h2 class="text-center">Извините <br> </h2>
    <h3 class="text-center">
        Превышено время ожидания операции. Обратитесь в техподдержку, ваш номер операции <?php echo $check->operation_id; ?> <br>
        Или запросите чек ещё раз <br>
        Круглосуточная служба поддержки: +7 771 015 15 15 <br>
    </h3>
    <?php echo Html::a('Запросить чек', ['check'], [
        'class'=>'btn btn-info',
        'data'=>[
            'method'=>'post',
            'params'=>[
                'operation_id'=> 'value',
    ],
    ]
    ]); ?>​
    <?php echo Html::a('Ссылка', ['/payment/check'], [
        'data' => [
            'method' => 'post',
            'params' => [
                'param1' => 'value1',
                'param2' => 'value2',
            ],
        ],
    ]); ?>

<?php endif; ?>

