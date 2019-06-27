<?php if( isset($check) and $check->checkInPDF ): ?>
<?php file_put_contents('check.pdf', $check->checkInPDF);  ?>
    <label for="showCheckBtn" class="btn btn-default btn-info check-btn">Показать чек</label>
    <input type="checkbox" id="showCheckBtn">
    <div class="text"><?php
    echo '<h2 style="text-align: center">ЧЕК</h2>' . '<br>' .
         'Номер квитанции: ' . $check->operation_id . '<br>' .
         'Название сервиса: ' . $check->service_title . '<br>' .
          $check->ident .
         'Время: ' . $check->time . '<br>' .
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

<?php elseif( isset($check) and $check->operation_id  ): ?>
    <?php
        echo 'Превышено время ожидания операции. Обратитесь в техподдержку, ваш номер операции ', $check->operation_id;
    ?>
<?php endif; ?>